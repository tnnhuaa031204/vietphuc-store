use App\Http\Controllers\SePayWebhookController;

Route::post('/sepay/webhook', [SePayWebhookController::class, 'handle']);