<?php
namespace App\Http\Controllers;
class EvolutionWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->input('event');

        Log::info('Evolution webhook', $request->all());
        if ($event === 'labels.association') {
            $data = $request->input('data');
            LabelAssociation::updateOrCreate([
                'instance' => $request->input('instance'),
                'chat_jid' => $data['chatId'] ?? $data['remoteJid'] ?? null,
                'label_id' => $data['labelId'] ?? null,
            ], [
                'action' => $data['type'] ?? null,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}