<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Collection | Library</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #d7e1f0;">
        <div class="d-flex flex-grow-1">
            <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
            <a class="navbar-brand d-none d-lg-inline-block" href="">
                Library
            </a>
            <a class="navbar-brand-two mx-auto d-lg-none d-inline-block" href="">
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
                    <a href="" class="nav-link m-2 menu-item nav-active ms-5">Dashboard</a>
                </li>

                {{-- untuk role master --}}
                    <li class="nav-item">
                        <a href="" class="nav-link m-2 menu-item">Admin</a>
                    </li>
                {{--  --}}

                {{-- untuk role bukan user --}}
                    <li class="nav-item">
                        <a href="" class="nav-link m-2 menu-item">Category</a>
                    </li>
                {{--  --}}

                <li class="nav-item">
                    <a href="" class="nav-link m-2 menu-item">Book</a>
                </li>

                {{-- untuk role user --}}
                    <li class="nav-item">
                        <a href="" class="nav-link m-2 menu-item">Collection</a>
                    </li>
                {{--  --}}
            </ul>
        </div>
        <div class="collapse navbar-collapse flex-grow-1 justify-content-end" id="myNavbar">
            <ul class="navbar-nav ms-auto flex-nowrap">
                <li class="nav-item">
                    <a href="" class="btn btn-outline-danger menu-item">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="m-5 text-center">
        <h4 class="mb-4">Data Collection</h4>
        <hr style="margin-bottom: -0.01rem;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" style="width:3%">#</th>
                    <th scope="col" style="width:17%">Title</th>
                    <th scope="col" style="width:16%">Category</th>
                    <th scope="col" style="width:16%">Author</th>
                    <th scope="col" style="width:16%">Publisher</th>
                    <th scope="col" style="width:10%">Publication Year</th>
                    <th scope="col" style="width:22%">Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- foreach books book --}}
                    {{-- code php book collections where userId auth id where bookId book id first() --}}
                        
                    {{--  --}}
                    {{-- if book collections contains userId auth id --}}
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            {{-- foreach book categories sebagai category --}}
                                <td>category name</td>
                            {{--  --}}
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- ambil id dari code php atas --}}
                                    <form action=""
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        {{-- ambil id dari code php atas -> id --}}
                                        <input type="hidden" name="id" value="">
                                        <button type="submit" class="btn btn-outline-success">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    {{--  --}}
                {{--  --}}
            </tbody>
        </table>
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
