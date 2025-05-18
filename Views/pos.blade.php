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
        </style>
    </head>
    <body>
        <nav class="navbar bg-danger text-white">
            <div class="container-fluid">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="d-flex align-items-center" style="gap:8px">
                        <div
                            class="bg-white rounded-circle text-secondary d-flex justify-content-center align-items-center"
                            style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="m-0">{{auth()->user()->name}}</h6>
                            <span style="font-size: 12px;">{!! auth()->user()->userRoleLabel !!}</span>
                        </div>
                    </div>
                    <div class="d-flex position-relative d-none d-md-block" role="search" style="max-width: 350px;width:100%;">
                        <input class="form-control" name="code" type="search" placeholder="Masukkan kode produk" aria-label="Search" onchange="findProduct(this.value)" />
                    </div>
                    <div class="text-end">
                        <p class="m-0 text-end d-block" id="clock-active">Loading clock...</p>
                        <span>{{date('l, d-m-Y')}}</span>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-12 d-block d-md-none mb-3">
                    <input class="form-control" name="code" type="search" placeholder="Masukkan kode produk" aria-label="Search" onchange="findProduct(this.value)" />
                </div>
                <div class="col-12 col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-bordered item-table">
                            <thead>
                                <tr>
                                    <th class="text-center" width="25px">No</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center" width="70px">Satuan</th>
                                    <th class="text-center" width="70px">Jumlah</th>
                                    <th width="200px" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                <tr><td colspan="5"><i>Tidak ada data</i></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <label for="">Diskon</label>
                            <input type="number" name="discount" onkeyup="reloadTable()" class="form-control text-end" placeholder="Masukkan diskon" value="0">
                        </li>
                        <li class="list-group-item px-0">
                            <label for="">Metode Pembayaran</label>
                            <select name="payment_method" class="form-control">

                                @foreach ($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                    
                                @endforeach
                            </select>
                        </li>
                        <li class="list-group-item px-0">
                            <input type="number" name="payment_amount" onkeyup="changePaymentAmount(this.value)" class="form-control text-end" placeholder="Masukkan Jumlah Bayar">
                        </li>
                        <li
                            class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total</h5>
                                <h5 class="text-danger" id="total">Rp 0</h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Kembalian</span>
                                <span id="change">Rp 0</span>
                            </div>
                        </li>

                        <li class="list-group-item px-0">
                            <button class="btn btn-lg btn-danger w-100" onclick="bayar()">Bayar</button>
                        </li>

                    </ul>

                </div>
            </div>

        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
            crossorigin="anonymous"></script>


            <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

            <script>
                var selectedItems = []
                var selectedRow = 1

                function findProduct(code) {
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
                                                    <span class="item-name">${item.name}</span><br>
                                                    ${item.code} ${item.sku ? '-' + item.sku : ''}
                                                </td>
                                                <td width="70px">
                                                    <select name="unit" style="width:70px" class="form-control form-lg" onchange="changeUnit('${item.code}', '${item.price}')">
                                                        ${item.units.length == 0 ? '<option value="">Tidak ada unit</option>' : ''}
                                                        ${item.units.map(unit => `<option value="${unit.unit}" data-price="${unit.amount_1}" ${unit.unit == item.unit ? 'selected' : ''}>${unit.unit}</option>`)}
                                                    </td>
                                                <td width="70px">
                                                    <input type="number" name="qty" class="form-control qty form-lg" style="width:70px" placeholder="Masukkan jumlah" value="1" onchange="changeQty(this.value, '${item.code}')">
                                                </td>   
                                                <td id="price-${item.code}" class="prices text-end" data-price="${item.price}" data-baseprice="${item.price}">
                                                    ${formatNumber(item.price)}
                                                </td>
    
                                            </tr>
                                    `

                                    $('tbody').append(html)
                                    // document.querySelector('tbody').innerHTML += html;
    
                                    changeUnit(item.code)
    
                                }
                                
                                document.querySelector('input[name="code"]').value = '';
                                updateSelection(1)
                                // document.querySelector('input[name="code"]').focus();
                        })
                        .catch(err => {

                        });
                }      

                function reloadTable()
                {
                    var discount = document.querySelector('input[name="discount"]').value;
                    var subtotals = document.querySelectorAll('.prices');
                    var total = 0;
                    subtotals.forEach((sbtotal) => {
                        var price = sbtotal.getAttribute('data-price');
                        total += parseInt(price)
                    });
                    setTotal(total-discount);

                    if(selectedItems.length == 0)
                    {
                        document.querySelector('tbody').innerHTML = '<td colspan="5"><i>Tidak ada data</i></td>'
                    }
                }
                
                function changeQty(qty, code) {
                    if(qty == 0)
                    {
                        document.querySelector(`#item-${code}`).remove()
                        const index = selectedItems.findIndex(itm => itm.code == code)
                        selectedItems.splice(index, 1)
                        reloadTable()
                        return
                    }
                    var selectUnit = document.querySelector(`#item-${code} [name="unit"]`)
                    var price = selectUnit.options[selectUnit.selectedIndex].getAttribute('data-price')
                    var subtotal = qty * price;
                    setSubTotal(code, subtotal, price);
                    reloadTable()
                }

                function changeUnit(code) {
                    var qty = document.querySelector(`#item-${code} [name="qty"]`).value;
                    var selectUnit = document.querySelector(`#item-${code} [name="unit"]`)
                    var price = selectUnit.options[selectUnit.selectedIndex].getAttribute('data-price')
                    var subtotal = price * qty;
                    setSubTotal(code, subtotal, price);
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
                    var total = document.getElementById('total').getAttribute('data-price');
                    var change = payment - total;
                    document.getElementById('change').innerHTML = payment ? formatNumber(change) : 'Rp 0';
                    document.getElementById('change').setAttribute('data-value', change);
                }

                function setTotal(total) {
                    document.getElementById('total').innerHTML = formatNumber(total);
                    document.getElementById('total').setAttribute('data-price', total);
                    changePaymentAmount(document.querySelector('input[name="payment_amount"]').value);
                }

                function setSubTotal(code, value, base_price) {
                    document.getElementById(`price-${code}`).innerHTML = formatNumber(value);
                    document.getElementById(`price-${code}`).setAttribute('data-price', value);
                    document.getElementById(`price-${code}`).setAttribute('data-baseprice', base_price);
                }

                function bayar() {

                    var total_item = document.querySelectorAll('tbody tr');

                    var items = [];

                    total_item.forEach((item) => {
                        var id = item.querySelector('input[name="id"]').value;
                        var qty = item.querySelector('input[name="qty"]').value;
                        var unit = item.querySelector('select[name="unit"]').value;
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
                        change:  parseInt(document.getElementById('change').getAttribute('data-value')),
                        items: items
                    }

                    console.log(data)

                    if(data.payment_amount <= 0)
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
                    })
                    .catch((error) => {
                        alert('Gagal melakukan transaksi');
                    });
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

                document.addEventListener('keydown', function(e) {
                    const currentTime = Date.now();

                    // Reset buffer kalau jeda terlalu lama
                    if (currentTime - lastKeyTime > 300) {
                        barcodeBuffer = '';
                    }

                    if (e.key === 'Enter') {
                        const barcode = barcodeBuffer.trim();
                        barcodeBuffer = '';

                        if (barcode.length > 0) {
                            console.log('Barcode scanned (global):', barcode);
                            findProduct(barcode);
                        }
                    } else {
                        barcodeBuffer += e.key;
                    }

                    lastKeyTime = currentTime;

                    if (e.key === 'ArrowUp') {
                        updateSelection(selectedRow - 1);
                    } else if (e.key === 'ArrowDown') {
                        updateSelection(selectedRow + 1);
                    } else if (e.key === 'Delete') {
                        doDelete()
                    } else if (e.key === '+') {
                        updateQty(1);
                    } else if (e.key === '-') {
                        updateQty(-1);
                    } else if (e.key === 'End') {
                        document.querySelector('[name="payment_amount"]').focus()
                    } else if (e.key === 'd' || e.key === 'D') {
                        document.querySelector('[name="discount"]').focus()
                    }
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

                function updateQty(additional)
                {
                    const row = document.querySelectorAll('.cart-item')[selectedRow-1]
                    row.querySelector('.qty').value = parseInt(row.querySelector('.qty').value) + parseInt(additional)
                    changeQty(row.querySelector('.qty').value, row.getAttribute('data-code'))
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
                    }
                }

            </script>
    </body>
</html>