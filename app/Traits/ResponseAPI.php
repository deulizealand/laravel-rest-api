<?php

namespace App\Traits;


trait ResponseAPI
{
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true)
    {
        if (!$message) return response()->json(['message' => "Message is Required"], 500);

        if ($isSuccess) {
            return response()->json([
                'message' => $message,
                'error' => !$isSuccess,
                'code' => $statusCode,
                'results' => $data
            ], $statusCode);
        } else {
            return response()->json([
                'message' => $message,
                'error' => !$isSuccess,
                'code' => $statusCode,
            ], $statusCode);
        }
    }


    public function success($message, $data, $statusCode = 200)
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    public function error($message, $data = null, $statusCode = 500)
    {
        return $this->coreResponse($message, $data, $statusCode, false);
    }
}
