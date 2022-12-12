<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\BooksResource;
use App\Http\Requests\StoreBookRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateBookRequest;

class BooksController extends Controller
{
    public function __construct(Book $book, User $user)
    {
        $this->book = $book;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BooksResource::collection(Book::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return new BooksResource($book);
        // return $book->author;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }

    public function typeCounter()
    {

        $category = Book::select('type', DB::raw("count(*) as total"))->groupBy('type')->pluck('total', 'type')->toArray();
        $total = array_sum($category);

        return response()->json([ 'total'=> $total, 'category'=> $category ]);

    }

    public function authorCounter()
    {

        $counter = DB::table("book_author")
                    ->join("books", "book_author.book_id", "=", "books.id", "inner")
                    ->select("books.type", DB::raw("count(book_author.book_id) as counter"))
                    ->groupBy("books.type")
                    ->paginate(10);

        // $data = BookAuthor::with('author', 'book')->get();

        // $counter = [];

        // foreach($data as $key => $value){
           
        //     if(!isset($counter[$value['book_id']])){
        //         $counter[$value['book_id']] = [
        //             'book_info' => $value['book'],
        //             'author_count' => 0,
        //             'author_info' => []
        //         ];
        //     }

        //     $counter[$value['book_id']]['author_count']++;
        //     $counter[$value['book_id']]['author_info'][] = $value['author'];

        // }

        return response()->json([ 'counter'=> $counter ]);
    }
    
    public function createRandomUser()
    {

        $response = $this->user->createRandomUser();

        return $response;
    }

    public function readRandomUser($filename)
    {

        $response = $this->user->readRandomUser($filename);

        return $response;
    }

}
