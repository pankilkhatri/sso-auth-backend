<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed   $data
     * @param  string  $message
     * @param  int     $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $message = "Success", $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
