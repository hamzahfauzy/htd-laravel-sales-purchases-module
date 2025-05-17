<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
            crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/b767cc3895.js"
            crossorigin="anonymous"></script>

        <style>
            .like-card {
                position:absolute;
                top:0;
                right:0;
                margin: 6px;
                color: white;
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-danger text-white">
            <div class="container-fluid">
                <div class="d-flex" style="gap:24px">
                    <button class="navbar-toggler text-white border-white"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar"
                        aria-controls="offcanvasNavbar"
                        aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center" style="gap:8px">
                        <div
                            class="bg-white rounded-circle text-secondary d-flex justify-content-center align-items-center"
                            style="width: 40px; height: 40px;">
                            <i
                                class="fa-solid fa-user"></i>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="m-0">Novian Adrian</h6>
                            <span style="font-size: 12px;">Admin</span>
                        </div>
                    </div>
                    <form class="d-flex position-relative" role="search"
                        style="width: 500px;">
                        <input class="form-control me-2" type="search"
                            placeholder="Cari Produk" aria-label="Search" />
                    </form>
                    <div
                        class="bg-white rounded-circle text-secondary d-flex justify-content-center align-items-center"
                        style="width: 40px; height: 40px;">

                        <i class="fa-solid fa-list"></i>
                    </div>
                </div>
                <div
                    class="p-2 bg-white text-dark border-info border-top"
                    style="border-width: 3px !important;">12</div>
                <div class="offcanvas offcanvas-start" tabindex="-1"
                    id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title"
                            id="offcanvasNavbarLabel">Offcanvas</h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul
                            class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                    role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Dropdown
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="#">Action</a></li>
                                    <li><a class="dropdown-item"
                                            href="#">Another action</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="#">Something else
                                            here</a></li>
                                </ul>
                            </li>
                        </ul>
                        <form class="d-flex mt-3" role="search">
                            <input class="form-control me-2" type="search"
                                placeholder="Search" aria-label="Search" />
                            <button class="btn btn-outline-success"
                                type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">

            <div class="row my-3">
                <div class="col-md-8">
                    <div class="card card-body p-2 mb-3">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active bg-danger"
                                    href="#">Semua</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary"
                                    href="#">Makanan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary"
                                    href="#">Minuman</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <span
                                    class="rounded-circle bg-danger d-flex justify-content-center align-items-center like-card"
                                    style="width: 24px; height: 24px;">

                                    <i
                                        class="fa-solid fa-check"></i>
                                </span>
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <span
                                    class="rounded-circle bg-danger d-flex justify-content-center align-items-center like-card"
                                    style="width: 24px; height: 24px;">

                                    <i
                                        class="fa-solid fa-check"></i>
                                </span>
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <span
                                    class="rounded-circle bg-danger d-flex justify-content-center align-items-center like-card"
                                    style="width: 24px; height: 24px;">

                                    <i
                                        class="fa-solid fa-check"></i>
                                </span>
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <span
                                    class="rounded-circle bg-danger d-flex justify-content-center align-items-center like-card"
                                    style="width: 24px; height: 24px;">

                                    <i
                                        class="fa-solid fa-check"></i>
                                </span>
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card position-relative">
                                <img src="./food.jpeg" class="card-img-top"
                                    alt="food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Makanan sehat
                                        bergizi</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">

                    <div
                        class="d-flex justify-content-between align-items-center p-2 border-bottom mb-3">
                        <h5>Daftar Pesanan</h5>
                        <button class="btn btn-lg btn-danger">Hapus</button>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li
                            class="list-group-item">

                            <div class="row">
                                <div class="col-md-6">
                                    Makanan sehat bergizi
                                </div>

                                <div class="col-md-2 text-danger">
                                    x2
                                </div>

                                <div class="col-md-4">
                                    <span
                                        class="text-danger me-2">Rp.200.000,-</span>
                                    <a href class="text-secondary">

                                        <i class="fa-solid fa-x"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li
                            class="list-group-item">

                            <div class="row">
                                <div class="col-md-6">
                                    Makanan sehat bergizi
                                </div>

                                <div class="col-md-2 text-danger">
                                    x2
                                </div>

                                <div class="col-md-4">
                                    <span
                                        class="text-danger me-2">Rp.200.000,-</span>
                                    <a href class="text-secondary">

                                        <i class="fa-solid fa-x"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li
                            class="list-group-item">

                            <div class="row">
                                <div class="col-md-6">
                                    Makanan sehat bergizi
                                </div>

                                <div class="col-md-2 text-danger">
                                    x2
                                </div>

                                <div class="col-md-4">
                                    <span
                                        class="text-danger me-2">Rp.200.000,-</span>
                                    <a href class="text-secondary">

                                        <i class="fa-solid fa-x"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li
                            class="list-group-item text-center">
                            <h6 class="text-danger">+ Biaya
                                Tambahan</h6>
                        </li>

                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            <h5>Total</h5>
                            <h5 class="text-danger">Rp.300.000</h5>
                        </li>

                        <li class="list-group-item">
                            <button
                                class="btn btn-lg btn-danger w-100">Bayar</button>
                        </li>

                    </ul>

                </div>
            </div>

        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
            crossorigin="anonymous"></script>
    </body>
</html>