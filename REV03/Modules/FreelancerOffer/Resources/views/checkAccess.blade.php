@extends('dashboard::layouts.dashboard.master')

@section('title','پیشنهاد فریلنسرها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">پیشنهاد فریلنسرها</span>
@endsection

@section('content')
    <section class="report-table">
        @if($AccessStatus === 'not_yet')
            <div class="widget-block widget-item widget-style center no-item">
                <h2>هنوز زمان مشاهده قیمت ها نرسید است</h2>
                <p style="font-size: 16px">پس از اتمام زمان ارسال پیشنهادات، جهت مشاهده لیست پیشنهادات به این صفحه مراجعه نمایید.</p>
            </div>
        @elseif($AccessStatus === 'code_not_found')
            <div class="widget-block widget-item widget-style no-item">
                <h2 style="font-weight: bold">درخواست دسترسی به لیست پیشنهادات:</h2>
                <ol style="font-size: 16px; line-height: 40px" class="num-fa">
                    <li>جهت دسترسی به لیست پیشنهادات، ابتدا روی دکمه «ارسال کد» کلیک کنید.</li>
                    <li>پس از کلیک، یک کد به واحد امور مهندسی ارسال می‌شود.</li>
                    <li>با دریافت این کد از امور مهندسی، می‌توانید آن را در همین صفحه وارد کرده و به لیست پیشنهادات دسترسی پیدا کنید.</li>
                </ol>

                <hr>

                <form action="{{ route('freelancer.offer.sendOTP', request()->route('id')) }}" method="post">
                    @csrf
                    <button type="submit" class="submit-form-btn" style="height: 50px; background: #08579a">ارسال کد دسترسی OTP به امور مهندسی</button>
                </form>
            </div>
        @elseif($AccessStatus === 'submit-otp')
            <div class="widget-block widget-item widget-style center no-item">
                <h2 style="font-weight: bold">کد دسترسی را وارد نمایید</h2>
                <p style="font-size: 16px; margin-bottom: 30px">این کد با نام شما برای امور مهندسی ارسال گردیده است</p>
                <form action="{{ route('freelancer.offer.submitOTP', request()->route('id')) }}" method="post" class="center">
                    @csrf
                    <div class="form-group row no-gutters">
                        <div class="otp-field">
                            {!! Form::text('otp',null,[ 'id'=>'first_name', 'maxlength' => '5' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'کد ۵ رقمی دسترسی را وارد نمایید']) !!}
                            @if($errors->has('otp'))
                                <span class="col-12 message-show">{{ $errors->first('otp') }}</span>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="submit-form-btn" style="height: 50px; background: #0b5b8d">بررسی و مشاهده لیست پیشنهادات</button>
                </form>
            </div>

            <form action="{{ route('freelancer.offer.sendOTP', request()->route('id')) }}" method="post" class="center">
                @csrf
                <button type="submit" class="table-hover" style="border: none; background: transparent; margin-top: 6px; color: #525252; cursor: pointer">ارسال مجدد کد دسترسی OTP به امور مهندسی</button>
            </form>
        @endif
    </section>
@endsection
