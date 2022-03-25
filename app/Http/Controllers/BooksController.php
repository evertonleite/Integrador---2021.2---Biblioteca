<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Books;

class BooksController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function create(){
        $this->authorize('is_admin');
        return view('panel.addbook');
    }

    public function store(Request $request){
        $this->authorize('is_admin');
        $book = new Books;
        $book->titulo = $request->titulo;
        $book->autor = $request->autor;
        $book->descricao = $request->descricao;
		$book->categoria = $request->categoria;
        $book->status_book = 'disponivel';

        $book->save();
        return redirect()->route('allbook');
    }

    public function show($id){
        $this->authorize('is_admin');
        $books = Books::findOrFail($id);
       // return view('books.show', ['book' => $book]);
    }

    public function showAll(){
        $this->authorize('is_admin');
        $books = Books::all();
        //return view('books.showAll',['books' => $books]);
    }

    public function destroy($id){
        $this->authorize('is_admin');
        $books = Books::findOrFail($id);
        $books->delete();
        return redirect()->route('allbook');
        //return redirect('/show/books');
    }

    // public function edit($id){
    //     $this->authorize('is_admin');
    //     $books = Books::findOrFail($id);
    //     //return view('books.edit', ['book' => $book]);
    // }

    public function update(request $request, $id){
        $this->authorize('is_admin');
		$book = Books::findOrFail($id);
		$book->titulo=$request->input('titulo');
		$book->autor=$request->input('autor');
		$book->descricao=$request->input('descricao');
		$book->categoria=$request->input('categoria');
		$book->save();
        
		return redirect()->route('allbook');
	}

    public function editbook($id){
        $books = Books::findOrFail($id);
        return view('panel.editbook',compact('books'));
    }

    public function allbook(){
        $books = Books::all();
        return view('panel.allbook',['books'=>$books]);
    }

    public function delete_book($id){

        $books = Books::findOrFail($id);
        $books->delete();

        return redirect()->route('allbook');
    }

    public function index(){

        $search = request('search');

        $books = Books::where([
            ['titulo', 'like', '%'.$search.'%']
            ])->get();

        return view('panel.index', ['books' => $books, 'search' => $search]);
    }
}

