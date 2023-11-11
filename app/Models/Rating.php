<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = ['book_id', 'user_id', 'point'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

}
