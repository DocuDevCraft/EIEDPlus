@extends('dashboard::layouts.dashboard.master')

@section('title')
    مشاهده صورتحساب
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/payment/images/icons/payments.gif') }}"></span>
    <span class="text">مشاهده صورتحساب</span>
@endsection

@section('content')
    <section class="form-section">
        <div class="row">
            <div class="col-9">
                {{-- اطلاعات فریلنسر --}}
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">اطلاعات فریلنسر</span>
                    </div>

                    <div class="widget-content widget-content-padding">
                        <div class="row">
                            {{-- نام و نام خانوادگی --}}
                            <div class="item-info col-4">
                                <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                    <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">نام و نام خانوادگی</div>
                                    <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Payment->Users->first_name . ' ' . $Payment->Users->last_name }}</div>
                                </div>
                            </div>
                            {{-- شماره شبا --}}
                            @isset($Payment->Freelancer->shaba)
                                <div class="item-info col-8">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">شماره شبا</div>
                                        <div class="item-value font-weight-bold left" style="font-size: 14px">IR{{ $Payment->Freelancer->shaba }}</div>
                                    </div>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>

                {{-- اطلاعات صورتحساب --}}
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">اطلاعات صورتحساب</span>
                    </div>

                    <div class="widget-content widget-content-padding">
                        <div class="row">
                            {{-- عنوان بسته کاری --}}
                            @isset($Payment->workPackage->title)
                                <div class="item-info col-8 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">عنوان و کد بسته کاری</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ $Payment->workPackage->package_number . ' - ' .$Payment->workPackage->title }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- مبلغ بسته کاری --}}
                            @isset($Payment->workPackage->wp_final_price)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">مبلغ بسته کاری</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ number_format($Payment->workPackage->wp_final_price) }} تومان</div>
                                    </div>
                                </div>
                            @endisset

                            {{-- عنوان فعالیت --}}
                            @isset($Payment->activity->title)
                                <div class="item-info col-8 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">عنوان فعالیت</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Payment->activity->title }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- سهم فعالیت --}}
                            @isset($Payment->activity->price_percentage)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">سهم فعالیت</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ $Payment->activity->price_percentage }} درصد</div>
                                    </div>
                                </div>
                            @endisset

                            {{-- عنوان مرحله --}}
                            @isset($Payment->category->title)
                                <div class="item-info col-8 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">عنوان مرحله</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Payment->category->title }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- سهم مرحله --}}
                            @isset($Payment->activity->price_percentage)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">سهم مرحله</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ $Payment->category->price_percentage }} درصد</div>
                                    </div>
                                </div>
                            @endisset

                            {{-- مبلغ صورتحساب --}}
                            <div class="item-info col-8">
                                <div style="background: #384c65" class="p-2 px-3 rounded">
                                    <div class="item-value font-weight-bold text-white" style="font-size: 16px">مبلغ صورتحساب</div>
                                </div>
                            </div>
                            {{-- مبلغ صورتحساب --}}
                            <div class="item-info col-4">
                                <div style="background: #384c65" class="p-2 px-3 rounded">
                                    <div class="item-value font-weight-bold num-fa text-white" style="font-size: 16px">{{ number_format($Payment->amount) }} تومان</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3">
                <form action="{{ route('payment.update', $Payment->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    {{-- Publish Options --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <div class="form-group row no-gutters">
                                @if($errors->has('status'))
                                    <span class="message-show">{{ $errors->first('status') }}</span>
                                @endif
                                <div class="col-12 field-style ">
                                    <select data-placeholder="یک مورد را انتخاب کنید" id="status" class="select chosen-rtl" name="status">
                                        <option value="pending" @selected(old(
                                        "status", $Payment->status) == "pending")> در انتظار بررسی</option>
                                        <option value="stop" @selected(old(
                                        "status", $Payment->status) == "stop")> متوقف شد</option>
                                        <option value="paid" @selected(old(
                                        "status", $Payment->status) == "paid")> پرداخت شد</option>
                                    </select>
                                </div>
                                {!! Form::label('status','وضعیت:',['class'=>'col-12']) !!}
                            </div>

                            <div class="mb-3">تایید شما به منزله تایید نهایی صورت وضعیت برای پرداخت نمی باشد.</div>

                            <button type="submit" class="submit-form-btn create-btn">بروزرسانی صورتحساب</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
