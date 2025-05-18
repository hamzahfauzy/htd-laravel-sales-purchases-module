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
                    style="border-width: 3px !important;" id="total-item"></div>
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
                    <input type="text" name="code" onchange="findProduct(this.value)" class="form-control form-lg" placeholder="Masukkan kode produk">

                    <div class="row mt-4">
                        <table class="table table-responsive">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Unit</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                            

                        </table>
                    </div>

                </div>
                <div class="col-md-4">

                    <div
                        class="d-flex justify-content-between align-items-center p-2 border-bottom mb-3">
                        <h5>Detail Pesanan</h5>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li
                            class="list-group-item">

                            <div class="row">
                                <div class="col-md-6">
                                    Diskon
                                </div>

                                <div class="col-md-6">
                                    <input type="number" name="discount" onchange="changeDiscount(this.value)" class="form-control" placeholder="Masukkan diskon" value="0">
                                </div>
                            </div>
                        </li>
                        <li
                            class="list-group-item">
                            <input type="number" name="payment_amount" onchange="changePaymentAmount(this.value)" class="form-control" placeholder="Masukkan Jumlah Bayar">
                        </li>
                        <li
                            class="list-group-item">
                            <select name="payment_method" class="form-control">

                                @foreach ($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                    
                                @endforeach
                            </select>
                        </li>

                        <li
                            class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total</h5>
                                <h5 class="text-danger" id="total">Rp 0</h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Kembalian</span>
                                <span id="change">Rp 0</span>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <button
                                class="btn btn-lg btn-danger w-100" onclick="bayar()">Bayar</button>
                        </li>

                    </ul>

                </div>
            </div>

        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
            crossorigin="anonymous"></script>

            <script>
                function findProduct(code) {
                    fetch('/pos?code=' + code)
                        .then(response => response.json())
                        .then(item => {
                            var items = document.querySelectorAll('tbody tr');

                            var found = document.querySelector(`#item-${item.code}`)
                            if(found) {
                                found.querySelector('input[name="qty"]').value = parseInt(found.querySelector('input[name="qty"]').value) + 1;
                                changeQty(found.querySelector('input[name="qty"]').value, item.code);
                            } else {
                                var html = `
                                        <tr id="item-${item.code}">
                                                <input type="hidden" name="id" value="${item.id}">
                                                <td>${items.length+1}</td>
                                                <td>
                                                    ${item.name}
                                                </td>
                                                <td>
                                                    <select name="unit" style="width:150px" class="form-control form-lg" onchange="changeUnit('${item.code}', '${item.price}')">
                                                        <option value="">Pilih Unit</option>
                                                        ${item.units.length == 0 ? '<option value="">Tidak ada unit</option>' : ''}
                                                         ${item.units.map(unit => `<option value="${unit.unit}" data-price="${unit.amount_1}" ${unit.unit == item.unit ? 'selected' : ''}>${unit.unit}</option>`)}
                                                    </td>
                                                <td>
                                                    <input type="number" name="qty" class="form-control qty form-lg" style="width:150px" placeholder="Masukkan jumlah" value="1" onchange="changeQty(this.value, '${item.code}')">
                                                </td>   
                                                <td id="price-${item.code}" class="prices" data-price="${item.price}">
                                                    ${formatNumber(item.price)}
                                                </td>
    
                                            </tr>
                                    `
                                    document.querySelector('tbody').innerHTML += html;
    
                                    changeUnit(item.code)
    
                                }
                                
                                document.querySelector('input[name="code"]').value = '';
                                document.querySelector('input[name="code"]').focus();
                        });
                }      
                
                function changeQty(qty, code) {
                    var selectUnit = document.querySelector(`#item-${code} [name="unit"]`)
                    var price = selectUnit.options[selectUnit.selectedIndex].getAttribute('data-price')
                    var subtotal = qty * price;
                    setSubTotal(code, subtotal);
                    var discount = document.querySelector('input[name="discount"]').value;
                    var subtotals = document.querySelectorAll('.prices');
                    var total = 0;
                    subtotals.forEach((sbtotal) => {
                        var price = sbtotal.getAttribute('data-price');
                        total += parseInt(price)
                    });
                    setTotal(total-discount);
                }

                function changeUnit(code) {
                    var qty = document.querySelector(`#item-${code} [name="qty"]`).value;
                    var selectUnit = document.querySelector(`#item-${code} [name="unit"]`)
                    var price = selectUnit.options[selectUnit.selectedIndex].getAttribute('data-price')
                    var subtotal = price * qty;
                    setSubTotal(code, subtotal);
                    var discount = document.querySelector('input[name="discount"]').value;
                    var subtotals = document.querySelectorAll('.prices');
                    var total = 0;
                    subtotals.forEach((sbtotal) => {
                        var price = sbtotal.getAttribute('data-price');
                        total += parseInt(price)
                    });
                    setTotal(total-discount);
                }

                function formatNumber(num) {
                    return  new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(num);
                }

                function changeDiscount(discount) {
                    var subtotals = document.querySelectorAll('.prices');
                    var total = 0;
                    subtotals.forEach((subtotal) => {
                        var price = subtotal.getAttribute('data-price');
                        total += parseInt(price)
                    });
                    setTotal(total-discount);
                }

                function changePaymentAmount(payment) {
                    var total = document.getElementById('total').getAttribute('data-price');
                    var change = payment - total;
                    document.getElementById('change').innerHTML = formatNumber(change);
                    document.getElementById('change').setAttribute('data-value', change);
                }

                function setTotal(total) {
                    document.getElementById('total').innerHTML = formatNumber(total);
                    document.getElementById('total').setAttribute('data-price', total);
                    changePaymentAmount(document.querySelector('input[name="payment_amount"]').value);
                }

                function setSubTotal(code, value) {
                    document.getElementById(`price-${code}`).innerHTML = formatNumber(value);
                    document.getElementById(`price-${code}`).setAttribute('data-price', value);
                }

                function bayar() {

                    var total_item = document.querySelectorAll('tbody tr');

                    var items = [];

                    total_item.forEach((item) => {
                        var id = item.querySelector('input[name="id"]').value;
                        var qty = item.querySelector('input[name="qty"]').value;
                        var unit = item.querySelector('select[name="unit"]').value;
                        var price = parseInt(item.querySelector('.prices').getAttribute('data-price'));
                        items.push({
                            product_id: id,
                            name: item.querySelector('td:nth-child(2)').innerHTML,
                            qty: parseInt(qty),
                            unit: unit,
                            base_price: price,
                            total_price: price * qty
                        });
                    });

                    var total_price = 0;
                    var subtotals = document.querySelectorAll('.prices');
                    subtotals.forEach((subtotal) => {
                        var price = subtotal.getAttribute('data-price');
                        total_price += parseInt(price)
                    });

                    var total_qty = 0;

                    var qtys = document.querySelectorAll('.qty');
                    qtys.forEach((qty) => {
                       total_qty += parseInt(qty.value);
                    });

                    var discount = parseInt(document.querySelector('input[name="discount"]').value);

                    var data = {
                        total_item: total_item.length,
                        total_price: total_price,
                        total_qty: total_qty,
                        final_price: total_price-discount,
                        discount: discount,
                        payment_amount: parseInt(document.querySelector('input[name="payment_amount"]').value),
                        payment_method: document.querySelector('select[name="payment_method"]').value,
                        change:  document.getElementById('change').getAttribute('data-value'),
                        items: items
                    }

                    fetch('/pos', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Berhasil melakukan transaksi');
                        window.location.reload();
                    })
                    .catch((error) => {
                        alert('Gagal melakukan transaksi');
                    });
                }
            </script>
    </body>
</html>