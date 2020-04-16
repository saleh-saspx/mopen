@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $coupon->name }}</div>

                    <div class="card-body">
                        <ul class="list-group p-0 m-0">
                            <li class="list-group-item">عنوان کوپن : <span>{{ $coupon->name }}</span></li>
                            <li class="list-group-item">کد کوپن : <span>{{ $coupon->code }}</span></li>
                            <li class="list-group-item">ارسال کننده کوپن : <span>{{ $coupon->user->name }}</span></li>
                            <li class="list-group-item">وضیعت کوپن : <span
                                    class="badge badge-{{ $coupon->status_text['class'] }}">{{ $coupon->status_text['text'] }}</span>
                            <li class="list-group-item">اعتبار :
                                <span>{{ $coupon->expired_at != null && $coupon->expired_at > now() ? jdate($coupon->expired_at)->ago() . "بعد" : '-'}}</span>
                            </li>
                            <li class="list-group-item">تاریخ ثبت کوپن
                                :<span>{{ jdate($coupon->updated_at)->ago() }}</span></li>
                            <li class="list-group-item"> پسندیده :<span> {{ $coupon->like->where('type','like')->count() }} نفر </span><span
                                    class="float-left" id="like"><button onclick="Like('like')" type="button"
                                                                         class="btn btn-sm btn-success">پسندیدن</button></span>
                            </li>
                            <li class="list-group-item"> رد شده :<span> {{ $coupon->like->where('type','desLike')->count() }} نفر </span><span
                                    class="float-left" id="desLike"><button onclick="Like('desLike')" type="button"
                                                                            class="btn btn-sm btn-danger">رد کدرن</button></span>
                            </li>
                        </ul>
                        <p class="mt-2">
                            دسته بندی : {{ $coupon->brand->category->name }}
                        </p>
                        <p class="mt-2">
                            برند : {{ $coupon->brand->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function Like(type) {
            $.ajax({
                url: '{{ route('like') }}',
                type: 'post',
                data: {
                    type: type,
                    model_id: '{{ $coupon->id }}',
                    model_type: "App\\Coupon",
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    alert(data.message);
                    $('#' + type).remove();
                    // location.reload();
                }
            });
        }
    </script>
@endsection
