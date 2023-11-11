<?php

namespace App\Http\Controllers\Api;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;

class RatingsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        try {
            $data = Rating::all();
            return $this->sendResponse($data, 'Rating List');
        } catch (Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $validator = $this->ratingValidation($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $errorCode = 422;
            return $this->sendError('Validation Error', $errorMessages, $errorCode);
        }

        try {
            $data = Rating::create($request->all());
            return $this->sendResponse($data, 'Rate the book successfully');
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
        //
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
    public function update(Request $request, Rating $rating)
    {
        $authenticatedUserId = Auth::id();
        if ($authenticatedUserId != $rating->user_id) {
            return $this->sendError('Unauthorized', 'You are not authorized to update this rating.', 403);
        }
        $validator = $this->ratingValidation($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $errorCode = 422;
            return $this->sendError('Validation Error', $errorMessages, $errorCode);
        }

        try {
            $rating->update($request->all());
            $updatedRating = $rating->fresh();
            return $this->sendResponse($updatedRating, 'Rating updated successfully');
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
    public function destroy(Rating $rating)
    {
        $authenticatedUserId = Auth::id();
        if ($authenticatedUserId != $rating->user_id) {
            return $this->sendError('Unauthorized', 'You are not authorized to delete this rating.', 403);
        }
        try {
            $deletedRating = $rating->delete();
            return $this->sendResponse($deletedRating, 'Rating deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
