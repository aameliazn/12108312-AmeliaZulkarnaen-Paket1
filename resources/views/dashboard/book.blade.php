<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Book | Library</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #d7e1f0;">
        <div class="d-flex flex-grow-1">
            <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
            <a class="navbar-brand d-none d-lg-inline-block" href="{{ route('dashboard') }}">
                Library
            </a>
            <a class="navbar-brand-two mx-auto d-lg-none d-inline-block" href="{{ route('dashboard') }}">
                <img src="//via.placeholder.com/40?text=LIB." alt="logo">
            </a>
            <div class="w-100 text-right">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
        <div class="collapse navbar-collapse flex-grow-1 text-right ms-5" id="myNavbar">
            <ul class="navbar-nav ms-5 flex-nowrap">
                <li class="nav-item ms-5">
                    <a href="{{ route('dashboard') }}" class="nav-link m-2 menu-item nav-active ms-5">Dashboard</a>
                </li>

                @if ($auth->role === 'master')
                    <li class="nav-item">
                        <a href="{{ route('admin') }}" class="nav-link m-2 menu-item">Admin</a>
                    </li>
                @endif

                @if ($auth->role !== 'user')
                    <li class="nav-item">
                        <a href="{{ route('category') }}" class="nav-link m-2 menu-item">Category</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('book') }}" class="nav-link m-2 menu-item">Book</a>
                </li>

                @if ($auth->role === 'user')
                    <li class="nav-item">
                        <a href="{{ route('collection') }}" class="nav-link m-2 menu-item">Collection</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="collapse navbar-collapse flex-grow-1 justify-content-end" id="myNavbar">
            <ul class="navbar-nav ms-auto flex-nowrap">
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger menu-item">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="m-5">
        <div class="d-flex justify-content-between mb-4 text-center">
            <div></div>
            <h4>Data Book</h4>

            @if ($auth->role !== 'user')
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#addBook">Add Data</button>
            @else
                <div></div>
            @endif

        </div>
        <hr style="margin-bottom: -0.01rem;">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th scope="col" style="width:3%">#</th>
                    <th scope="col" style="width:18%">Title</th>
                    <th scope="col" style="width:18%">Category</th>
                    <th scope="col" style="width:18%">Author</th>
                    <th scope="col" style="width:18%">Publisher</th>
                    <th scope="col" style="width:13%">Publication Year</th>
                    <th scope="col" style="width:12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr class="text-center">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $book->title }}</td>

                        @foreach ($book->categories as $category)
                            <td>{{ $category->name }}</td>
                        @endforeach

                        <td>{{ $book->author }}</td>
                        <td>{{ $book->publisher }}</td>
                        <td>{{ $book->pub_year }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                @if ($auth->role !== 'user')
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#editBook-{{ $book->id }}">Edit</button>
                                    <form action="{{ route('book.delete', $book->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-dark">Delete</button>
                                    </form>
                                @else
                                    <form action="{{ route('lend.add') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="bookId" value="{{ $book->id }}">

                                        @if ($book->lends->contains('status', 'lend'))
                                            <button type="submit" class="btn btn-outline-primary"
                                                disabled>Borrowed</button>
                                        @else
                                            <button type="submit" class="btn btn-outline-primary">Borrow</button>
                                        @endif

                                    </form>
                                    <form action="{{ route('collection.add') }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="bookId" value="{{ $book->id }}">

                                        @if ($book->collections()->where('userId', $auth->id)->exists())
                                            <button type="submit" class="btn btn-outline-success"
                                                disabled>Collection</button>
                                        @else
                                            <button type="submit" class="btn btn-outline-success">Collection</button>
                                        @endif

                                    </form>
                                    <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                                        data-bs-target="#reviewBook-{{ $book->id }}">Review</button>
                                @endif

                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="reviewBook-{{ $book->id }}" tabindex="-1"
                        aria-labelledby="reviewBookLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 me-2" id="reviewBookLabel">Review {{ $book->title }}
                                    </h1>

                                    @if ($book->lends()->where('userId', $auth->id)->where('bookId', $book->id)->where('status', 'returned')->exists())
                                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#addReview-{{ $book->id }}">Add Review</button>
                                    @endif

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body mx-1">
                                    @foreach ($book->reviews as $review)
                                        <p class="fs-5">{{ $review->users->username }} - {{ $review->rating }}
                                            Bintang
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p>"{{ $review->review }}"</p>
                                            @if ($review->userId === $auth->id)
                                                <form action="{{ route('review.delete', $review->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id"
                                                        value="{{ $review->id }}">
                                                    <button type="submit" class="btn btn-outline-danger"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="addReview-{{ $book->id }}" tabindex="-1"
                        aria-labelledby="addReviewLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="addReviewLabel">Review {{ $book->title }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('review.add') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="modal-body mx-1">
                                        <input type="hidden" name="bookId" value="{{ $book->id }}">
                                        <div class="mb-3">
                                            <label for="reviewInput" class="form-label">Review</label>
                                            <textarea class="form-control" id="reviewInput" name="review"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rating"
                                                    id="flexRadioDefault1" value="5" checked>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Bintang 5
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rating"
                                                    id="flexRadioDefault2" value="4">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Bintang 4
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rating"
                                                    id="flexRadioDefault3" value="3">
                                                <label class="form-check-label" for="flexRadioDefault3">
                                                    Bintang 3
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rating"
                                                    id="flexRadioDefault4" value="2">
                                                <label class="form-check-label" for="flexRadioDefault4">
                                                    Bintang 2
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rating"
                                                    id="flexRadioDefault5" value="1">
                                                <label class="form-check-label" for="flexRadioDefault5">
                                                    Bintang 1
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-outline-dark">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editBook-{{ $book->id }}" tabindex="-1"
                        aria-labelledby="editBookLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editBookLabel">Edit Data</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('book.edit', $book->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body mx-1">
                                        <div class="mb-3">
                                            <label for="titleInput" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="titleInput"
                                                name="title" value="{{ $book->title }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="CategoryInput" class="form-label">Category</label>
                                            <select class="form-select" name="category"
                                                aria-label="Default select example">
                                                <option selected disabled>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="authorInput" class="form-label">Author</label>
                                            <input type="text" class="form-control" id="authorInput"
                                                name="author" value="{{ $book->author }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="publisherInput" class="form-label">Publisher</label>
                                            <input type="text" class="form-control" id="publisherInput"
                                                name="publisher" value="{{ $book->publisher }}">
                                        </div>
                                        <div>
                                            <label for="publicationInput" class="form-label">Publication Year</label>
                                            <input type="number" class="form-control" id="publicationInput"
                                                name="pub_year" value="{{ $book->pub_year }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addBook" tabindex="-1" aria-labelledby="addBookLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addBookLabel">Add Book</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('book.add') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body mx-1">
                        <div class="mb-3">
                            <label for="titleInput" class="form-label">Title</label>
                            <input type="text" class="form-control" id="titleInput" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="CategoryInput" class="form-label">Category</label>
                            <select class="form-select" name="category" aria-label="Default select example">
                                <option selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="authorInput" class="form-label">Author</label>
                            <input type="text" class="form-control" id="authorInput" name="author">
                        </div>
                        <div class="mb-3">
                            <label for="publisherInput" class="form-label">Publisher</label>
                            <input type="text" class="form-control" id="publisherInput" name="publisher">
                        </div>
                        <div>
                            <label for="publicationInput" class="form-label">Publication Year</label>
                            <input type="number" class="form-control" id="publicationInput" name="pub_year">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

</body>

</html>
