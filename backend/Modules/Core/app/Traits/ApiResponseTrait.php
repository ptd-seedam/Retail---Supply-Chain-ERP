<?php

namespace Modules\Core\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    /**
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', int $status = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'message' => $message,
            'status' => $status,
            'payload' => $data,
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }

    /**
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error', int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'message' => $message,
            'status' => $status,
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'Success', int $status = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'message' => $message,
            'status' => $status,
            'payload' => [
                'items' => $paginator->items(),
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
            ],
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }
}
