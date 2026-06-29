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
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
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



}
