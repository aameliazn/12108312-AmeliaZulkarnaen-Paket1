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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GlobalController extends Controller
{
    public function index(Request $request)
    {
        $auth = Auth::user();
        $books = Book::with('lends', 'categories')->get();
        $lends = Lend::with('books', 'users')->get();
        $users = User::all();
        $categories = Category::all();

        $search = $request->input('search');
        $results = Lend::join('books', 'lends.bookId', '=', 'books.id')
            ->join('users', 'lends.userId', '=', 'users.id')
            ->where('books.title', 'like', "%$search%")
            ->orWhere('books.author', 'like', "%$search%")
            ->orWhere('users.name', 'like', "%$search%")
            ->orWhere('users.email', 'like', "%$search%")
            ->orWhere('lends.status', 'like', "%$search%")
            ->orWhere('lends.due_date', 'like', "%$search%")
            ->orWhere('lends.lend_date', 'like', "%$search%")
            ->orWhere('lends.updated_at', 'like', "%$search%")
            ->get();

        $search2 = $request->input('search2');
        $results2 = Lend::where('userId', $auth->id)
            ->join('books as b', 'lends.bookId', '=', 'b.id')
            ->leftJoin('category_relations as cr', 'cr.bookId', '=', 'b.id')
            ->leftJoin('categories as c', 'c.id', '=', 'cr.categoryId')
            ->where('b.title', 'like', "%$search2%")
            ->orWhere('c.name', 'like', "%$search2%")
            ->orWhere('b.author', 'like', "%$search2%")
            ->orWhere('b.publisher', 'like', "%$search2%")
            ->orWhere('b.pub_year', 'like', "%$search2%")
            ->get();

        return view("dashboard.dashboard", compact("auth", 'books', 'lends', 'users', 'categories', 'results', 'results2'));
    }

    public function showAdmin(Request $request)
    {
        $auth = Auth::user();
        $admins = User::where('role', 'admin')->get();

        $search = $request->input('search');
        $results = User::where('role', 'admin')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            })
            ->get();

        return view("dashboard.admin", compact("auth", 'admins', 'results'));
    }

    public function showCategory(Request $request)
    {
        $auth = Auth::user();
        $categories = Category::all();

        $search = $request->input('search');
        $results = Category::where('name', 'like', "%$search%")->get();

        return view("dashboard.category", compact('auth', 'categories', 'results'));
    }

    public function showBook(Request $request)
    {
        $auth = Auth::user();
        $authId = Auth::id();
        $categories = Category::all();
        $books = Book::with('categories', 'reviews', 'lends', 'collections')->get();
        $reviews = Review::with('users')->get();

        $search = $request->input('search');
        $results = Book::leftJoin('category_relations as cr', 'cr.bookId', '=', 'books.id')
            ->leftJoin('categories as c', 'c.id', '=', 'cr.categoryId')
            ->leftJoin('reviews as r', function ($join) {
                $join->on('r.bookId', '=', 'books.id')
                    ->whereRaw('r.id = (select id from reviews where bookId = books.id order by id desc limit 1)');
            })
            ->leftJoin('lends as l', function ($join) {
                $join->on('l.bookId', '=', 'books.id')
                    ->whereRaw('l.id = (select max(id) from lends where bookId = books.id)');
            })
            ->leftJoin('collections as col', function ($join) use ($authId) {
                $join->on('col.bookId', '=', 'books.id')
                    ->where('col.userId', '=', $authId);
            })
            ->leftJoin('users as u', 'u.id', '=', 'r.userId')
            ->select(
                'books.*',
                'c.id as categoriesId',
                'c.name as categoriesName',
                'r.id as reviewsId',
                'r.userId as reviewsUserId',
                'u.name as reviewsUserName',
                'r.bookId as reviewsBookId',
                'r.review as reviewsReview',
                'r.rating as reviewsRating',
                'l.id as lendsId',
                'l.userId as lendsUserId',
                'l.bookId as lendsBookId',
                'l.status as lendsStatus',
                'l.lend_date as lendsLendDate',
                'l.due_date as lendsDueDate',
                'l.updated_at as lendsReturnDate',
                'col.id as collectionsId',
                'col.userId as collectionsUserId',
                'col.bookId as collectionsBookId',
            )
            ->where(function ($query) use ($search) {
                $query->where('books.title', 'like', "%$search%")
                    ->orWhere('books.author', 'like', "%$search%")
                    ->orWhere('books.publisher', 'like', "%$search%")
                    ->orWhere('books.pub_year', 'like', "%$search%")
                    ->orWhere('c.name', 'like', "%$search%");
            })
            ->get();

        return view("dashboard.book", compact("auth", 'categories', 'books', 'results', 'reviews'));
    }

    public function showCollection(Request $request)
    {
        $auth = Auth::user();
        $books = Book::with('collections', 'categories')->get();

        $search = $request->input('search');
        $results = Collection::where('userId', $auth->id)
            ->leftJoin('books as b', 'b.id', '=', 'collections.bookId')
            ->leftJoin('category_relations as cr', 'cr.bookId', '=', 'b.id')
            ->leftJoin('categories as c', 'c.id', '=', 'cr.categoryId')
            ->where(function ($query) use ($search) {
                $query->where('b.title', 'like', "%$search%")
                    ->orWhere('c.name', 'like', "%$search%")
                    ->orWhere('b.author', 'like', "%$search%")
                    ->orWhere('b.publisher', 'like', "%$search%")
                    ->orWhere('b.pub_year', 'like', "%$search%");
            })
            ->get();

        return view("dashboard.collection", compact('auth', 'books', 'results'));
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
            'stock' => 'required',
            'category' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'pub_year' => 'required',
            'cover' => 'required|image',
        ])->validate();

        $file = $request->file('cover');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '-' . rand(0, 99) . '.' . $extension;
        $uploadPath = 'uploads/cover/';
        $path = $uploadPath . $filename;

        $file->move($uploadPath, $filename);

        $book = Book::create([
            'title' => $request->title,
            'stock' => $request->stock,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'pub_year' => $request->pub_year,
            'path_cover' => $path,
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

        $book = Book::findOrFail($request->bookId);
        $book->stock -= 1;
        $book->save();

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

        if (File::exists($book->path_cover)) {
            File::delete($book->path_cover);
        }

        $file = $request->file('cover');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '-' . rand(0, 99) . '.' . $extension;
        $uploadPath = 'uploads/cover/';
        $path = $uploadPath . $filename;

        $file->move($uploadPath, $filename);

        $book->update([
            'title' => $request->title,
            'stock' => $request->stock,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'pub_year' => $request->pub_year,
            'path_cover' => $path,
        ]);

        CategoryRelation::where('bookId', $id)->update([
            'categoryId' => $request->category,
        ]);

        return back()->with('success', 'Update success');
    }

    public function updateLend(Request $request, $id)
    {
        $lend = Lend::where('bookId', $id)->where('userId', Auth::id())->where('status', 'lend');

        $lend->update([
            'status' => 'returned',
        ]);

        $book = Book::findOrFail($id);
        $book->stock += 1;
        $book->save();

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

        if (File::exists($book->path_cover)) {
            File::delete($book->path_cover);
        }

        $book->delete();

        return back()->with('success', 'Delete success');
    }

    public function destroyCollection($id)
    {
        $collection = Collection::where('bookId', $id)->where('userId', Auth::id());

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
        if ($request->userId !== 'all' && $request->bookId !== 'all') {
            $data['lends'] = Lend::where('bookId', $request->bookId)->where('userId', $request->userId)->get();
        } elseif ($request->bookId !== 'all' && $request->userId === 'all') {
            $data['lends'] = Lend::where('bookId', $request->bookId)->get();
        } elseif ($request->userId !== 'all' && $request->bookId === 'all') {
            $data['lends'] = Lend::where('userId', $request->userId)->get();
        } else {
            $data['lends'] = Lend::all();
        }

        date_default_timezone_set('Asia/Jakarta');
        $date = date('dmHis');

        $export = Pdf::loadView('export.lend', $data);
        return $export->download('Book_Lend_Report_' . $date . '.pdf');
    }
}
