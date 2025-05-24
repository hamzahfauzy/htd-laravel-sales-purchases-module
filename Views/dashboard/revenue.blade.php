<div class="row">
    @foreach($summary as $title => $revenue)
    <div class="col-12 col-md-3">
        <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10 flex-wrap">
                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                  <div class="card-title mb-6">
                    <h5 class="text-nowrap mb-1">{{$title}}</h5>
                    <span class="badge bg-label-success">
                        @if(\Str::startsWith($title, 'Month'))
                        {{date('F Y')}}
                        @else
                        {{date('d-m-Y')}}
                        @endif
                    </span>
                  </div>
                  <div class="mt-sm-auto">
                    <span class="text-success text-nowrap fw-medium"></span>
                    <h3 class="mb-0">{{number_format($revenue)}}</h3>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    @endforeach
</div>