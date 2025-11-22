<?php

namespace Modules\Core\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApiResponseTrait
 *
 * Provides standard API response methods for controllers
 */
trait ApiResponseTrait
{
    /**
     * Return a success response
     *
     * @param mixed $data Response data
     * @param string $message Response message
     * @param int $status HTTP status code
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', int $status = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status,
            'data' => $data,
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }

    /**
     * Return an error response
     *
     * @param string $message Error message
     * @param int $status HTTP status code
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error', int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status' => $status,
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }

    /**
     * Return a paginated response
     *
     * @param LengthAwarePaginator $paginator Paginated data
     * @param string $message Response message
     * @param int $status HTTP status code
     * @return JsonResponse
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'Success', int $status = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status,
            'data' => [
                'items' => $paginator->items(),
                'pagination' => [
                    'total' => $paginator->total(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ],
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }

    /**
     * Return validation error response
     *
     * @param array $errors Validation errors
     * @param int $status HTTP status code
     * @return JsonResponse
     */
    protected function validationErrorResponse(array $errors, int $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => 'Validation failed',
            'status' => $status,
            'errors' => $errors,
            'time' => microtime(true) - LARAVEL_START,
        ];

        return response()->json($response, $status);
    }
}
