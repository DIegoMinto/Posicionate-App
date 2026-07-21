<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.url');
        $this->apiKey = config('services.whatsapp.key');
    }

    private function headers()
    {
        return [
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ];
    }

    public function index()
    {
        $usuario = auth()->user();
        return view('dashboard.wpsender', compact('usuario'));
    }

    public function qr()
    {
        try {

            $user = auth()->user();

            if (!$user->instance_name) {
                $user->instance_name = $user->codigo_personal;
                $user->save();
            }

            $instance = $user->instance_name;

            $check = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/instance/connectionState/{$instance}");

            if ($check->status() === 404) {

                $create = Http::withHeaders($this->headers())
                    ->post("{$this->baseUrl}/instance/create", [
                        "instanceName" => $instance,
                        "integration" => "WHATSAPP-BAILEYS",
                        "qrcode" => true,
                        "tokenStoreType" => "DATABASE"
                    ]);

                if ($create->failed()) {
                    return response()->json([
                        'error' => 'Error al crear en Render: ' . $create->body()
                    ], 500);
                }
                sleep(2);
            }

            $response = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/instance/connect/{$instance}");

            return response()->json([
                'instance' => $instance,
                'data' => $response->json()
            ]);

        } catch (\Exception $e) {
            dd([
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine()
            ]);
        }
    }

    public function status($instance)
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/instance/connectionState/{$instance}");

            return response()->json($response->json());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function viewSend()
    {
        $usuario = auth()->user();
        return view('wpsender.send', compact('usuario'));
    }

    public function send(Request $request)
    {
        set_time_limit(120);

        $instance = auth()->user()->codigo_personal;

        if (!$instance) {
            return response()->json([
                'ok' => false,
                'error' => 'Instancia no encontrada'
            ], 400);
        }

        $numero = preg_replace('/[^0-9]/', '', $request->numeros);
        $modo = $request->modo ?? 'junto';

        try {

            $responseMedia = null;
            $responseText = null;

            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $mime = $file->getMimeType();
                $base64 = base64_encode(file_get_contents($file));
                $safeName = str_replace(' ', '_', $file->getClientOriginalName());

                if (str_contains($mime, 'video')) {
                    $mediatype = 'video';
                } elseif (str_contains($mime, 'image')) {
                    $mediatype = 'image';
                } else {
                    $mediatype = 'document';
                }
                if ($modo === 'junto') {

                    $responseMedia = Http::withHeaders($this->headers())
                        ->timeout(60)
                        ->post("{$this->baseUrl}/message/sendMedia/{$instance}", [
                            "number" => $numero,
                            "mediatype" => $mediatype,
                            "mimetype" => $mime,
                            "caption" => $request->mensaje,
                            "media" => $base64,
                            "fileName" => $safeName
                        ]);
                } else {

                    $responseMedia = Http::withHeaders($this->headers())
                        ->timeout(60)
                        ->post("{$this->baseUrl}/message/sendMedia/{$instance}", [
                            "number" => $numero,
                            "mediatype" => $mediatype,
                            "mimetype" => $mime,
                            "media" => $base64,
                            "fileName" => $safeName
                        ]);
                    sleep(2);

                    $responseText = Http::withHeaders($this->headers())
                        ->post("{$this->baseUrl}/message/sendText/{$instance}", [
                            "number" => $numero,
                            "text" => $request->mensaje
                        ]);
                }

            } else {

                $responseText = Http::withHeaders($this->headers())
                    ->post("{$this->baseUrl}/message/sendText/{$instance}", [
                        "number" => $numero,
                        "text" => $request->mensaje
                    ]);
            }

            return response()->json([
                'ok' => true,
                'media' => $responseMedia ? $responseMedia->json() : null,
                'text' => $responseText ? $responseText->json() : null
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }


    }

    public function getGroups()
    {
        try {
            $instance = auth()->user()->instance_name ?? auth()->user()->codigo_personal;

            if (!$instance) {
                return response()->json(['error' => 'No se encontró el nombre de la instancia.'], 400);
            }

            $response = Http::withHeaders($this->headers())
                ->timeout(60)
                ->get("{$this->baseUrl}/group/fetchAllGroups/{$instance}?getParticipants=false");

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Evolution API devolvió un error.',
                    'status' => $response->status()
                ], $response->status());
            }

            $groups = $response->json();
            return response()->json(is_array($groups) ? $groups : []);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excepción interna en Laravel.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportGroupContactsExcel(Request $request)
    {
        $request->validate([
            'groupJids' => 'required|array',
            'groupJids.*' => 'string',
        ]);

        try {
            $user = auth()->user();
            $instance = $user->instance_name ?? $user->codigo_personal;

            $phones = [];

            foreach ($request->groupJids as $groupJid) {
                $response = Http::withHeaders($this->headers())
                    ->timeout(60)
                    ->get("{$this->baseUrl}/group/participants/{$instance}", [
                        'groupJid' => $groupJid,
                    ]);

                if ($response->failed()) {
                    continue;
                }

                $participants = $response->json('participants') ?? [];

                foreach ($participants as $p) {
                    $isLid = str_ends_with($p['id'], '@lid');
                    $phone = null;

                    if ($isLid) {
                        if (!empty($p['phoneNumber'])) {
                            $phone = explode('@', $p['phoneNumber'])[0];
                        }
                    } else {
                        $phone = explode('@', $p['id'])[0];
                    }

                    if ($phone) {
                        $phones[$phone] = true;
                    }
                }
            }

            $phoneList = array_keys($phones);

            if (empty($phoneList)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'No se encontraron números resolubles en los grupos seleccionados.'
                ], 422);
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Numero');

            $row = 2;
            foreach ($phoneList as $phone) {
                $sheet->setCellValueExplicit(
                    "A{$row}",
                    $phone,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                $row++;
            }

            $fileName = 'contactos_' . now()->format('Y-m-d_His') . '.xlsx';
            $tempPath = storage_path("app/{$fileName}");

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($tempPath);

            return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => 'Excepción al generar el Excel.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLabels()
    {
        $user = auth()->user();
        $instance = $user->instance_name ?? $user->codigo_personal;

        $response = Http::withHeaders($this->headers())
            ->timeout(30)
            ->get("{$this->baseUrl}/label/findLabels/{$instance}");

        return response()->json($response->json());
    }

    public function exportLabelContactsExcel(Request $request)
    {
        $request->validate([
            'labelIds' => 'required|array',
            'labelIds.*' => 'string',
        ]);

        try {
            $chats = \DB::connection('evolution')
                ->table('Chat')
                ->whereNotNull('labels')
                ->get();

            $lidMap = [];

            $messages = \DB::connection('evolution')
                ->table('Message')
                ->whereRaw("key::text LIKE '%@lid%'")
                ->select('key')
                ->get();

            foreach ($messages as $msg) {
                $keyData = json_decode($msg->key, true);
                if (!$keyData)
                    continue;

                $lidJid = $keyData['remoteJid'] ?? null;
                $altJid = $keyData['remoteJidAlt'] ?? null;
                if ($lidJid && $altJid && str_ends_with($lidJid, '@lid') && !str_ends_with($altJid, '@lid')) {
                    $lidMap[explode('@', $lidJid)[0]] = explode('@', $altJid)[0];
                }

                $participantLid = $keyData['participant'] ?? null;
                $participantAlt = $keyData['participantAlt'] ?? null;
                if ($participantLid && $participantAlt && str_ends_with($participantLid, '@lid') && !str_ends_with($participantAlt, '@lid')) {
                    $lidMap[explode('@', $participantLid)[0]] = explode('@', $participantAlt)[0];
                }
            }

            $phones = [];
            $sinResolver = [];

            foreach ($chats as $chat) {
                $chatLabelIds = json_decode($chat->labels, true) ?? [];
                $matches = array_intersect($request->labelIds, $chatLabelIds);

                if (!empty($matches)) {
                    $jid = $chat->remoteJid ?? null;

                    if ($jid && !str_ends_with($jid, '@g.us')) {
                        $phone = null;

                        if (str_ends_with($jid, '@lid')) {
                            $lidNumber = explode('@', $jid)[0];
                            $phone = $lidMap[$lidNumber] ?? null;
                        } else {
                            $phone = explode('@', $jid)[0];
                        }

                        if ($phone && is_numeric($phone) && strlen($phone) < 16) {
                            $phones[$phone] = true;
                        } else {
                            $sinResolver[] = $jid;
                        }
                    }
                }
            }

            \Log::info('DEBUG export labels', [
                'telefonos_resueltos' => count($phones),
                'sin_resolver' => $sinResolver,
            ]);

            $phoneList = array_keys($phones);

            if (empty($phoneList)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'No se encontraron contactos válidos con esas etiquetas.',
                    'sin_resolver' => $sinResolver,
                ], 422);
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Numero');
            $sheet->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            $row = 2;
            foreach ($phoneList as $phone) {
                $sheet->setCellValueExplicit("A{$row}", $phone, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $row++;
            }

            $fileName = 'contactos_etiquetas_' . now()->format('Y-m-d_His') . '.xlsx';
            $tempPath = storage_path("app/{$fileName}");

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($tempPath);

            return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => 'Excepción al generar el Excel.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
