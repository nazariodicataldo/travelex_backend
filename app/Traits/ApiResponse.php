<?php
namespace App\Traits;

use function PHPUnit\Framework\isNull;

trait ApiResponse
{
    /* Helper per generare delle response Json */
    /* Utile sia per risposte di successo che errori */
    protected function apiResponse(
        bool $success,
        ?mixed $dataOrErrors = null,
        int $code = 200,
        ?string $message = null,
    ) {
        $payload = [
            "success" => $success,
            $success ? "data" : "errors" => $dataOrErrors,
            "timestamp" => now()->format("Y-m-d H:i:s"),
        ];

        if ($message) {
            $payload["message"] = $message;
        }

        return response()->json($payload, $code);
    }
}

?>
