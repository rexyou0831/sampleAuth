<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author()
    {
        return $this->hasManyThrough(
            Author::class,
            BookAuthor::class,
            'book_id',
            'id',
            'id',
            'author_id'
        );
    }

    public function book_author()
    {
        return $this->hasMany(BookAuthor::class, 'book_id', 'id');
    }

    public function gym()
    {
        return $this->hasMany(BookAuthor::class, 'book_id', 'id')->where('type', 'gym');
    }

    public function horror()
    {
        return $this->book_author()->where('type', 'horror');
    }

    
    public function fiction()
    {
        return $this->where('type', 'fiction');
    }

    
    public function society()
    {
        return $this->where('type', 'society');
    }

    public function history()
    {
        return $this->book_author()->where('type', 'history');
    }

}
