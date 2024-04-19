<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Admin | Library</title>
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
            <h4>Data Admin</h4>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAdmin">Add
                Data</button>
        </div>
        <hr style="margin-bottom: -0.01rem;">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th scope="col" style="width:3%">#</th>
                    <th scope="col" style="width:20%">Name</th>
                    <th scope="col" style="width:20%">Username</th>
                    <th scope="col" style="width:20%">Email</th>
                    <th scope="col" style="width:24%">Address</th>
                    <th scope="col" style="width:13%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                    <tr class="text-center">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->username }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->address }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#editAdmin-{{ $admin->id }}">Edit</button>
                                <form action="{{ route('admin.delete', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-dark">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editAdmin-{{ $admin->id }}" tabindex="-1"
                        aria-labelledby="editAdminLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editAdminLabel">Edit Data</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.edit', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body mx-1">
                                        <div class="mb-3">
                                            <label for="nameInput" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="nameInput" name="name"
                                                value="{{ $admin->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="addressInput" class="form-label">Address</label>
                                            <textarea class="form-control" id="addressInput" aria-describedby="addressHelp" name="address">{{ $admin->address }}</textarea>
                                            <div id="addressHelp" class="form-text">We'll never share your address
                                                with anyone else.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1"
                                                name="email" value="{{ $admin->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="usernameInput" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="usernameInput"
                                                name="username" value="{{ $admin->username }}">
                                        </div>
                                        <div>
                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                name="password">
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

    <div class="modal fade" id="addAdmin" tabindex="-1" aria-labelledby="addAdminLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addAdminLabel">Add Admin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.add') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body mx-1">
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nameInput" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="addressInput" class="form-label">Address</label>
                            <textarea class="form-control" id="addressInput" aria-describedby="addressHelp" name="address"></textarea>
                            <div id="addressHelp" class="form-text">We'll never share your address with anyone else.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="usernameInput" class="form-label">Username</label>
                            <input type="text" class="form-control" id="usernameInput" name="username">
                        </div>
                        <div>
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password">
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
