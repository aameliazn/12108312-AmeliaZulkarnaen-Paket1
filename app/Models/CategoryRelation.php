<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRelation extends Model
{
    use HasFactory;

    protected $table = 'category_relations';

    protected $fillable = [
        'bookId',
        'categoryId',
    ];
}
