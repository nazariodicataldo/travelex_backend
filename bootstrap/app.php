<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Traits\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(api: __DIR__ . '/../routes/api.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //Gestione delle eccezioni
        $exceptions->render(function (Throwable $e, Request $request) {
            // Gestione universale per le API
            if ($request->is('api/*')) {
                //Determina lo status code
                $code = method_exists($e, 'getStatusCode') //getStatusCode esiste?
                    ? $e->getStatusCode() //se si, ritorna
                    : (method_exists($e, 'getCode') && $e->getCode() >= 100 && $e->getCode() < 600 //altrimenti verifico se esista getCode e se questo è incluso tra 100 e 599
                        ? $e->getCode() //se si ritorno il codice
                        : 500); //sennò errore standard

                //a volte alcuni status code sono stringhe SQL
                $message = 'Internal Server Error';
                $error = $e->getMessage();

                if ($e instanceof AuthenticationException) {
                    $code = 401;
                    $message = 'Invalid token or expired session';
                }

                // Gestione specifica per Record non trovati
                if ($e instanceof NotFoundHttpException) {
                    $code = 404;
                    $message = 'No query results';
                }

                // Gestione specifica per errori di validazione
                if ($e instanceof ValidationException) {
                    $code = 422;
                    $message = 'Invalid data';
                    $error = $e->errors();
                }

                return ApiResponse::apiResponse(false, $error, $code, $message);
            }
        });
    })
    ->create();
