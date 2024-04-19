<?php

namespace App\Models;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lend extends Model
{
    use HasFactory;

    protected $table = 'lends';

    protected $fillable = [
        'userId',
        'bookId',
        'lend_date',
        'due_date',
        'status',
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
