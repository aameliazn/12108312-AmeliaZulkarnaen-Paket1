<?php

namespace App\Models;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'userId',
        'bookId',
        'review',
        'rating',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function books()
    {
        return $this->belongsTo(Book::class, 'bookId');
    }
}
