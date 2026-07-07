<?php
Route::post('/webhooks/evolution', [EvolutionWebhookController::class, 'handle']);