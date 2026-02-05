<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    public function successResponse($data, $message = "Success", $status = 200)
{
        return response()->json([
        'message' => $message,
        'data' => $data
    ], $status);
}

public function errorResponse($message = "Error", $status = 400)
{
        return response()->json([
        'message' => $message,
    ], $status);
}

}

