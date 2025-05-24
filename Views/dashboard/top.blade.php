<div class="row mt-4">
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h4>Top Sales</h4>
            </div>
            <div class="card-body">
              <table class="table">
                <tr>
                    <td>#</td>
                    <td>Product</td>
                    <td>Total Sales</td>
                </tr>
                @forelse($topSales as $key => $product)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{number_format($product->total_sales)}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center"><i>No Data</i></td>
                </tr>
                @endforelse
              </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h4>Top Products</h4>
            </div>
            <div class="card-body">
              <table class="table">
                <tr>
                    <td>#</td>
                    <td>Product</td>
                    <td>Total Transaction</td>
                </tr>
                @forelse($topProducts as $key => $product)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{number_format($product->total_qty)}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center"><i>No Data</i></td>
                </tr>
                @endforelse
              </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h4>Low Stock Products</h4>
            </div>
            <div class="card-body">
              <table class="table">
                <tr>
                    <td>#</td>
                    <td>Product</td>
                    <td>Stock</td>
                </tr>
                @forelse($lowStockProducts as $key => $product)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{number_format($product->stock)}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center"><i>No Data</i></td>
                </tr>
                @endforelse
              </table>
            </div>
        </div>
    </div>
</div>