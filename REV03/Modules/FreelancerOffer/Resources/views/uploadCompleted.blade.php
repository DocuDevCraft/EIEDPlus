@extends('dashboard::layouts.dashboard.master')

@section('title','پیشنهاد فریلنسرها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">پیشنهاد فریلنسرها</span>
@endsection

@section('content')
    <section class="report-table">
        <div class="widget-block widget-item widget-style center no-item">
            <h2 style="color:#2b7a41; font-weight: bold">ارسال فایل لیست پیشنهادات با موفقیت انجام شد</h2>
            <p style="font-size:16px; line-height:1.8; margin-bottom: 40px">
                اکنون می‌توانید جهت بررسی و اعلام برنده، از طریق لینک زیر به بخش «لیست پیشنهادات» مراجعه کنید.
            </p>
            <a href="{{ route('freelancer.offer.list', request()->route('id')) }}" class="submit-form-btn" style="height: 50px; line-height: 50px; background: #08579a; width: auto; padding: 0 50px">انتخاب فریلنسر برنده</a>
        </div>
    </section>
@endsection
