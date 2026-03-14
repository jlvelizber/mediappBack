<?php

namespace App\Http\Middleware;

use App\Services\InstallationStateService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsOpen
{
    public function __construct(
        private readonly InstallationStateService $installationStateService
    ) {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        if ($this->installationStateService->isInstalled()) {
            return response()->json([
                'message' => 'La aplicación ya fue inicializada.',
            ], Response::HTTP_CONFLICT);
        }

        return $next($request);
    }
}
