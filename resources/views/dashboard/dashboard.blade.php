<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Dashboard | Library</title>
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

    <div class="m-5 text-center">
        <h2>Hola {{ $auth->name }}!</h2>
        <p>{{ \Carbon\Carbon::now()->addHour(7)->isoFormat('dddd, D MMMM Y') }}</p>
    </div>

    @if ($auth->role !== 'user')
        <div class="m-5 d-flex justify-content-between gap-2">
            <div class="d-grid col-12">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#varExport">Export Lend
                    Book</button>
            </div>
        </div>
        <hr class="m-5">
        <div class="m-5">
            <h4 class="mb-4 text-center">Lend Data</h4>
            <hr style="margin-bottom: -0.01rem;">
            <table class="table table-hover">
                <thead class="text-center">
                    <tr>
                        <th scope="col" style="width:3%">#</th>
                        <th scope="col" style="width:15%">Name</th>
                        <th scope="col" style="width:15%">Email</th>
                        <th scope="col" style="width:15%">Title</th>
                        <th scope="col" style="width:15%">Author</th>
                        <th scope="col" style="width:10%">Lend Date</th>
                        <th scope="col" style="width:10%">Due Date</th>
                        <th scope="col" style="width:10%">Return Date</th>
                        <th scope="col" style="width:7%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lends as $lend)
                        @php
                            $textClass =
                                $lend->status === 'lend' && \Carbon\Carbon::parse($lend->due_date)->isPast()
                                    ? 'text-danger'
                                    : '';
                        @endphp
                        <tr class="text-center">
                            <th scope="row" class="{{ $textClass }}">{{ $loop->iteration }}</th>
                            <td class="{{ $textClass }}">{{ $lend->users->name }}</td>
                            <td class="{{ $textClass }}">{{ $lend->users->email }}</td>
                            <td class="{{ $textClass }}">{{ $lend->books->title }}</td>
                            <td class="{{ $textClass }}">{{ $lend->books->author }}</td>
                            <td class="{{ $textClass }}">{{ $lend->lend_date }}</td>
                            <td class="{{ $textClass }}">{{ $lend->due_date }}</td>
                            <td class="{{ $textClass }}">
                                @if ($lend->status === 'returned')
                                    {{ \Carbon\Carbon::parse($lend->updated_at)->format('Y-m-d') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="{{ $textClass }}">{{ $lend->status }}</td>
                        </tr>

                        <div class="modal fade" id="varExport" tabindex="-1" aria-labelledby="varExportLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="varExportLabel">Export Lend Data</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('export') }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="modal-body mx-1">
                                            <div class="mb-3">
                                                <label for="CategoryInput" class="form-label">User</label>
                                                <select class="form-select" name="userId"
                                                    aria-label="Default select example">
                                                    <option selected disabled>Select User</option>
                                                    <option value="all">All User</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="CategoryInput" class="form-label">Book</label>
                                                <select class="form-select" name="bookId"
                                                    aria-label="Default select example">
                                                    <option selected disabled>Select Book</option>
                                                    <option value="all">All Book</option>
                                                    @foreach ($books as $book)
                                                        <option value="{{ $book->id }}">{{ $book->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
    @else
        <hr class="m-5">
        <div class="m-5 text-center">
            <h4 class="mb-4">Data Borrowed</h4>
            <hr style="margin-bottom: -0.01rem;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width:3%">#</th>
                        <th scope="col" style="width:19%">Title</th>
                        <th scope="col" style="width:18%">Category</th>
                        <th scope="col" style="width:18%">Author</th>
                        <th scope="col" style="width:18%">Publisher</th>
                        <th scope="col" style="width:12%">Publication Year</th>
                        <th scope="col" style="width:12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        @php
                            $bookLends = $book->lends
                                ->where('userId', $auth->id)
                                ->where('bookId', $book->id)
                                ->where('status', 'lend')
                                ->first();
                        @endphp

                        @if ($book->lends->where('userId', $auth->id)->contains('status', 'lend'))
                            <tr>
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
                                        <form action="{{ route('lend.edit', $bookLends->id) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="id" value="{{ $bookLends->id }}">
                                            <button type="submit" class="btn btn-outline-primary">Return</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

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
