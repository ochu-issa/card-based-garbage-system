<?php

namespace App\Traits;

trait HttpResponses
{
    //success responses
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Request was successfully.',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    //error responses
    protected function error($data, $message = null, $code)
    {
        return response()->json([
            'status' => 'Error has occured',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
