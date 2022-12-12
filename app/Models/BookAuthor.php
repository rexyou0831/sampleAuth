<?php

namespace App\Models;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookAuthor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'book_author';

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function gym()
    {
        return $this->book()->where('type', 'gym');
    }

    public function horror()
    {
        return $this->book()->where('type', 'horror');
    }

    
    public function fiction()
    {
        return $this->book()->where('type', 'fiction');
    }

    
    public function society()
    {
        return $this->book()->where('type', 'society');
    }

    public function history()
    {
        return $this->book()->where('type', 'history')->groupBy('type');
    }

}
