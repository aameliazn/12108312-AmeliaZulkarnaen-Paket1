<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\CategoryRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GlobalController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        return view("dashboard.dashboard", compact("auth"));
    }

    public function showAdmin()
    {
        $auth = Auth::user();
        $admins = User::where('role', 'admin')->get();
        return view("dashboard.admin", compact("auth", 'admins'));
    }

    public function showCategory()
    {
        $auth = Auth::user();
        $categories = Category::all();
        return view("dashboard.category", compact('auth', 'categories'));
    }

    public function showBook()
    {
        $auth = Auth::user();
        $books = Book::with('categories')->get();
        $categories = Category::all();
        return view("dashboard.book", compact("auth", 'categories', 'books'));
    }

    public function showCollection()
    {
        return view("dashboard.collection");
    }

    public function storeAdmin(Request $request)
    {
        Validator::make($request->all(), [
            'username' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'password' => 'required',
        ])->validate();

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'role' => 'admin',
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Register success');
    }

    public function storeCategory(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
        ])->validate();

        Category::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Category success');
    }

    public function storeBook(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'category' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'pub_year' => 'required',
        ])->validate();

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'pub_year' => $request->pub_year,
        ]);
        
        CategoryRelation::create([
            'bookId' => $book->id,
            'categoryId' => $request->category,
        ]);
        return back()->with('success', 'Book success');
    }

    public function updateAdmin(Request $request, $id)
    {
        $user = User::findorFail($id);

        $user->update($request->all());

        return back()->with('success', 'Update success');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findorFail($id);

        $category->update($request->all());

        return back()->with('success', 'Update success');
    }

    // public function updateBook(Request $request, $id)
    // {
    //     $book = Book::findorFail($id);

    //     $book->update($request->all());

    //     return back()->with('success', 'Update success');
    // }

    public function destroyAdmin($id)
    {
        $user = User::findorFail($id);

        $user->delete();

        return back()->with('success', 'Delete success');
    }

    public function destroyCategory($id)
    {
        $category = Category::findorFail($id);

        $category->delete();

        return back()->with('success', 'Delete success');
    }

    public function destroyBook($id)
    {
        $book = Book::findorFail($id);

        $book->delete();

        return back()->with('success', 'Delete success');
    }
}
