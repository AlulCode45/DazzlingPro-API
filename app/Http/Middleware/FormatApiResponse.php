<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormatApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only format JSON responses
        if ($request->expectsJson() && $response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);

            // If it's already formatted, return as is
            if (isset($data['success'])) {
                return $response;
            }

            // Format the response
            $statusCode = $response->getStatusCode();
            $success = $statusCode >= 200 && $statusCode < 300;

            $formattedData = [
                'success' => $success,
                'message' => $this->getDefaultMessage($statusCode),
                'data' => $data,
            ];

            $response->setData($formattedData);
        }

        return $response;
    }

    /**
     * Get default message based on status code
     */
    private function getDefaultMessage(int $statusCode): string
    {
        return match ($statusCode) {
            200 => 'Berhasil',
            201 => 'Berhasil dibuat',
            204 => 'Berhasil dihapus',
            400 => 'Permintaan tidak valid',
            401 => 'Tidak terautentikasi',
            403 => 'Tidak memiliki akses',
            404 => 'Data tidak ditemukan',
            422 => 'Data tidak valid',
            500 => 'Terjadi kesalahan server',
            default => 'Berhasil',
        };
    }
}
