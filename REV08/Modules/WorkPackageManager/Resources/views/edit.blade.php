@extends('dashboard::layouts.dashboard.master')

@section('title','مدیریت بسته کاری')

@section('lib')
    @if($WorkPackage->status == 'new' && $WorkPackage->published_at && $WorkPackage->published_at)
        <link href="{{ asset('/modules/dashboard/admin/plugins/persian-date/persian-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    @endif
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">مدیریت بسته کاری</span>
@endsection

@section('content')
    <section class="form-section">
        @canany(['isAdmin', 'isWorkPackageManager', 'isSectionManager'])
            <form action="{{ route('work-package.update', $WorkPackage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{ method_field('PUT') }}
                @endcanany

                <div class="row">
                    <div class="col-9">
                        {{-- مشحصات کلی --}}
                        <div class="widget-block widget-item widget-style">
                            <div class="heading-widget">
                                <div class="row">
                                    <div class="col-9">
                                        <span class="widget-title">مشخصات کلی</span>
                                    </div>
                                    <div class="col-3 left"></div>
                                </div>
                            </div>

                            <div class="widget-content widget-content-padding">
                                @csrf
                                <div class="form-group row no-gutters ">
                                    @if($errors->has('package_number'))
                                        <span class="col-12 message-show">{{ $errors->first('package_number') }}</span>
    @endif
    {{ Form::text('package_number',$WorkPackage->package_number,[ 'id'=>'package_number' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'شماره بسته کاری را وارد نمایید'])
    {{ Form::label('package_number','شماره بسته کاری:',['class'=>'col-12'])
</div>

<div class="form-group row no-gutters">
    @if($errors->has('title'))
        <span class="col-12 message-show">{{ $errors->first('title') }}</span>
    @endif
    {{ Form::text('title',$WorkPackage->title,[ 'id'=>'title' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'عنوان بسته کاری را وارد نمایید'])
    {{ Form::label('title','عنوان بسته کاری:',['class'=>'col-12'])
</div>

<div class="form-group">
    <div style="margin-bottom: 10px;">{{ Form::label('desc','شرح بسته کاری')
        <span class="required">(اختیاری)</span></div>
    <textarea class="field-style input-text" id="desc" name="desc" placeholder="شرح بسته کاری را وارد نمایید">{{ old('desc', $WorkPackage->desc) </textarea>
    @if($errors->has('desc'))
        <span class="message-show">{{ $errors->first('desc') }}</span>
    @endif
</div>

<div class="form-group">
    <div style="margin-bottom: 10px;">{{ Form::label('rules','نکات خاص')
        <span class="required">(اختیاری)</span></div>
    <textarea class="field-style input-text" id="rules" name="rules" placeholder="نکات خاص را وارد نمایید">{{ old('rules', $WorkPackage->rules) </textarea>
    @if($errors->has('rules'))
        <span class="message-show">{{ $errors->first('rules') }}</span>
    @endif
</div>
</div>
</div>

{{-- اطلاعات بسته کاری --}}
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<span class="widget-title">اطلاعات بسته کاری</span>
</div>

<div class="widget-content widget-content-padding">
<div class="row">
    {{-- نفر ساعت --}}
    <div class="col-4 mb-5">
        <div class="form-group row no-gutters ">
            @if($errors->has('man_hour'))
                <span class="col-12 message-show">{{ $errors->first('man_hour') }}</span>
            @endif
            {{ Form::text('man_hour',$WorkPackage->man_hour,[ 'id'=>'man_hour' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'نفر ساعت را وارد نمایید'])
            {{ Form::label('man_hour','نفر ساعت:',['class'=>'col-12'])
        </div>
    </div>

    {{-- حداقل نمره قبولی --}}
    <div class="col-4 mb-5">
        <div class="form-group row no-gutters ">
            @if($errors->has('minimum_technical_grade'))
                <span class="col-12 message-show">{{ $errors->first('minimum_technical_grade') }}</span>
            @endif
            {{ Form::text('minimum_technical_grade',$WorkPackage->minimum_technical_grade,[ 'id'=>'minimum_technical_grade' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'حداقل نمره قبولی را وارد نمایید'])
            {{ Form::label('minimum_technical_grade','حداقل نمره قبولی:',['class'=>'col-12'])
        </div>
    </div>

    {{-- ارشدیت --}}
    <div class="col-4 mb-5">
        <div class="form-group row no-gutters ">
            @if($errors->has('seniority'))
                <span class="col-12 message-show">{{ $errors->first('seniority') }}</span>
            @endif
            <div class="col-12 field-style">
                <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="seniority" id="seniority">
                    <option></option>
                    <option value="senior" @selected(old(
                    'seniority', $WorkPackage->seniority) == 'senior')>Senior
                    </option>
                    <option value="junior" @selected(old(
                    'seniority', $WorkPackage->seniority) == 'junior')>Junior
                    </option>
                </select>
            </div>

            {{ Form::label('seniority','ارشدیت',['class'=>'col-12'])
        </div>
    </div>


    {{-- نوع زمان بسته کاری --}}
    <div class="col-4 mb-5">
        <div class="form-group row no-gutters ">
            @if($errors->has('package_time_type'))
                <span class="col-12 message-show">{{ $errors->first('package_time_type') }}</span>
            @endif
            <div class="col-12 field-style">
                <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="package_time_type" id="package_time_type">
                    <option></option>
                    <option value="ثــابت" @selected(old(
                    'package_time_type', $WorkPackage->package_time_type) == 'ثــابت')>ثــابت
                    </option>
                    <option value="مناقصه" @selected(old(
                    'package_time_type', $WorkPackage->package_time_type) == 'مناقصه')>مناقصه
                    </option>
                </select>
            </div>

            {{ Form::label('package_time_type','نوع زمان بسته کاری',['class'=>'col-12'])
        </div>
    </div>

    {{-- نوع قیمت بسته کاری --}}
    <div class="col-4 mb-5">
        <div class="form-group row no-gutters ">
            @if($errors->has('package_price_type'))
                <span class="col-12 message-show">{{ $errors->first('package_price_type') }}</span>
            @endif
            <div class="col-12 field-style">
                <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="package_price_type" id="package_price_type">
                    <option></option>
                    <option value="ثــابت" @selected(old(
                    'package_price_type', $WorkPackage->package_price_type) == 'ثــابت')>ثــابت
                    </option>
                    <option value="مناقصه" @selected(old(
                    'package_price_type', $WorkPackage->package_price_type) == 'مناقصه')>مناقصه
                    </option>
                </select>
            </div>

            {{ Form::label('package_price_type','نوع قیمت بسته کاری',['class'=>'col-12'])
        </div>
    </div>

    {{-- زمان پیشنهادی کارفرما --}}
    <div class="col-4 mb-5">
        <div class="form-group row no-gutters ">
            @if($errors->has('recommend_time'))
                <span class="col-12 message-show">{{ $errors->first('recommend_time') }}</span>
            @endif
            {{ Form::text('recommend_time',$WorkPackage->recommend_time,[ 'id'=>'recommend_time' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'زمان پیشنهادی کارفرما را وارد نمایید'])
            {{ Form::label('recommend_time','زمان پیشنهادی کارفرما (روز):',['class'=>'col-12'])
        </div>
    </div>

    {{-- قیمت پیشنهادی کارفرما --}}
    <div class="col-4">
        <div class="form-group row no-gutters ">
            @if($errors->has('recommend_price'))
                <span class="col-12 message-show">{{ $errors->first('recommend_price') }}</span>
            @endif
            {{ Form::text('recommend_price',$WorkPackage->recommend_price,[ 'id'=>'recommend_price' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'قیمت پیشنهادی کارفرما را وارد نمایید'])
            {{ Form::label('recommend_price','قیمت پیشنهادی کارفرما (تومان):',['class'=>'col-12'])
        </div>
    </div>

    {{-- فرمول برنده بسته کاری --}}
    <div class="col-4">
        <div class="form-group row no-gutters ">
            @if($errors->has('winning_formula'))
                <span class="col-12 message-show">{{ $errors->first('winning_formula') }}</span>
            @endif
            <div class="col-12 field-style">
                <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="winning_formula" id="package_price_type">
                    <option></option>
                    <option value="lowest_price" @selected(old(
                    'winning_formula', $WorkPackage->winning_formula) == 'lowest_price')>کمترین قیمت
                    </option>
                    <option value="less_time" @selected(old(
                    'winning_formula', $WorkPackage->winning_formula) == 'less_time')>کمترین زمان
                    </option>
                    <option value="grade" @selected(old(
                    'winning_formula', $WorkPackage->winning_formula) == 'grade')>بیشترین نمره فنی
                    </option>
                </select>
            </div>

            {{ Form::label('winning_formula','فرمول برنده بسته کاری',['class'=>'col-12'])
        </div>
    </div>

    {{-- حداقل تعداد پیشنهاد --}}
    <div class="col-4">
        <div class="form-group row no-gutters ">
            @if($errors->has('minimum_offers'))
                <span class="col-12 message-show">{{ $errors->first('minimum_offers') }}</span>
            @endif
            {{ Form::text('minimum_offers',$WorkPackage->minimum_offers,[ 'id'=>'minimum_offers' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'حداقل تعداد پیشنهاد را وارد نمایید'])
            {{ Form::label('minimum_offers','حداقل تعداد پیشنهاد:',['class'=>'col-12'])
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-3">
<a href='{{ url('dashboard/work-package-manager-chat/' . $WorkPackage->id) }}' class="submit-form-btn mb-2 position-relative" style="background-color: #00b1f2">فرایند تایید بسته کاری <span class="offer-badge num-fa" style="top: 50%; margin-top: -10px; border-radius: 5px">{{ \Modules\WorkPackageManager\Entities\WorkPackageManagerChat::where('work_package_id', $WorkPackage->id)->where('status', 'new')->count() }}</span></a>

<a href='{{ url('dashboard/work-package-task-manager/' . $WorkPackage->id) }}' class="submit-form-btn mb-2" style="background-color: #f25f00">مدیریت بلاک های بسته کاری</a>
@if($WorkPackage->status === 'activated')
<a href='{{ url('dashboard/work-package-task/' . $WorkPackage->id) }}' class="submit-form-btn mb-2" style="background-color: #61687c">فرآیند پیشرفت بسته کاری</a>
@endif
@cannot('isWorkPackageManagerOnly')
<a href='{{ url('dashboard/freelancer-offer/' . $WorkPackage->id) }}' class="submit-form-btn mb-2" style="background-color: #9900f2">پیشنهاد فریلنسرها</a>
@endcannot
<a href='{{ url('dashboard/work-package-chat/' . $WorkPackage->id) }}' class="submit-form-btn mb-4 position-relative" style="background-color: #00b1f2">سوالات عمومی فریلنسرها<span class="offer-badge num-fa" style="top: 50%; margin-top: -10px; border-radius: 5px">{{ $WorkPackageChatList }}</span></a>

{{-- Publish Options --}}
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<span class="widget-title">ثبت اطلاعات</span>
</div>

<div class="widget-content widget-content-padding widget-content-padding-side">
{{-- Section --}}
<div class="form-group row no-gutters">
    @if($errors->has('section'))
        <span class="col-12 message-show">{{ $errors->first('section') }}</span>
    @endif
    {{ Form::label('section','بخش',['class'=>'col-12'])
    <select data-placeholder="بخش را انتخاب نمایید..." id="section" class="select chosen-rtl num-fa" name="section_id">
        <option value="">بخش را انتخاب نمایید...</option>
        @forelse($Section as $item)
            <option value="{{$item->id}}" @selected(old('section_id', $WorkPackage->section_id) == $item->id)>{{ $item->title }}</option>
        @empty
        @endforelse
    </select>
</div>

{{-- Subsection --}}
<div class="form-group row no-gutters subsection-block d-none">
    @if($errors->has('subsection'))
        <span class="col-12 message-show">{{ $errors->first('subsection') }}</span>
    @endif
    {{ Form::label('subsection','زیر بخش',['class'=>'col-12'])
    <select data-placeholder="زیر بخش را انتخاب نمایید..." id="subsection" class="select chosen-rtl num-fa" name="subsection_id"></select>
</div>

{{-- Division --}}
<div class="form-group row no-gutters division-block d-none">
    @if($errors->has('division'))
        <span class="col-12 message-show">{{ $errors->first('division') }}</span>
    @endif
    <div class="col-12" style="margin-bottom: 10px;">{{ Form::label('division','قسمت') <span class="required align-middle">(اختیاری)</span></div>
    <select data-placeholder="قسمت را انتخاب نمایید..." id="division" class="select chosen-rtl num-fa" name="division_id">
    </select>
</div>

{{-- WorkPackage Type --}}
<div class="form-group row no-gutters">
    @if($errors->has('work_package_type'))
        <span class="col-12 message-show">{{ $errors->first('work_package_type') }}</span>
    @endif
    {{ Form::label('work_package_type','نوع بسته کاری',['class'=>'col-12'])
    <select data-placeholder="اندازه بسته کاری را انتخاب نمایید..." id="work_package_type" class="select chosen-rtl num-fa" name="work_package_type">
        <option value="public" @selected(old('work_package_type', $WorkPackage->work_package_type) == "public")>انتشار عمومی</option>
        <option value="hourly_contract" @selected(old('work_package_type', $WorkPackage->work_package_type) == "hourly_contract")>قرارداد نفر/ساعت</option>
    </select>
</div>

{{-- Select Freelancer --}}
<div class="form-group row no-gutters" id="freelancer_select_wrapper">
    <div class="col-12 field-style">
        <select multiple data-placeholder="فریلنسرها را انتخاب کنید..." class="form-control chosen-rtl select" name="freelancer[]" id="freelancer"></select>
    </div>
    {{ Form::label('freelancer', 'فریلنسرهای قرداداد ساعت کاری:', ['class' => 'col-12'])
</div>

{{-- Select public Freelancer --}}
<div class="form-group row no-gutters" id="normal_freelancer_select_wrapper">
    <div class="col-12 field-style">
        <select multiple data-placeholder="فریلنسر را انتخاب نمایید..." id="normal_freelancer" class="form-control select chosen-rtl num-fa" name="normal_freelancer[]"></select>
    </div>
    {{ Form::label('normal_freelancer', 'فریلنسرها:', ['class' => 'col-12'])
</div>

{{-- بازه زمانی ارائه پیشنهادات --}}
<div class="form-group row no-gutters ">
    @if($WorkPackage->status == 'new' && $WorkPackage->published_at && $WorkPackage->published_at)
        <div class="mb-4">
            <label>زمان شروع ارائه پیشنهادات</label>
            <input name="published_at_date_picker" required autocomplete="off" readonly id="published_at_date_picker" type="text" class="col-12 field-style input-text num-fa" placeholder="زمان شروع پیشنهادات">
            <input id="published_at" required type="hidden" name="published_at">
        </div>

        <div class="mb-4">
            <label>زمان پایان ارائه پیشنهادات</label>
            <input name="offer_time" required autocomplete="off" readonly id="offer_time_date_picker" type="text" class="col-12 field-style input-text num-fa" placeholder="زمان پایان ارائه پیشنهادات">
            <input id="offer_time_date" required type="hidden" name="offer_time_date">
        </div>
    @else

        @if($errors->has('offer_time'))
            <span class="col-12 message-show">{{ $errors->first('offer_time') }}</span>
        @endif
        <div style="margin-bottom: 10px;">{{ Form::label('offer_time','بازه زمانی ارائه پیشنهادات')
            <span class="required">(روز)</span></div>

        {{ Form::text('offer_time',$WorkPackage->offer_time,[ 'id'=>'offer_time' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'بازه زمانی ارائه پیشنهادات را وارد نمایید'])
    @endif
</div>

{{-- هماهنگ کننده --}}
<div class="form-group row no-gutters ">
    @if($errors->has('coordinator'))
        <span class="col-12 message-show">{{ $errors->first('coordinator') }}</span>
    @endif
    {{ Form::text('coordinator',$WorkPackage->coordinator,[ 'id'=>'coordinator' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'هماهنگ کننده را وارد نمایید'])
    {{ Form::label('coordinator','هماهنگ کننده:',['class'=>'col-12'])
</div>

@canany(['isAdmin', 'isWorkPackageManager', 'isSectionManager'])
    <button type="submit" class="submit-form-btn create-btn">بروزرسانی بسته کاری</button>
@endcanany
</div>
</div>

{{-- جرایم تاخیرات --}}
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<span class="widget-title">جرایم تاخیرات</span>
</div>

<div class="widget-content widget-content-padding widget-content-padding-side">
{{-- جرایم تاخیرات هر روز تاخیر --}}
<div class="form-group row no-gutters">
    @if($errors->has('daily_fine'))
        <span class="col-12 message-show">{{ $errors->first('daily_fine') }}</span>
    @endif
    {{ Form::text('daily_fine',$WorkPackage->daily_fine,[ 'id'=>'daily_fine' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'جرایم تاخیرات هر روز تاخیر را وارد نمایید'])
    {{ Form::label('daily_fine','جرایم تاخیرات هر روز تاخیر:',['class'=>'col-12'])
</div>

{{-- جرایم تاخیرات پس از گذشت n روز --}}
<div class="form-group row no-gutters">
    @if($errors->has('fine_after_day'))
        <span class="col-12 message-show">{{ $errors->first('fine_after_day') }}</span>
    @endif
    {{ Form::text('fine_after_day',$WorkPackage->fine_after_day,[ 'id'=>'fine_after_day' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'جرایم تاخیرات پس از گذشت n روز را وارد نمایید'])
    {{ Form::label('fine_after_day','جرایم تاخیرات پس از گذشت n روز:',['class'=>'col-12'])
</div>

{{-- مبلغ جرایم تاخیرات پس از گذشت n روز --}}
<div class="form-group row no-gutters">
    @if($errors->has('fine_after_price'))
        <span class="col-12 message-show">{{ $errors->first('fine_after_price') }}</span>
    @endif
    {{ Form::text('fine_after_price',$WorkPackage->fine_after_price,[ 'id'=>'fine_after_price' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'مبلغ جرایم تاخیرات پس از گذشت n روز را وارد نمایید'])
    {{ Form::label('fine_after_price','مبلغ جرایم تاخیرات پس از گذشت n روز:',['class'=>'col-12'])
</div>

{{-- نمره منفی پس از گذشت n روز --}}
<div class="form-group row no-gutters">
    @if($errors->has('fine_after_negative'))
        <span class="col-12 message-show">{{ $errors->first('fine_after_negative') }}</span>
    @endif
    {{ Form::text('fine_after_negative',$WorkPackage->fine_after_negative,[ 'id'=>'fine_after_negative' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'نمره منفی پس از گذشت n روز را وارد نمایید'])
    {{ Form::label('fine_after_negative','نمره منفی پس از گذشت n روز:',['class'=>'col-12'])
</div>
</div>
</div>

{{-- مستندات بسته کاری --}}
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<span class="widget-title">مستندات بسته کاری</span>
</div>

<div class="widget-content widget-content-padding widget-content-padding-side">
{{-- فایل عمومی بسته کاری --}}
<div class="form-group row no-gutters border-bottom pb-3">
    @if($errors->has('attachment_for_all'))
        <span class="col-12 message-show">{{ $errors->first('attachment_for_all') }}</span>
    @endif
    {{ Form::label('attachment_for_all','فایل عمومی بسته کاری:',['class'=>'col-12 mb-1'])
    <input type="file" value="{{ old('attachment_for_all') }}" name="attachment_for_all">
    @if(isset($WorkPackage->attachment_for_all))
        <a href="{{ $WorkPackage->attachment_for_all['path'] }}" download="{{ $WorkPackage->attachment_for_all['file_name'] }}" class="mt-2 font-weight-bold">دانلود فایل پیوست</a>
    @endif
</div>

{{-- فایل مرتبط با برنده بسته کاری --}}
<div class="form-group row no-gutters ">
    @if($errors->has('attachment_for_winner'))
        <span class="col-12 message-show">{{ $errors->first('attachment_for_winner') }}</span>
    @endif
    {{ Form::label('attachment_for_winner','فایل مرتبط با برنده بسته کاری:',['class'=>'col-12 mb-1'])
    {{ Form::file('attachment_for_winner',null,[ 'id'=>'attachment_for_winner' , 'class'=>'col-12'])
    @if(isset($WorkPackage->attachment_for_winner))
        <a href="{{ $WorkPackage->attachment_for_winner['path'] }}" download="{{ $WorkPackage->attachment_for_winner['file_name'] }}" class="mt-2 font-weight-bold">دانلود فایل پیوست</a>
    @endif
</div>

{{--                            --}}{{-- فایل مرتبط با برنده بسته کاری --}}
{{--                            <div class="form-group row no-gutters">--}}
{{--                                <div style="margin-bottom: 10px;">{{ Form::label('rules','قوانین خاص') --}}
{{--                                    <span class="required">(اختیاری)</span></div>--}}
{{--                                <textarea style="min-height: 100px" class="field-style input-text" id="rules" name="rules" placeholder="قوانین خاص را وارد نمایید">{{ old('rules', $WorkPackage->rules) }}</textarea>--}}
{{--                                @if($errors->has('rules'))--}}
{{--                                    <span class="message-show">{{ $errors->first('rules') }}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
</div>
</div>

{{-- Tags --}}
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<span class="widget-title">برچسب ها</span>
</div>

<div class="widget-content widget-content-padding widget-content-padding-side">
<div class="form-group">
    <div class="answer-question text-field-repeater">
        <div class="field-list" id="repeat_list"></div>
        <div class="add-field center" id="addRepeatItem">
            <span class="icon-plus"></span>افزودن برچسب
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
@canany(['isAdmin', 'isWorkPackageManager'])
</form>
@endcanany
</section>
@endsection



@section('footer')
@if($WorkPackage->status == 'new' && $WorkPackage->published_at && $WorkPackage->published_at)
{{-- Persian Date --}}
<script src="{{ asset('/modules/dashboard/admin/plugins/persian-date/persian-date.min.js') }}"></script>
<script src="{{ asset('/modules/dashboard/admin/plugins/persian-date/persian-datepicker.min.js') }}"></script>

{{-- Date Picker --}}
<script>
const fromOptions = {
observer: true,
initialValueType: 'persian',
altFormat: 'unix',
inline: false,
format: "dddd YYYY/MM/DD",
viewMode: "month",
initialValue: false,
minDate: <?php echo time() * 1000 ?>,
onSelect: function (unix) {
// unix اینجا بر حسب میلی‌ثانیه است
const d = new Date(unix);
d.setHours(0, 0, 0, 0);
const sec = Math.floor(d.getTime() / 1000);
$('#published_at').val(sec);

rebuildToPickerWithMin(unix);
},
"autoClose": false,
"position": "auto",
"onlyTimePicker": false,
"onlySelectOnDate": false,
"calendarType": "persian",
"inputDelay": "0",
"calendar": {
"persian": {
"locale": "fa",
"showHint": true,
"leapYearMode": "astronomical"
},
"gregorian": {
"locale": "en",
"showHint": false
}
},
"navigator": {
"enabled": true,
"scroll": {
"enabled": false
},
"text": {
"btnNextText": "",
"btnPrevText": ""
}
},
"toolbox": {
"enabled": true,
"calendarSwitch": {
"enabled": false,
"format": "MMMM"
},
"todayButton": {
"enabled": true,
"text": {
"fa": "امروز",
"en": "Today"
}
},
"submitButton": {
"enabled": true,
"text": {
"fa": "تایید",
"en": "Submit"
}
},
"text": {
"btnToday": "امروز"
}
},
"dayPicker": {
"enabled": true,
"titleFormat": "YYYY MMMM"
},
"monthPicker": {
"enabled": true,
"titleFormat": "YYYY"
},
"yearPicker": {
"enabled": true,
"titleFormat": "YYYY"
},
"responsive": true
};

const toOptions = {
observer: true,
initialValueType: 'persian',
altFormat: 'unix',
inline: false,
format: "dddd YYYY/MM/DD",
viewMode: "month",
initialValue: false,
minDate: null,
onSelect: function (unix) {
const d = new Date(unix);
d.setHours(23, 59, 0, 0);
const sec = Math.floor(d.getTime() / 1000);
$('#offer_time_date').val(sec);

rebuildFromPickerWithMax(unix);
},
"autoClose": false,
"position": "auto",
"onlyTimePicker": false,
"onlySelectOnDate": false,
"calendarType": "persian",
"inputDelay": "0",
"calendar": {
"persian": {
"locale": "fa",
"showHint": true,
"leapYearMode": "astronomical"
},
"gregorian": {
"locale": "en",
"showHint": false
}
},
"navigator": {
"enabled": true,
"scroll": {
"enabled": false
},
"text": {
"btnNextText": "",
"btnPrevText": ""
}
},
"toolbox": {
"enabled": true,
"calendarSwitch": {
"enabled": false,
"format": "MMMM"
},
"todayButton": {
"enabled": true,
"text": {
"fa": "امروز",
"en": "Today"
}
},
"submitButton": {
"enabled": true,
"text": {
"fa": "تایید",
"en": "Submit"
}
},
"text": {
"btnToday": "امروز"
}
},
"dayPicker": {
"enabled": true,
"titleFormat": "YYYY MMMM"
},
"monthPicker": {
"enabled": true,
"titleFormat": "YYYY"
},
"yearPicker": {
"enabled": true,
"titleFormat": "YYYY"
},
"responsive": true
};

function initPickers() {
try {
$('#published_at_date_picker').persianDatepicker('destroy');
} catch (e) {
}
try {
$('#offer_time_date_picker').persianDatepicker('destroy');
} catch (e) {
}

const a = $('#published_at_date_picker').persianDatepicker(fromOptions);
const b = $('#offer_time_date_picker').persianDatepicker(toOptions);

window.fromPicker = a && a.setDate ? a : a;
window.toPicker = b && b.setDate ? b : b;
}

function rebuildToPickerWithMin(minMs) {
let current = null;
try {
current = window.toPicker.getState().selected.unixDate;
} catch (e) {
try {
current = $('#offer_time_date_picker').persianDatepicker('getState').selected.unixDate;
} catch (e) {
current = null;
}
}

try {
$('#offer_time_date_picker').persianDatepicker('destroy');
} catch (e) {
}
toOptions.minDate = minMs;
const inst = $('#offer_time_date_picker').persianDatepicker(toOptions);
window.toPicker = inst && inst.setDate ? inst : inst;

if (current) {
try {
window.toPicker.setDate(current);
} catch (e) {
try {
$('#offer_time_date_picker').persianDatepicker('setDate', current);
} catch (e2) {
}
}
$('#offer_time_date').val(Math.floor(current / 1000));
}
}

function rebuildFromPickerWithMax(maxMs) {
let current = null;
try {
current = window.fromPicker.getState().selected.unixDate;
} catch (e) {
try {
current = $('#published_at_date_picker').persianDatepicker('getState').selected.unixDate;
} catch (e) {
current = null;
}
}

try {
$('#published_at_date_picker').persianDatepicker('destroy');
} catch (e) {
}
fromOptions.maxDate = maxMs;
const inst = $('#published_at_date_picker').persianDatepicker(fromOptions);
window.fromPicker = inst && inst.setDate ? inst : inst;

if (current) {
try {
window.fromPicker.setDate(current);
} catch (e) {
try {
$('#published_at_date_picker').persianDatepicker('setDate', current);
} catch (e2) {
}
}
$('#published_at').val(Math.floor(current / 1000));
}
}

$(document).ready(function () {
window.fromPicker = $('#published_at_date_picker').persianDatepicker(fromOptions);
window.toPicker = $('#offer_time_date_picker').persianDatepicker(toOptions);

// --- مقداردهی اولیه published_at ---
@if(!empty($WorkPackage->published_at))
let publishedSec = {{ $WorkPackage->published_at }};
let publishedMs = publishedSec * 1000;
try {
window.fromPicker.setDate(publishedMs);
} catch (e) {
$('#published_at_date_picker').persianDatepicker('setDate', publishedMs);
}
$('#published_at').val(publishedSec);

rebuildToPickerWithMin(publishedMs);
@endif

@if(!empty($WorkPackage->offer_time_date))
let offerSec = {{ $WorkPackage->offer_time_date }};
let offerMs = offerSec * 1000;
try {
window.toPicker.setDate(offerMs);
} catch (e) {
$('#offer_time_date_picker').persianDatepicker('setDate', offerMs);
}
$('#offer_time_date').val(offerSec);

rebuildFromPickerWithMax(offerMs);

@if(!empty($WorkPackage->published_at))
if (publishedSec > offerSec) {
try {
window.toPicker.setDate(publishedMs);
} catch (e) {
$('#offer_time_date_picker').persianDatepicker('setDate', publishedMs);
}
$('#offer_time_date').val(publishedSec);

rebuildFromPickerWithMax(publishedMs);
}
@endif
@endif
});

</script>
@endif

{{-- Users Check --}}
<script>
$(function () {
const $normalFreelancerWrapper = $('#normal_freelancer_select_wrapper');
const $freelancerWrapper = $('#freelancer_select_wrapper');
const $typeSelect = $('#work_package_type');

const toggleFreelancerSelect = () => {
if ($typeSelect.val() === 'hourly_contract') {
$normalFreelancerWrapper.slideUp();
$freelancerWrapper.slideDown();
} else {
$normalFreelancerWrapper.slideDown();
$freelancerWrapper.slideUp();
}
};

$typeSelect.on('change', toggleFreelancerSelect);
toggleFreelancerSelect();
});
</script>

<script>
@php
$selectedNormalFreelancers = old('normal_freelancer', isset($WorkPackage) ? $WorkPackage->freelancers->pluck('id')->toArray() : []);
$selectedFreelancers = old('freelancer', isset($WorkPackage) ? $WorkPackage->freelancers->pluck('id')->toArray() : []);
@endphp

const $subsection = $('#subsection');
const $normal_freelancer = $('#normal_freelancer');
const $freelancer = $('#freelancer');
$subsection.chosen();
$normal_freelancer.chosen();
$freelancer.chosen();
const section_id = '{{ old('section_id') ? old('section_id') : $WorkPackage->section_id}}';
const subsection_id = '{{old('subsection_id') ? old('subsection_id') : $WorkPackage->subsection_id}}';
const selectedNormalFreelancersList = @json($selectedNormalFreelancers);
const selectedFreelancersList = @json($selectedFreelancers);

if (section_id) {
$('.subsection-block').addClass('d-none');
$("#subsection").find('option').remove();
$("#division").find('option').remove();
var url = "{{ url('/dashboard/subsection/check') }}/" + section_id;
$.ajax({
url: url,
type: "POST",
data: {_token: '{{ csrf_token() }}'},
cache: false,
dataType: 'json',
success: function (dataResult) {
$('.subsection-block').removeClass('d-none');
var newOptions = '<option>زیر بخش را انتخاب کنید...</option>';
dataResult.subsection.forEach(function (item) {
var selected = parseInt(subsection_id) == item.id ? 'selected' : null;
newOptions += '<option ' + selected + ' value=' + item.id + '>' + item.title + '</option>';
});
$subsection.html(newOptions);
$subsection.trigger("chosen:updated");

var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.normally_freelancer.forEach(function (item) {
const selectedNormallyFreelancer = selectedNormalFreelancersList.includes(item.id) ? 'selected' : '';
newOptionsNormallyFreelancer += '<option ' + selectedNormallyFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$normal_freelancer.html(newOptionsNormallyFreelancer);
$normal_freelancer.trigger("chosen:updated");
});

var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.freelancers.forEach(function (item) {
const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$freelancer.html(newOptionsFreelancer);
$freelancer.trigger("chosen:updated");
});
}
});
}
$('#section').on('change', function (e, prom) {
e.preventDefault();
$('.subsection-block').addClass('d-none');
$("#subsection").find('option').remove();
$("#division").find('option').remove();
var url = "{{ url('/dashboard/subsection/check') }}/" + prom.selected;
$.ajax({
url: url,
type: "POST",
data: {_token: '{{ csrf_token() }}'},
cache: false,
dataType: 'json',
success: function (dataResult) {
$('.subsection-block').removeClass('d-none');
var newOptions = '<option>زیر بخش را انتخاب کنید...</option>';
dataResult.subsection.forEach(function (item) {
newOptions += '<option  value=' + item.id + '>' + item.title + '</option>';
});
$subsection.html(newOptions);
$subsection.trigger("chosen:updated");

var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.normally_freelancer.forEach(function (item) {
newOptionsNormallyFreelancer += '<option  value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$normal_freelancer.html(newOptionsNormallyFreelancer);
$normal_freelancer.trigger("chosen:updated");
});

var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.freelancers.forEach(function (item) {
const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$freelancer.html(newOptionsFreelancer);
$freelancer.trigger("chosen:updated");
});
}
});
});

{{-- Division Check --}}
var $division = $('#division');
$division.chosen();
const division_id = '{{old('division_id') ? old('division_id') : $WorkPackage->division_id}}';
if (subsection_id) {
$('.division-block').addClass('d-none');
$("#division").find('option').remove();
var url = "{{ url('/dashboard/division/check') }}/" + subsection_id;
$.ajax({
url: url,
type: "POST",
data: {_token: '{{ csrf_token() }}'},
cache: false,
dataType: 'json',
success: function (dataResult) {
if (dataResult.division.length) {
$('.division-block').removeClass('d-none');
var newOptions = '<option>قسمت را انتخاب کنید...</option>';
dataResult.division.forEach(function (item) {
var selected = parseInt(division_id) == item.id ? 'selected' : null;
newOptions += '<option ' + selected + ' value=' + item.id + '>' + item.title + '</option>';
});
$division.html(newOptions);
$division.trigger("chosen:updated");
}

var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.normally_freelancer.forEach(function (item) {
const selectedNormallyFreelancer = selectedNormalFreelancersList.includes(item.id) ? 'selected' : '';
newOptionsNormallyFreelancer += '<option ' + selectedNormallyFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$normal_freelancer.html(newOptionsNormallyFreelancer);
$normal_freelancer.trigger("chosen:updated");
});

var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.freelancers.forEach(function (item) {
const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$freelancer.html(newOptionsFreelancer);
$freelancer.trigger("chosen:updated");
});
}
});
}
$('#subsection').on('change', function (e, prom) {
e.preventDefault();
$('.division-block').addClass('d-none');
$("#division").find('option').remove();
var url = "{{ url('/dashboard/division/check') }}/" + prom.selected;
$.ajax({
url: url,
type: "POST",
data: {
_token: '{{ csrf_token() }}'
},
cache: false,
dataType: 'json',
success: function (dataResult) {
console.log(dataResult)
if (dataResult.division.length) {
$('.division-block').removeClass('d-none');
var newOptions = '<option>قسمت را انتخاب کنید...</option>';
dataResult.division.forEach(function (item) {
newOptions += '<option value=' + item.id + '>' + item.title + '</option>';
});
$division.html(newOptions);
$division.trigger("chosen:updated");
}

var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.normally_freelancer.forEach(function (item) {
newOptionsNormallyFreelancer += '<option  value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$normal_freelancer.html(newOptionsNormallyFreelancer);
$normal_freelancer.trigger("chosen:updated");
});

var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
dataResult.freelancers.forEach(function (item) {
const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
$freelancer.html(newOptionsFreelancer);
$freelancer.trigger("chosen:updated");
});
}
});
});
</script>

{{-- CKEditor Config --}}
{{--    <script type="text/javascript">--}}
{{--        CKEDITOR.replace('desc', {--}}
{{--            language: 'fa',--}}
{{--            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['path' => 'work_package','_token' => csrf_token()])}}",--}}
{{--            filebrowserUploadMethod: 'form',--}}
{{--            width: '100%',--}}
{{--            height: '200',--}}
{{--            uiColor: '#fdfdfd',--}}
{{--        });--}}
{{--        CKEDITOR.replace('rules', {--}}
{{--            language: 'fa',--}}
{{--            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['path' => 'work_package','_token' => csrf_token()])}}",--}}
{{--            filebrowserUploadMethod: 'form',--}}
{{--            width: '100%',--}}
{{--            height: '200',--}}
{{--            uiColor: '#fdfdfd',--}}
{{--        });--}}
{{--    </script>--}}

{{-- Item Repeater --}}
<script>
var currentcheckcontent, lastcheckcontent;
jQuery(document).ready(function () {
var i = 0;
jQuery("#addRepeatItem").click(function () {
i += 1;
jQuery("#repeat_list").append("" +
"<div id='field-repeat-item-" + i + "'>" +
"<div class='text-field'>" +
"<input placeholder='برچسب را وارد نمایید...' class='field-style input-text' type=\"text\" name=\"tag[" + i + "]\"> " +
"<span class='delete-row icon-close' onclick='delete_item(" + i + ")'>" +
"<span class='zmdi zmdi-close-circle'></span>" +
"</span>" +
"</div>" +
"</div>" +
"");
return false;
});

});

function delete_item($id) {
$('#field-repeat-item-' + $id).remove();
}
</script>
@endsection
