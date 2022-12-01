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

}
