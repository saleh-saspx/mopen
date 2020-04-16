@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">کد های درج شده</div>
                    <div class="card-body">

                        <form action="{{ isset($coupon) ? $coupon->updateUrl : route("listUpload") }}"
                              enctype="multipart/form-data"
                              method="post">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="name">عنوان</label>
                                    <input type="text" value="{{ isset($coupon->name) ? $coupon->name : '' }}" required
                                           class="form-control" name="name" id="name" placeholder="">
                                </div>
                                <div class="form-group col-4">
                                    <label for="code">لیست کد ها</label>
                                    <input type="file" required
                                           class="form-control" name="files" id="code" placeholder="">
                                </div>
                                <div class="form-group col-4">
                                    <label for="amount">میزان تخفیف</label>
                                    <input type="text" value="{{ isset($coupon->amount) ? $coupon->amount : '' }}"
                                           required class="form-control" name="amount" id="amount" placeholder="">
                                </div>
                                <div class="form-group col-4">
                                    <label for="link">لینک تخفیف</label>
                                    <input type="url" value="{{ isset($coupon->link) ? $coupon->link : '' }}" required
                                           class="form-control" name="link" id="link" placeholder="">
                                </div>
                                <div class="form-group col-4">
                                    <label for="brand">برند</label>
                                    <select class="form-control" required id="brand" name="brand_id">
                                        @forelse($brands as $brand)
                                            <option
                                                @if(isset($coupon->brand_id) && $coupon->brand_id == $brand->id) selected
                                                @endif value="{{ $brand->id }}">{{ $brand->name . " - " . $brand->category->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label for="type">نوع</label>
                                    <select required class="form-control" id="type" name="type">
                                        <option @if(isset($coupon->type) && $coupon->type == 'coupon') selected
                                                @endif value="coupon">کد تخفیف
                                        </option>
                                        <option @if(isset($coupon->type) && $coupon->type == 'offer') selected
                                                @endif value="offer">پیشنهاد تخفیف دار
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label for="publish_at">تاریخ انتشار در سایت</label>
                                    <input type="text"
                                           value="{{ isset($coupon->publish_at) ? $coupon->publish_at : '' }}" required
                                           class="form-control publish_at" id="publish_at"
                                           placeholder="">
                                    <input type="hidden" name="publish_at" class="publish_at-timestamp">
                                </div>
                                <div class="form-group col-4">
                                    <label for="start_at">تاریخ شروع</label>
                                    <input type="text"
                                           value="{{ isset($coupon->start_at) ? $coupon->start_at : '' }}" required
                                           class="form-control start_at" id="start_at" placeholder="">
                                    <input type="hidden" name="start_at" class="start_at-timestamp">

                                </div>
                                <div class="form-group col-4">
                                    <label for="expired_at">تاریخ منقضی</label>
                                    <input type="text"
                                           value="{{ isset($coupon->expired_at) ? $coupon->expired_at : '' }}" required
                                           class="form-control expired_at" id="expired_at"
                                           placeholder="">
                                    <input type="hidden" name="expired_at" class="expired_at-timestamp">

                                </div>
                            </div>
                            @csrf
                            @if(isset($coupon))
                                @method('PUT')
                            @endif
                            <button type="submit"
                                    class="btn btn-block @if(isset($coupon)) btn-info @else btn-success @endif">@if(isset($coupon))
                                    ویرایش @else ثبت  @endif</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function () {
            makeDatePicker('expired_at');
            makeDatePicker('start_at');
            makeDatePicker('publish_at');
        }, 1);
    </script>
@endsection
