<div class="detail">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width: 1%;white-space:nowrap;">No</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Final Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->items as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->product->name}}</td>
                <td>{{$item->qty}} {{$item->unit}}</td>
                <td>{{$item->total_price}}</td>
                <td>{{$item->total_discount}}</td>
                <td>{{$item->final_price}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>