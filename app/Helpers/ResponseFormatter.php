<?php

namespace App\Helpers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

/**
 * Response Formatter
 * Provides consistent API response formatting
 */
class ResponseFormatter
{
    /**
     * Success response for created resources
     */
    public static function created(string $message = "Data successfully created.", mixed $data = null): JsonResponse
    {
        return static::success($message, $data, Response::HTTP_CREATED);
    }

    /**
     * Standard success response
     */
    public static function success(string $message, mixed $data = null, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ], $code);
    }

    /**
     * Error response
     */
    public static function error(string $message, mixed $errors = null, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
            'timestamp' => now()->toISOString()
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     */
    public static function validationError(string $message = 'Validation failed', array $errors = []): JsonResponse
    {
        return static::error($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Not found error response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return static::error($message, null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Unauthorized error response
     */
    public static function unauthorized(string $message = 'Unauthorized access'): JsonResponse
    {
        return static::error($message, null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Forbidden error response
     */
    public static function forbidden(string $message = 'Access forbidden'): JsonResponse
    {
        return static::error($message, null, Response::HTTP_FORBIDDEN);
    }

    /**
     * Response with redirect URL
     */
    public static function redirected(string $message, string $redirect_url, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'redirect_url' => $redirect_url
        ], $code);
    }

    /**
     * Paginated response
     */
    public static function paginated(string $message, mixed $data, array $pagination): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination
        ], Response::HTTP_OK);
    }

    /**
     * Handle exception and return appropriate error response
     */
    public static function handleError(\Exception $e, string $message = 'Internal Server Error, Please try again later.'): JsonResponse  
    {  
        // Log the error for debugging
        \Illuminate\Support\Facades\Log::error('API Error: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);

        $errors = config('app.debug') ? [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ] : null;

        return static::error($message, $errors, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Handle HTTP client errors (4xx responses)
     */
    public static function handleHttpError(\Exception $e, string $defaultMessage = 'External service error'): JsonResponse
    {
        $code = method_exists($e, 'getCode') ? $e->getCode() : Response::HTTP_BAD_REQUEST;
        
        // Ensure we have a valid HTTP status code
        if ($code < 400 || $code >= 600) {
            $code = Response::HTTP_BAD_REQUEST;
        }

        return static::error($defaultMessage, config('app.debug') ? $e->getMessage() : null, $code);
    }
}