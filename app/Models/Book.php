<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Lend;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'pub_year',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_relations', 'bookId', 'categoryId');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'bookId');
    }
    
    public function lends()
    {
        return $this->hasMany(Lend::class, 'bookId');
    }
        
    public function reviews()
    {
        return $this->hasMany(Review::class, 'bookId');
    }
}
