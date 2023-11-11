<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;


class ReviewsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Review::all();
            return $this->sendResponse($data, 'Review List');
        } catch (Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->reviewValidation($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $errorCode = 422;
            return $this->sendError('Validation Error', $errorMessages, $errorCode);
        }

        try {
            $data = Review::create($request->all());
            return $this->sendResponse($data, 'One new review created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Review::findOrFail($id);
            return $this->sendResponse($data, 'Review detail');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $authenticatedUserId = Auth::id();
        if ($authenticatedUserId != $review->user_id) {
            return $this->sendError('Unauthorized', 'You are not authorized to update this review.', 403);
        }
        $validator = $this->reviewValidation($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $errorCode = 422;
            return $this->sendError('Validation Error', $errorMessages, $errorCode);
        }

        try {
            $review->update($request->all());
            $updatedReview = $review->fresh();
            return $this->sendResponse($updatedReview, 'Review updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $authenticatedUserId = Auth::id();
        if ($authenticatedUserId != $review->user_id) {
            return $this->sendError('Unauthorized', 'You are not authorized to delete this review.', 403);
        }
        try {
            $deletedReview = $review->delete();
            return $this->sendResponse($deletedReview, 'Review deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
