<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [

            'status' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [

            'status' => 'false',
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function bookValidation($request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|integer|between:1900,2100',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);
        return $validator;
    }

    public function reviewValidation($request){
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);
        return $validator;
    }

    public function ratingValidation($request){
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'point' => 'required|integer',Rule::in([1, 2, 3, 4, 5]),
        ]);
        return $validator;
    }
}
