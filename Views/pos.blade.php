<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{{csrf_token()}}">
        <title>Kasir</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
            crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/b767cc3895.js"
            crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap-5-theme.css') }}" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

        <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap5.css')}}">
        <link rel="stylesheet" href="{{asset('css/dataTables.css')}}">

        <style>
            .like-card {
                position:absolute;
                top:0;
                right:0;
                margin: 6px;
                color: white;
            }

            .cart-item.selected td {
                background-color:#c2c2fd;
            }
            .product-list-header th {
                background-color:rgba(var(--bs-danger-rgb), 1) !important;
                color: #FFF;
            }
            .select2, .general-select2, .select-unit {
                width: 100%!important;;
            }
            .payment_input:focus {
                box-shadow: none !important;
            }
            .label-discount {
                margin-bottom: -30px;
                display: block;
                margin-top: 10px;
                padding-left: 10px;
            }

            .selected {
                background-color: #D9EDF7 !important;
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-danger text-white sticky-top">
            <div class="container-fluid">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="d-flex align-items-center" style="gap:8px">
                        <img src="{{asset('modules/salespurchases/img/default-logo-transparent.png')}}" alt="" height="80px">
                    </div>
                    <div class="justify-content-between position-relative d-none d-lg-flex" role="search" style="max-width: 450px;width:100%;">
                        <select name="record_type" class="form-control form-select general-select2 record_type_select">
                            <option value="SALES">Mode Transaksi : Penjualan</option>
                            <option value="PURCHASES">Mode Transaksi : Pembelian</option>
                        </select>
                    </div>
                    <div class="d-flex">
                        <div class="text-end me-4 d-none d-md-block">
                            <h3 class="m-0 text-end d-block" id="clock-active">--:--:--</h3>
                            <span>{{date('l, d-m-Y')}}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-column justify-content-center text-end me-2 d-none d-md-flex">
                                <h6 class="m-0">{{auth()->user()->name}}</h6>
                                <span style="font-size: 12px;">{!! auth()->user()->userRoleLabel !!}</span>
                            </div>
                            <a href="{{route('home')}}">
                                <div class="bg-white rounded-circle text-secondary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-12 d-flex d-lg-none mb-3">
                    <input class="form-control" name="code" type="search" placeholder="Masukkan kode produk" aria-label="Search" onchange="findProduct(this.value)" />
                    <button class="btn btn-sm btn-info ms-2" data-bs-toggle="modal" data-bs-target="#productModal"><i class="fas fa-folder-open"></i></button>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="table-responsive">
                        <table class="table table-bordered item-table">
                            <thead>
                                <tr class="product-list-header">
                                    <th class="text-center" width="25px">No</th>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center" width="200px">Harga</th>
                                    <th class="text-center" width="70px">Satuan</th>
                                    <th class="text-center" width="70px">Jumlah</th>
                                    <th width="200px" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                <tr><td colspan="6" class="text-center"><i>Tidak ada data</i></td></tr>
                            </tbody>

                            <tfoot>
                                <tr><td colspan="6" class="text-end"><h2 class="total-items">Rp. 0</h2></td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="m-0">PINTASAN</h4>
                        </div>
                        <div class="card-body">
                            <code>
                                [Atas/Bawah] > Memilih item<br>               
                                [Kiri] > Buka Produk          <br>
                                [End] > Bayar      <br>
                                [F2] > Tambah Produk      <br>
                                [F3] > Edit Produk      <br>
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="paymentModalLabel">Pembayaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 p-0 mb-2">
                                <label style="position: absolute;z-index: 3;right: 14px;top: 5px;"><i class="fa-solid fa-user"></i></label>
                                <select name="customer" class="form-control form-select customer-select">
                                    <option value="">Walkin Guest</option>
                                </select>
                            </li>
                            <li class="list-group-item border-0 p-0 mb-2">
                                <select name="payment_method" class="form-control form-select general-select2 payment_method_select">
                                    @foreach ($paymentMethods as $key => $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" {{$key == 0 ? 'selected' : ''}}>Metode Pembayaran : {{ $paymentMethod->name }}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="list-group-item border-0 p-0 mb-2">
                                <label for="" class="label-discount">Diskon</label>
                                <input type="tel" name="discount" onkeyup="reloadTable()" class="form-control text-end autonumeric" placeholder="Masukkan diskon" value="0">
                            </li>
                            <li class="list-group-item border-0 p-0 mb-2">
                                <input type="tel" name="payment_amount" onkeyup="changePaymentAmount(this.value)" class="form-control text-end border-0 payment_input autonumeric" placeholder="Jumlah Bayar" style="font-size: 32px">
                            </li>
                            <li class="list-group-item border-0 p-0 mb-2">
                                <input type="text" name="reference" class="form-control text-end" placeholder="Catatan" id="reference">
                            </li>
                            <li
                                class="list-group-item border-0 p-0 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2>Total</h2>
                                    <h2 class="text-danger" id="total">Rp 0</h2>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Kembalian</span>
                                    <span id="change">Rp 0</span>
                                </div>
                            </li>

                            <li class="list-group-item border-0 p-0 mb-2">
                                <button class="btn btn-lg btn-danger w-100" onclick="bayar()">Bayar</button>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="productModalLabel">Daftar Produk | [F3 : Edit Produk Terpilih]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered product-lists table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Satuan</th>
                                        <th>Harga 1</th>
                                        <th>Harga 2</th>
                                        <th>Harga 3</th>
                                        <th>Harga 4</th>
                                        <th>Harga 5</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-product-close" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" type="button" onclick="addItem()">Submit</button>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="voidModal" tabindex="-1" aria-labelledby="voidModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="voidModalLabel">Void Invoice</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Invoice Code</label>
                            <input type="text" name="void_invoice_code" id="void_invoice_code" class="form-control" placeholder="Masukkan kode invoice">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" type="button" onclick="confirmVoid()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProductModalLabel">Tambah Produk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label for="">Kode</label>
                                    <input type="text" name="product_code" id="product_code" class="form-control" placeholder="Kode">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Nama</label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Nama">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Satuan</label>
                                    <input type="text" name="product_unit" id="product_unit" class="form-control" placeholder="Satuan" value="PCS">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 1</label>
                                    <input type="number" name="product_price_1" id="product_price_1" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 1</label>
                                    <input type="number" name="product_qty_1" id="product_qty_1" class="form-control" placeholder="Jumlah" value="1">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 2</label>
                                    <input type="number" name="product_price_2" id="product_price_2" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 2</label>
                                    <input type="number" name="product_qty_2" id="product_qty_2" class="form-control" placeholder="Jumlah">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 3</label>
                                    <input type="number" name="product_price_3" id="product_price_3" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 3</label>
                                    <input type="number" name="product_qty_3" id="product_qty_3" class="form-control" placeholder="Jumlah">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 4</label>
                                    <input type="number" name="product_price_4" id="product_price_4" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 4</label>
                                    <input type="number" name="product_qty_4" id="product_qty_4" class="form-control" placeholder="Jumlah">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Stok Masuk</label>
                                    <input type="number" name="product_stock" id="product_stock" class="form-control" placeholder="Stok" onkeyup="calculatePurchasePrice()" onchange="calculatePurchasePrice()">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Total</label>
                                    <input type="number" name="product_stock_price" id="product_stock_price" class="form-control" placeholder="Harga Total" onkeyup="calculatePurchasePrice()" onchange="calculatePurchasePrice()">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Modal</label>
                                    <input type="number" name="product_purchase_price" id="product_purchase_price" class="form-control" placeholder="Harga Modal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" type="button" onclick="saveAndAddProductToList()">Tambahkan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editProductModalLabel">Edit Produk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label for="">Kode</label>
                                    <input type="text" name="edit_product_code" id="edit_product_code" class="form-control" placeholder="Kode">
                                    <input type="hidden" name="edit_product_id" id="edit_product_id">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Nama</label>
                                    <input type="text" name="edit_product_name" id="edit_product_name" class="form-control" placeholder="Nama">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Satuan</label>
                                    <input type="text" name="edit_product_unit" id="edit_product_unit" class="form-control" placeholder="Satuan" value="PCS">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 1</label>
                                    <input type="number" name="edit_product_price_1" id="edit_product_price_1" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 1</label>
                                    <input type="number" name="edit_product_qty_1" id="edit_product_qty_1" class="form-control" placeholder="Jumlah" value="1">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 2</label>
                                    <input type="number" name="edit_product_price_2" id="edit_product_price_2" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 2</label>
                                    <input type="number" name="edit_product_qty_2" id="edit_product_qty_2" class="form-control" placeholder="Jumlah">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 3</label>
                                    <input type="number" name="edit_product_price_3" id="edit_product_price_3" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 3</label>
                                    <input type="number" name="edit_product_qty_3" id="edit_product_qty_3" class="form-control" placeholder="Jumlah">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Jual 4</label>
                                    <input type="number" name="edit_product_price_4" id="edit_product_price_4" class="form-control" placeholder="Harga">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Minimal Jumlah 4</label>
                                    <input type="number" name="edit_product_qty_4" id="edit_product_qty_4" class="form-control" placeholder="Jumlah">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Stok Masuk</label>
                                    <input type="number" name="edit_product_stock" id="edit_product_stock" class="form-control" placeholder="Stok" onkeyup="calculatePurchasePriceEdit()" onchange="calculatePurchasePriceEdit()">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Total</label>
                                    <input type="number" name="edit_product_stock_price" id="edit_product_stock_price" class="form-control" placeholder="Harga Total" onkeyup="calculatePurchasePriceEdit()" onchange="calculatePurchasePriceEdit()">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Harga Modal</label>
                                    <input type="number" name="edit_product_purchase_price" id="edit_product_purchase_price" class="form-control" placeholder="Harga Modal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" type="button" onclick="updateProduct()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="returnModalLabel">Return Invoice</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Invoice Code</label>
                            <input type="text" name="return_invoice_code" id="return_invoice_code" class="form-control" placeholder="Masukkan kode invoice">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" type="button" onclick="initReturn()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
            crossorigin="anonymous"></script>


            <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
            <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
            <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
            <script src="{{asset('assets/js/select2.js')}}"></script>
            <script src="{{asset('modules/salespurchases/js/autoNumeric.min.js')}}"></script>

            <script src="{{asset('js/dataTables.js')}}"></script>
            <script src="{{asset('js/dataTables.bootstrap5.js')}}"></script>

            <script>
                const select2Params = {
                    theme: 'bootstrap-5',
                    placeholder: 'Find Product',
                    dropdownParent: $("#productModal"),
                    ajax: {
                        url: '/sales-purchases/products', // ganti dengan URL kamu
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.code,
                                text: item.completeName,
                                prices: item.prices
                            }))
                        };
                        },
                        cache: true
                    }
                }
                $('.select2').select2(select2Params);

                $('.general-select2').select2({
                    theme: 'bootstrap-5',
                });
                
                $('.select-unit').select2({
                    theme: 'bootstrap-5',
                });

                let selectedProductIndex = -1; // tidak ada yang dipilih awalnya

                let productLists = $('.product-lists').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/sales-purchases/products/datatable',
                    aLengthMenu: [
                        [25, 50, 100, 200],
                        [25, 50, 100, 200]
                    ],
                    columnDefs: [
                        {
                            targets: 0, // index kolom No
                            width: '1%',
                            className: 'dt-nowrap'
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        // Misal data.code adalah kode produk dari response JSON
                        $(row).attr('data-code', data[8]);
                    }
                });

                const customerSelect = {
                    theme: 'bootstrap-5',
                    placeholder: 'Cari Kustomer',
                    ajax: {
                        url: '/sales-purchases/customers', // ganti dengan URL kamu
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.name,
                            }))
                        };
                        },
                        cache: true
                    }
                }

                $('.customer-select').select2(customerSelect);
                
                var selectedItems = []
                var selectedRow = 1
                var invoice_code = ''

                function highlightRow(rows) {
                    $(rows).removeClass('selected'); // hapus class dari semua
                    if (selectedProductIndex >= 0 && rows[selectedProductIndex]) {
                        $(rows[selectedProductIndex]).addClass('selected');
                        $(rows[selectedProductIndex])[0].scrollIntoView({ block: 'nearest' });
                    }
                }

                function setAutoNumeric(el){
                    el.forEach(elm => {
                        new AutoNumeric(elm, {
                            digitGroupSeparator: ',',
                            decimalCharacter: '.',
                            decimalPlaces: 0,
                            unformatOnSubmit: true,
                        })
                    })
                }

                function findProduct(code) {
                    if(invoice_code) return
                    fetch('/pos?code=' + code)
                        .then(response => response.json())
                        .then(item => {
                            if(selectedItems.length == 0)
                            {
                                document.querySelector('.item-table tbody').innerHTML = ''
                            }

                            selectedItems.push(item)
                            var items = document.querySelectorAll('.cart-item');

                            var found = document.querySelector(`#item-${item.code}`)
                            if(found) {
                                found.querySelector('input[name="qty"]').value = parseInt(found.querySelector('input[name="qty"]').value) + 1;
                                changeQty(found.querySelector('input[name="qty"]').value, item.code);
                            } else {
                                var html = `
                                        <tr id="item-${item.code}" class="cart-item" data-code="${item.code}" onclick="setActive(this)" style="cursor:pointer;">
                                            <td width="25px">${items.length+1} <input type="hidden" name="id" value="${item.id}"></td>
                                            <td>
                                                <span class="item-name">${item.name}</span>
                                            </td>
                                            <td>
                                                <span class="base_price base_price_${item.code}">${formatNumber(item.price)}</span>
                                            </td>   
                                            <td width="130px" style="position:relative;">
                                                <select name="unit" class="form-control form-lg select-unit" onchange="changeUnit('${item.code}', '${item.price}')">
                                                    ${item.units.length == 0 ? '<option value="">Tidak ada unit</option>' : ''}
                                                    ${item.units.map(unit => `<option value="${unit.unit}" data-price="${unit.amount_1}" ${unit.unit == item.unit ? 'selected' : ''}>${unit.unit}</option>`)}
                                                </select>
                                            </td>
                                            <td width="70px">
                                                <input type="number" name="qty" class="form-control qty form-lg" style="width:70px" placeholder="Masukkan jumlah" value="1" onkeyup="changeQty(this.value, '${item.code}')">
                                            </td>
                                            <td class="text-end">
                                                ${document.querySelector('.record_type_select').value == 'PURCHASES' ? `<input type="number" name="subtotal_value" class="form-control prices" id="price-${item.code}" data-code="${item.code}" value="${item.price}" data-baseprice="${item.price}" onchange="changePrice(this)" onkeyup="changePrice(this)">` : `<span id="price-${item.code}" class="prices" data-price="${item.price}" data-baseprice="${item.price}">${formatNumber(item.price)}</span>`}
                                            </td>

                                        </tr>
                                    `

                                    $('.item-table tbody').append(html)
                                    $('#item-'+item.code).find('.select-unit').select2({
                                        theme: 'bootstrap-5',
                                    });
                                    // document.querySelector('tbody').innerHTML += html;
    
                                    changeUnit(item.code)
    
                                }
                                
                                document.querySelector('input[name="code"]').value = '';
                                updateSelection(1)
                                // document.querySelector('input[name="code"]').focus();
                        })
                        .catch(err => {
                            console.log(err)
                            if(confirm('Produk tidak ditemukan. Buat Produk baru ?')){
                                addProduct(code)
                            }
                        });
                }      

                function addProduct(code = '')
                {
                    $('#addProductModal').modal('show')
                    $('#product_code').val(code)
                    setTimeout(function(){
                        $('#product_name').focus()
                    }, 500)
                }

                function reloadTable()
                {
                    var discount = document.querySelector('input[name="discount"]').value.replaceAll(',','');
                    var subtotals = document.querySelectorAll('.prices');
                    var total = 0;
                    const recordType = document.querySelector('.record_type_select').value
                    subtotals.forEach((sbtotal) => {
                        var price = recordType == 'PURCHASES' ? sbtotal.value : sbtotal.getAttribute('data-price');
                        total += parseInt(price)
                    });
                    setTotal(total-discount);

                    if(selectedItems.length == 0)
                    {
                        document.querySelector('tbody').innerHTML = '<td colspan="6" class="text-center"><i>Tidak ada data</i></td>'
                    }
                }
                
                function changeQty(qty, code) {
                    qty = parseFloat(qty)
                    if(qty == 0 && qty != '')
                    {
                        document.querySelector(`#item-${code}`).remove()
                        const index = selectedItems.findIndex(itm => itm.code == code)
                        selectedItems.splice(index, 1)
                        reloadTable()
                        return
                    }

                    if(document.querySelector('.record_type_select').value == 'SALES'){
                        var item = selectedItems[selectedRow-1]
                        var selectUnit = document.querySelector(`#item-${code} select[name="unit"]`)
                        var selectedUnit = item.units.find(u => u.unit == selectUnit.value)
                        var price = selectUnit && !document.querySelector('.price_value[data-code="'+code+'"]') ? selectUnit.options[selectUnit.selectedIndex].getAttribute('data-price') : document.querySelector(`#price-${code}`).getAttribute('data-baseprice')
                        if(selectedUnit.min_qty_5 && selectedUnit.min_qty_5 > 0 && qty >= selectedUnit.min_qty_5){
                            price = selectedUnit.amount_5
                        } else if(selectedUnit.min_qty_4 && selectedUnit.min_qty_4 > 0 && qty >= selectedUnit.min_qty_4){
                            price = selectedUnit.amount_4
                        } else if(selectedUnit.min_qty_3 && selectedUnit.min_qty_3 > 0 && qty >= selectedUnit.min_qty_3){
                            price = selectedUnit.amount_3
                        } else if(selectedUnit.min_qty_2 && selectedUnit.min_qty_2 > 0 && qty >= selectedUnit.min_qty_2){
                            price = selectedUnit.amount_2
                        }
                        var subtotal = qty * price;
                        setSubTotal(code, subtotal, price);
                        reloadTable()
                    } else {
                        var subtotal = document.querySelector(`#price-${code}`).value
                        var price = subtotal / qty;
                        document.querySelector(`.base_price_${code}`).innerHTML = formatNumber(price);
                        document.querySelector(`#price-${code}`).setAttribute('data-baseprice', price);
                    }
                }

                function changeUnit(code) {
                    var qty = document.querySelector(`#item-${code} [name="qty"]`).value;
                    var selectUnit = document.querySelector(`#item-${code} [name="unit"]`)
                    var price = selectUnit.options[selectUnit.selectedIndex].getAttribute('data-price')
                    var subtotal = price * qty;
                    setSubTotal(code, subtotal, price);
                    reloadTable()
                }
                
                function changePrice(el) {
                    const code = el.dataset.code
                    var qty = document.querySelector(`#item-${code} [name="qty"]`).value;
                    var subtotal = el.value
                    var price = subtotal / qty;
                    document.querySelector(`.base_price_${code}`).innerHTML = formatNumber(price);
                    // setSubTotal(code, subtotal, price);
                    reloadTable()
                }

                function formatNumber(num) {
                    return  new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(num);
                }

                // function changeDiscount(discount) {
                //     var subtotals = document.querySelectorAll('.prices');
                //     var total = 0;
                //     subtotals.forEach((subtotal) => {
                //         var price = subtotal.getAttribute('data-price');
                //         total += parseInt(price)
                //     });
                //     setTotal(total-discount);
                // }

                function changePaymentAmount(payment) {
                    payment = payment.replaceAll(',','')
                    var total = document.getElementById('total').getAttribute('data-price');
                    var change = payment - total;
                    document.getElementById('change').innerHTML = payment ? formatNumber(change) : 'Rp 0';
                    document.getElementById('change').setAttribute('data-value', change);
                }

                function setTotal(total) {
                    document.getElementById('total').innerHTML = formatNumber(total);
                    document.querySelector('.total-items').innerHTML = formatNumber(total);
                    document.getElementById('total').setAttribute('data-price', total);
                    changePaymentAmount(document.querySelector('input[name="payment_amount"]').value);
                }

                function setSubTotal(code, value, base_price) {
                    if(document.querySelector('.record_type_select').value == 'SALES'){
                        document.querySelector(`.base_price_${code}`).innerHTML = formatNumber(base_price);
                        document.getElementById(`price-${code}`).innerHTML = formatNumber(value);
                        document.getElementById(`price-${code}`).setAttribute('data-price', value);
                        document.getElementById(`price-${code}`).setAttribute('data-baseprice', base_price);
                    }
                }

                function bayar() {

                    var total_item = document.querySelectorAll('.item-table tbody tr');

                    const recordType = document.querySelector('.record_type_select').value

                    var items = [];

                    total_item.forEach((item) => {
                        var id = item.querySelector('input[name="id"]').value;
                        var qty = item.querySelector('input[name="qty"]').value;
                        var unit = item.querySelector('[name="unit"]').value;
                        var price = parseInt(item.querySelector('.prices').getAttribute('data-baseprice'));
                        items.push({
                            product_id: id,
                            name: item.querySelector('.item-name').innerHTML,
                            qty: parseInt(qty),
                            unit: unit,
                            base_price: price,
                            total_price: price * qty,
                            final_price: (price * qty) - 0 // 0 as discount
                        });
                    });

                    var total_price = 0;
                    var subtotals = document.querySelectorAll('.prices');
                    subtotals.forEach((subtotal) => {
                        var price = recordType == 'PURCHASES' ? subtotal.value : subtotal.getAttribute('data-price');
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
                        payment_amount: parseInt(document.querySelector('input[name="payment_amount"]').value.replaceAll(',','')),
                        payment_method: document.querySelector('select[name="payment_method"]').value,
                        change:  parseInt(document.getElementById('change').getAttribute('data-value')),
                        items: items,
                        payment_reference: document.getElementById('reference').value,
                        code: invoice_code,
                        record_type: document.querySelector('select[name="record_type"]').value,
                        customer_id: $('.customer-select').val()
                    }

                    if(data.payment_amount <= 0 || !data.payment_amount)
                    {
                        alert('Nominal Pembayaran tidak boleh kosong dan harus lebih dari 0')
                        return
                    }

                    if(data.change < 0 && data.payment_amount > 0 && !confirm('Nominal Pembayaran kurang dari jumlah yang harus dibayar. Lanjutkan ?'))
                    {
                        return
                    }

                    fetch('/pos', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Berhasil melakukan transaksi');
                        window.location.reload();
                        // selectedItems = []
                        // selectedRow = 1
                        // reloadTable()
                        // setTotal(0)
                        // // document.querySelector('input[name="payment_amount"]').value = ''
                        // document.querySelector('input[name="discount"]').value = ''
                        // document.getElementById('reference').value = ''
                        // // document.getElementById('total').value = 'Rp 0'
                        // // document.getElementById('change').value = 'Rp 0'
                        // $('#paymentModal').modal('hide')
                    })
                    .catch((error) => {
                        alert('Gagal melakukan transaksi');
                    });
                }

                function printLastInvoice()
                {
                    if(confirm('Apakah kamu yakin akan mencetak ulang transaksi terakhir ?')){
                        fetch('/pos/print-last-invoice', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({'action':'print last invoice'})
                        })
                    }
                }

                var span = document.getElementById('clock-active');

                function time() {
                    var d = new Date();
                    var s = d.getSeconds();
                    var m = d.getMinutes();
                    var h = d.getHours();
                    span.textContent = ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
                }

                setInterval(time, 1000);

                let barcodeBuffer = '';
                let lastKeyTime = Date.now();

                document.querySelector('[name="payment_amount"]').addEventListener('keydown', function(e){
                    if(e.key == 'Enter')
                    {
                        bayar()
                    }
                })

                $('.product-lists tbody').on('click', 'tr', function () {
                    const rows = productLists.rows({ page: 'current' }).nodes();

                    // Hapus highlight semua
                    $(rows).removeClass('selected');

                    // Tambah highlight ke yang diklik
                    $(this).addClass('selected');

                    // Update selectedRowIndex
                    selectedProductIndex = $(rows).index(this);
                });

                document.addEventListener('keydown', function(e) {
                    if(e.target.id == 'void_invoice_code' || e.target.id == 'return_invoice_code' || e.target.id == 'reference' || e.target.classList.contains('qty')) return
                    if($('#editProductModal').hasClass('show') || $('#addProductModal').hasClass('show') || $('#voidModal').hasClass('show') || $('#paymentModal').hasClass('show') || $('#returnModal').hasClass('show') || $('#addProductModal').hasClass('show')) return
                    const currentTime = Date.now();

                    // Reset buffer kalau jeda terlalu lama
                    if (currentTime - lastKeyTime > 700) {
                        barcodeBuffer = '';
                    }

                    if (e.key === 'Enter') {
                        if($('#productModal').hasClass('show') && selectedProductIndex > -1){
                            const rows = productLists.rows({ page: 'current' }).nodes();
                            const selectedRow = $(rows[selectedProductIndex]);
                            const code = selectedRow.data('code');

                            if (code) {
                                // Kirim ke server
                                findProduct(code);
                                $('#productModal').modal('hide')
                            }
                            return
                        }
                        const barcode = barcodeBuffer.trim();
                        barcodeBuffer = '';

                        if (barcode.length > 0) {
                            console.log('Barcode scanned (global):', barcode);
                            findProduct(barcode);
                        }
                    } else if (e.key === '*') {
                        const quantity = parseFloat(barcodeBuffer.trim());
                        if(!isNaN(quantity)){
                            setQty(quantity);
                            barcodeBuffer = '';
                        }
                    } else {
                        var addKeyToBuffer = true
                        if (e.key === 'ArrowUp') {
                            if($('#productModal').hasClass('show')){
                                const rows = productLists.rows({ page: 'current' }).nodes();
                                e.preventDefault();
                                if (selectedProductIndex > 0) {
                                    selectedProductIndex--;
                                    highlightRow(rows);
                                }
                            }
                            else
                            {
                                updateSelection(selectedRow - 1);
                            }

                            addKeyToBuffer = false
                        } else if (e.key === 'ArrowDown') {
                            if($('#productModal').hasClass('show')){
                                const rows = productLists.rows({ page: 'current' }).nodes();
                                e.preventDefault();
                                if (selectedProductIndex < rows.length - 1) {
                                    selectedProductIndex++;
                                    highlightRow(rows);
                                }
                                
                            }
                            else
                            {
                                updateSelection(selectedRow + 1);
                            }

                            addKeyToBuffer = false
                        } else if (e.key == 'ArrowLeft') {
                            $('#productModal').modal('show')
                            setTimeout(() => {
                                // $('#modal_product_id').select2('open')
                                $('.dt-search input').focus();
                            }, 1000);

                            addKeyToBuffer = false
                        } else if (e.key === 'Delete') {
                            doDelete()
                            addKeyToBuffer = false
                        } else if (e.key === 'End') {
                            $('#paymentModal').modal('show')
                            setTimeout(() => {
                                document.querySelector('[name="payment_amount"]').focus()
                            }, 1000);
                            addKeyToBuffer = false
                        } else if (e.key === 'F2') {
                            addProduct('')
                        } else if (e.key === 'F3') {
                            e.preventDefault()
                            if($('#productModal').hasClass('show')){
                                $('#productModal').modal('hide')
                                const rows = productLists.rows({ page: 'current' }).nodes();
                                e.preventDefault();
                                if (selectedProductIndex < rows.length - 1) {
                                    const selectedRow = $(rows[selectedProductIndex]);
                                    const code = selectedRow.data('code');
                                    initEditProduct(code)
                                }
                            }
                            else
                            {
                                const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                                const code = row.dataset.code
                                initEditProduct(code)
                            }
                            
                        } else if (e.key === 'F9') {
                            printLastInvoice()
                        }

                        if(addKeyToBuffer)
                        {
                            barcodeBuffer += e.key;
                        }
                    }

                    lastKeyTime = currentTime;

                });

                function setActive(el)
                {
                    const arr = document.querySelectorAll('.cart-item')
                    const index = Array.from(arr).findIndex(cart => cart == el)
                    updateSelection(index+1)
                }

                function updateSelection(newIndex) {
                    if(selectedItems.length == 0) return

                    if(newIndex == 0)
                    {
                        selectedRow = document.querySelectorAll('.cart-item').length
                    }
                    else if(newIndex > document.querySelectorAll('.cart-item').length)
                    {
                        selectedRow = 1
                    }
                    else
                    {
                        selectedRow = newIndex
                    }

                    document.querySelectorAll('.cart-item').forEach(el => el.classList.remove('selected'))
                    document.querySelectorAll('.cart-item')[selectedRow-1].classList.add('selected')
                }

                function setQty(qty)
                {
                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                    if(!row) return
                    if(row.querySelector('.qty').max && qty > row.querySelector('.qty').max) return
                    row.querySelector('.qty').value = qty
                    changeQty(row.querySelector('.qty').value, row.getAttribute('data-code'))
                }

                function updateQty(additional)
                {
                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                    if(!row) return
                    const newVal = parseInt(row.querySelector('.qty').value) + parseInt(additional)
                    if(row.querySelector('.qty').max && newVal > row.querySelector('.qty').max) return
                    row.querySelector('.qty').value = newVal
                    changeQty(row.querySelector('.qty').value, row.getAttribute('data-code'))
                }

                function setAmountFocus()
                {
                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                    const input = row.querySelector('.qty')
                    input.focus()
                }
                
                function openUnit()
                {
                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                    $(row).find('.select-unit').select2('open')
                }

                function doDelete(){
                    if(selectedItems.length == 0) return
                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                    const itemName = row.querySelector('.item-name').innerHTML
                    if(confirm('Apakah kamu yakin akan menghapus '+ itemName + '?'))
                    {
                        row.remove()
                        selectedItems.splice(selectedRow-1, 1)
                        selectedRow = 1
                        reloadTable()
                    }
                }

                $('#voidModal').on('shown.bs.modal', function () {
                    $('#void_invoice_code').focus();
                })
                
                $('#returnModal').on('shown.bs.modal', function () {
                    $('#return_invoice_code').focus();
                })

                $('#void_invoice_code').on('keydown',function(e){
                    if(e.key == 'Enter')
                    {
                        confirmVoid()
                    }
                })
                
                $('#return_invoice_code').on('keydown',function(e){
                    if(e.key == 'Enter')
                    {
                        initReturn()
                    }
                })

                function confirmVoid()
                {
                    const code = $('#void_invoice_code').val()
                    if(!code)
                    {
                        alert('Kode tidak boleh kosong')
                        return
                    }
                    if(confirm('Apakah kamu yakin akan membatalkan transaksi dengan kode '+code+'?'))
                    {
                        fetch('/sales-purchases/void-sales', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({code: code})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.status == 'success')
                            {
                                alert('Berhasil membatalkan transaksi');
                                // window.location.reload();
                            }
                            else
                            {
                                alert(data.message)
                            }
                        })
                        .catch((error) => {
                            alert('Transaksi gagal dibatalkan');
                        });
                    }
                }

                function initReturn()
                {
                    const code = $('#return_invoice_code').val()
                    if(!code)
                    {
                        alert('Kode tidak boleh kosong')
                        return
                    }

                    fetch('/sales-purchases/return-sales', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({code: code})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status == 'success')
                        {
                            invoice_code = code
                            initItemTable(data.data)
                            $('#returnModal').modal('hide')
                        }
                        else
                        {
                            alert(data.message)
                        }
                    })
                    .catch((error) => {
                        alert('Transaksi tidak ditemukan');
                    });
                }


                function initItemTable(data)
                {
                    document.querySelector('.item-table tbody').innerHTML = ''
                    data.items.forEach((item, key) => {
                        var html = `
                                <tr id="item-${item.product.code}" class="cart-item" data-code="${item.product.code}" onclick="setActive(this)" style="cursor:pointer;">
                                        <td width="25px">${key+1} <input type="hidden" name="id" value="${item.product_id}"></td>
                                        <td>
                                            <span class="item-name">${item.product.name}</span><br>
                                            ${item.product.code} ${item.product.sku ? '-' + item.product.sku : ''}
                                        </td>
                                        <td width="70px">
                                            <input type="hidden" name="unit" value="${item.unit}">
                                            ${item.unit}
                                        <td width="70px">
                                            <input type="number" name="qty" class="form-control qty form-lg" style="width:70px" placeholder="Masukkan jumlah" value="${item.qty}" min="0" max="${item.qty}" onchange="changeQty(this.value, '${item.product.code}')">
                                        </td>   
                                        <td id="price-${item.product.code}" class="prices text-end" data-price="${item.total_price}" data-baseprice="${item.base_price}">
                                            ${formatNumber(item.total_price)}
                                        </td>
    
                                    </tr>
                                `
    
                        $('tbody').append(html)
                        selectedItems.push(item)
                    })

                    document.querySelector('#reference').value = data.payment[0].reference
                    document.querySelector('[name="payment_amount"]').value = data.final_price.replaceAll(',','')
                    document.querySelector('[name="discount"]').value = data.total_discount.replaceAll(',','')
                    document.querySelector('[name="payment_amount"]').setAttribute('readonly','readonly')
                    document.querySelector('[name="discount"]').setAttribute('readonly','readonly')

                    updateSelection(1)
                    reloadTable()
                }

                function addItem()
                {
                    const code = $('#modal_product_id').val()
                    findProduct(code)
                    $('.btn-product-close').trigger('click')
                }

                function calculatePurchasePrice()
                {
                    try {
                        const total_price = $('#product_stock_price').val()
                        const stock = $('#product_stock').val()
                        const purchase_price = total_price/stock
                        $('#product_purchase_price').val(purchase_price)
                    } catch (error) {
                        
                    }
                }
                
                function calculatePurchasePriceEdit()
                {
                    try {
                        const total_price = $('#edit_product_stock_price').val()
                        const stock = $('#edit_product_stock').val()
                        const purchase_price = total_price/stock
                        $('#edit_product_purchase_price').val(purchase_price)
                    } catch (error) {
                        
                    }
                }

                function saveAndAddProductToList()
                {
                    var productData = {
                        code: $('#product_code').val(),
                        name: $('#product_name').val(),
                        stock: $('#product_stock').val(),
                        purchase_price: $('#product_purchase_price').val(),
                        stock_price: $('#product_stock_price').val(),
                        unit: $('#product_unit').val(),
                        price_1: $('#product_price_1').val(),
                        qty_1: $('#product_qty_1').val(),
                        price_2: $('#product_price_2').val(),
                        qty_2: $('#product_qty_2').val(),
                        price_3: $('#product_price_3').val(),
                        qty_3: $('#product_qty_3').val(),
                        price_4: $('#product_price_4').val(),
                        qty_4: $('#product_qty_4').val(),
                        // price_5: $('#product_price_5').val(),
                        // qty_5: $('#product_qty_5').val(),
                    }
                    fetch('/sales-purchases/add-product', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(productData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status == 'success')
                        {
                            findProduct(productData.code)
                            $('#product_code').val('')
                            $('#product_name').val('')
                            $('#product_stock').val('')
                            $('#product_stock_price').val('')
                            $('#product_purchase_price').val('')
                            $('#product_unit').val('PCS')
                            $('#product_price_1').val('')
                            $('#product_qty_1').val(1)
                            $('#product_price_2').val('')
                            $('#product_qty_2').val('')
                            $('#product_price_3').val('')
                            $('#product_qty_3').val('')
                            $('#product_price_4').val('')
                            $('#product_qty_4').val('')
                            $('#addProductModal').modal('hide')
                        }
                    })
                    .catch((error) => {
                        alert('Gagal Disimpan');
                    });
                }

                function updateProduct()
                {
                    var productData = {
                        id: $('#edit_product_id').val(),
                        code: $('#edit_product_code').val(),
                        name: $('#edit_product_name').val(),
                        stock: $('#edit_product_stock').val(),
                        purchase_price: $('#edit_product_purchase_price').val(),
                        stock_price: $('#edit_product_stock_price').val(),
                        unit: $('#edit_product_unit').val(),
                        price_1: $('#edit_product_price_1').val(),
                        qty_1: $('#edit_product_qty_1').val(),
                        price_2: $('#edit_product_price_2').val(),
                        qty_2: $('#edit_product_qty_2').val(),
                        price_3: $('#edit_product_price_3').val(),
                        qty_3: $('#edit_product_qty_3').val(),
                        price_4: $('#edit_product_price_4').val(),
                        qty_4: $('#edit_product_qty_4').val(),
                        // price_5: $('#product_price_5').val(),
                        // qty_5: $('#product_qty_5').val(),
                    }
                    fetch('/sales-purchases/update-product', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(productData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status == 'success')
                        {
                            $('#edit_product_code').val('')
                            $('#edit_product_name').val('')
                            $('#edit_product_stock').val('')
                            $('#edit_product_stock_price').val('')
                            $('#edit_product_purchase_price').val('')
                            $('#edit_product_unit').val('PCS')
                            $('#edit_product_price_1').val('')
                            $('#edit_product_qty_1').val(1)
                            $('#edit_product_price_2').val('')
                            $('#edit_product_qty_2').val('')
                            $('#edit_product_price_3').val('')
                            $('#edit_product_qty_3').val('')
                            $('#edit_product_price_4').val('')
                            $('#edit_product_qty_4').val('')
                            $('#editProductModal').modal('hide')
                            
                            try {
                                if(selectedRow){
                                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                                    const code = row.dataset.code
                                    if(productData.code == code){
                                        // update price
                                        var qty = document.querySelector(`#item-${code} [name="qty"]`).value;
                                        var price = productData.price_1
                                        var subtotal = price * qty;

                                        var selectUnit = document.querySelector(`#item-${code} [name="unit"]`)
                                        selectUnit.options[selectUnit.selectedIndex].setAttribute('data-price', price)
                                        setSubTotal(code, subtotal, price);
                                        reloadTable()
                                    }
                                }
                                
                            } catch (error) {
                                
                            }
                        }
                    })
                    .catch((error) => {
                        alert('Gagal Disimpan');
                    });
                }

                function initEditProduct(code){
                    fetch('/sales-purchases/get-product/'+code)
                    .then(response => response.json())
                    .then(data => {
                        if(data.status == 'success')
                        {
                            $('#editProductModal').modal('show')
                            $('#edit_product_id').val(data.data.id)
                            $('#edit_product_code').val(data.data.code)
                            $('#edit_product_name').val(data.data.name)
                            $('#edit_product_stock').val('')
                            $('#edit_product_stock_price').val('')
                            $('#edit_product_purchase_price').val('')
                            $('#edit_product_unit').val(data.data.prices[0].unit)
                            $('#edit_product_price_1').val(data.data.prices[0].amount_1)
                            $('#edit_product_qty_1').val(data.data.prices[0].min_qty_1)
                            $('#edit_product_price_2').val(data.data.prices[0].amount_2)
                            $('#edit_product_qty_2').val(data.data.prices[0].min_qty_2)
                            $('#edit_product_price_3').val(data.data.prices[0].amount_3)
                            $('#edit_product_qty_3').val(data.data.prices[0].min_qty_3)
                            $('#edit_product_price_4').val(data.data.prices[0].amount_4)
                            $('#edit_product_qty_4').val(data.data.prices[0].min_qty_4)
                        }
                    })
                    .catch((error) => {
                    });
                }

                setAutoNumeric(document.querySelectorAll('.autonumeric'))
            </script>
    </body>
</html>