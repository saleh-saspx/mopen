@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        کد های درج شده
                        <div class="float-left">
                            <a class="btn btn-sm btn-dark" target="_blank" href="{{ route('coupon.create') }}">ایجاد
                                کوپن</a>
                                <a class="btn btn-sm btn-dark" target="_blank" href="{{ route('listUploadPage') }}"> ایجاد
                                گروهی کوپن</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">عنوان</th>
                                <th scope="col">شناسه کد</th>
                                <th scope="col">تاریح ثبت</th>
                                <th scope="col">ثبت کننده</th>
                                <th scope="col">ثبت کننده</th>
                                <th scope="col">مدیریت</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($coupons as $coupon)
                                <tr id="coupon-{{ $coupon->id }}">
                                    <th scope="row">1</th>
                                    <td>{{ $coupon->name }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td style="text-align: justify">{{ jdate($coupon->created_at)->format("Y/m/d") }} ساعت {{ jdate($coupon->created_at)->format("h:i") }} </td>
                                    <td>{{ $coupon->user->name }}</td>
                                    <td><span
                                            class="text-{{ $coupon->StatusText['class'] }}">{{ $coupon->StatusText['text'] }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ $coupon->editUrl }}" target="_blank" class="btn btn-success btn-sm">ویرایش</a>
                                        <a href="{{ $coupon->showUrl }}" target="_blank" class="btn btn-info btn-sm">نمایش</a>
                                        <button onclick="deleteCoupon('{{ $coupon->id }}')"
                                                class="btn btn-danger btn-sm">حذف
                                        </button>
                                        <button onclick="updateStatus('{{ $coupon->id }}','{{ $coupon->status }}')"
                                                class="btn btn-warning btn-sm">تغیر وضیعت
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>چیزی جهت نمایش وجود ندارد</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateStatus(id, status) {
            let ask = confirm('آیا مایل به ویرایش وضیعت این کوپن هستید ؟ ');
            if (ask) {
                $.ajax({
                    url: '{{ route('coupon.index') }}/' + id,
                    type: "post",
                    data: {
                        status: status === 'hold' ? "show" : "hold",
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        alert(data.message);
                        setTimeout(function () {
                            location.reload();
                        }, 2000)
                    },
                    error(xhr) {
                        let err = JSON.parse(xhr.responseText);
                        alert(err.message);
                    }
                })
            }
        }

        function deleteCoupon(id) {
            let ask = confirm('آیا مطمن هستید میخواید این کوپن را حذف کنید ؟');
            if (ask) {
                $.ajax({
                    url: '{{ route('coupon.index') }}/' + id,
                    type: "post",
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        alert('با موفقیت حذف شد');
                        $('#coupon-' + id).remove();
                    },
                    error(xhr) {
                        let err = JSON.parse(xhr.responseText);
                        alert(err.message);
                    }
                })
            }
        }
    </script>
@endsection
