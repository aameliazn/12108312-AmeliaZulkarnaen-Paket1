<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\CategoryRelation;
use App\Models\Collection;
use App\Models\Lend;
use App\Models\Review;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GlobalController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $books = Book::with('lends', 'categories')->get();
        $lends = Lend::with('books', 'users')->get();
        $users = User::all();
        $categories = Category::all();
        return view("dashboard.dashboard", compact("auth", 'books', 'lends', 'users', 'categories'));
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
        $categories = Category::all();
        $books = Book::with('categories', 'reviews', 'lends', 'collections')->get();
        return view("dashboard.book", compact("auth", 'categories', 'books'));
    }

    public function showCollection()
    {
        $auth = Auth::user();
        $books = Book::with('collections', 'categories')->get();
        return view("dashboard.collection", compact('auth', 'books'));
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

    public function storeLend(Request $request)
    {
        $lendDate = Carbon::now()->toDateString();
        $dueDate = Carbon::now()->addDays(7)->toDateString();

        Validator::make($request->all(), [
            'bookId' => 'required',
        ])->validate();

        Lend::create([
            'userId' => Auth::id(),
            'bookId' => $request->bookId,
            'lend_date' => $lendDate,
            'due_date' => $dueDate,
            'status' => 'lend',
        ]);

        return back()->with('success', 'Lend success');
    }

    public function storeCollection(Request $request)
    {
        Validator::make($request->all(), [
            'bookId' => 'required',
        ])->validate();

        Collection::create([
            'userId' => Auth::id(),
            'bookId' => $request->bookId,
        ]);

        return back()->with('success', 'Collection success');
    }

    public function storeReview(Request $request)
    {
        Validator::make($request->all(), [
            'bookId' => 'required',
            'review' => 'required',
            'rating' => 'required',
        ])->validate();

        Review::create([
            'userId' => Auth::id(),
            'bookId' => $request->bookId,
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Collection success');
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

    public function updateBook(Request $request, $id)
    {
        $book = Book::findorFail($id);

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'pub_year' => $request->pub_year,
        ]);

        CategoryRelation::where('bookId', $id)->update([
            'categoryId' => $request->category,
        ]);

        return back()->with('success', 'Update success');
    }

    public function updateLend(Request $request, $id)
    {
        $lend = Lend::findorFail($id);

        $lend->update([
            'status' => 'returned',
        ]);

        return back()->with('success', 'Update success');
    }

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

    public function destroyCollection($id)
    {
        $collection = Collection::findorFail($id);

        $collection->delete();

        return back()->with('success', 'Delete success');
    }

    public function destroyReview($id)
    {
        $review = Review::findorFail($id);

        $review->delete();

        return back()->with('success', 'Delete success');
    }

    public function export(Request $request)
    {
        if ($request->userId !== 'all') {
            $data['lends'] = Lend::where('userId', $request->userId)->get();
        } elseif ($request->bookId !== 'all') {
            $data['lends'] = Lend::where('bookId', $request->bookId)->get();
        } elseif ($request->userId !== 'all' && $request->bookId !== 'all') {
            $data['lends'] = Lend::where('bookId', $request->bookId)->where('userId', $request->userId)->get();
        } else {
            $data['lends'] = Lend::all();
        }

        date_default_timezone_set('Asia/Jakarta');
        $date = date('dmHis');

        $export = Pdf::loadView('export.lend', $data);
        return $export->download('Book_Lend_Report_' . $date . '.pdf');
    }
}
