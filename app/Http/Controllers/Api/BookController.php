<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $book = Book::with(['category','authors'])->get();

        return ResponseHelper::success(' جميع الكتب', $book);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        //ربط المؤلفين
        $book->authors()->attach($request->authors);

        if ($request->hasFile('cover')){
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('book-images', $file ,$filename );
            $book->cover = $filename;
            $book->save();
        }
        return ResponseHelper::success("تمت إضافة الكتاب", $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return ResponseHelper::success('',$book->load(['Category','authors']));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        $book->authors()->when(
        $request->filled('authors'),
        fn ($q) => $q->sync($request->authors)
         );

        $request->whenHasFile('image', function () use ($book, $request) {
            optional($book->image, fn ($img) => Storage::delete($img));

        $book->update([
            'image' => $request->file('image')->store('books')
        ]);

        });

        return ResponseHelper::success("تمت تعديل الكتاب", $book);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->image && Storage::exists($book->image)) {
        Storage::delete($book->image);
        }

        $book->delete();

        return ResponseHelper::success("تمت حذف الكتاب", $book);
    }
}
