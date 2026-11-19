<?php

namespace App\Helper;
class ApiResponse
{
    public static function success($message = 'Success',$data = [], $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message = 'Error', $data = [], $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}