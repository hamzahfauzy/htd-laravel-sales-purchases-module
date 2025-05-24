<div class="item table-responsive">
    <table class="table table-bordered table-striped table-invoice-item">
        <thead>
            <tr>
                <th style="width: 1%;white-space:nowrap;">No</th>
                <th>Product</th>
                <th width="150px">Qty</th>
                <th width="150px">Unit</th>
                <th width="150px">Price</th>
                <th width="150px">Subtotal</th>
                <th width="65px"><button type="button" class="btn btn-sm btn-info btn-add-item"><i class="bx bxs-plus-square"></i></button></th>
            </tr>
        </thead>
        <tbody>
            @if($data->items)
            @foreach($data->items as $key => $item)
            <tr class="item" data-index="{{$key}}">
                <td>{{$key+1}}</td>
                <td>
                    <select name="items[{{$key}}][product_id]" class="form-control form-select product-select">
                        <option value="{{$item->product->id}}" selected>{{$item->product->name}}</option>
                    </select>
                </td>
                <td><input type="text" name="items[{{$key}}][qty]" class="qty form-control autonumeric" value="{{$item->qty}}"></td>
                <td>
                    <select class="form-control form-select unit" name="items[{{$key}}][unit]">
                        @foreach($item->product->prices as $price)
                        <option value="{{$price->unit}}" {{$price->unit == $item->unit ? 'selected=""' : ''}} data-amount="{{$price->amount_1}}">{{$price->unit}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="items[{{$key}}][base_price]" class="base_price form-control autonumeric" value="{{$item->base_price}}">
                </td>
                <td>
                    <input type="hidden" name="items[{{$key}}][total_discount]" value="0" class="discount">
                    <input type="hidden" name="items[{{$key}}][final_price]" class="final_price" value="{{$item->final_price}}">
                    <span class="final_price-label final_price-{{$key}} autonumeric">{{$item->final_price}}</span>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm removeRowBtn"><i class="bx bx-trash"></i></button></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="8" class="text-center"><i>Item is empty</i></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<script>window.items = {{count($data?->items) ?? 0}};</script>