<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;

class BooksController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        try {
            $data = Book::all();
            return $this->sendResponse($data, 'Books List');
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

        $validator = $this->bookValidation($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $errorCode = 422;
            return $this->sendError('Validation Error', $errorMessages, $errorCode);
        }

        try {
            $data = Book::create($request->all());
            return $this->sendResponse($data, 'Book stored successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Book::with(['reviews', 'ratings'])
            ->withAvg('ratings', 'point')
            ->find($id);

        if (!$data) {
            return $this->sendError('Error', 'Book not found', 404);
        }
        $data['avg_rating'] = round($data->ratings_avg_point, 1);

        return $this->sendResponse($data, 'Book detail with reviews and avg rating');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $authenticatedUserId = Auth::id();
        if ($authenticatedUserId != $book->user_id) {
            return $this->sendError('Unauthorized', 'You are not authorized to update this book.', 403);
        }

        $validator = $this->bookValidation($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $errorCode = 422;
            return $this->sendError('Validation Error', $errorMessages, $errorCode);
        }

        try {
            $book->update($request->all());
            $updatedBook = $book->fresh();
            return $this->sendResponse($updatedBook, 'Book updated successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $authenticatedUserId = Auth::id();
        if ($authenticatedUserId != $book->user_id) {
            return $this->sendError('Unauthorized', 'You are not authorized to delete this book.', 403);
        }

        try {
            $deletedBook = $book->delete();
            return $this->sendResponse($deletedBook, 'Book deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
