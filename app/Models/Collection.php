<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';

    protected $fillable = [
        'userId',
        'bookId',
    ];

    public function books()
    {
        return $this->belongsTo(Book::class, 'bookId');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
