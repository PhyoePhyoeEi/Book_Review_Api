<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['book_id', 'user_id', 'content'];

    public function books()
    {
        return $this->belongsTo(Book::class);
    }
}
