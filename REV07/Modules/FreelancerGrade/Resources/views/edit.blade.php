@extends('dashboard::layouts.dashboard.master')

@section('title')
    اطلاعات فنی
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/users/images/icons/user.gif') }}"></span>
    <span class="text">اطلاعات فنی</span>
@endsection

@section('content')
    <section class="form-section">
        <div class="row">
            <div class="col-9">
                {{-- اطلاعات حساب کاربری --}}
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">اطلاعات فریلنسر</span>
                    </div>

                    <div class="widget-content widget-content-padding">
                        <div class="row">
                            {{-- نام و نام خانوادگی --}}
                            <div class="item-info col-4 mb-3">
                                <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                    <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">نام و نام خانوادگی</div>
                                    <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->users->first_name . ' ' . $Freelancer->users->last_name }}</div>
                                </div>
                            </div>
                            {{-- کد ملی --}}
                            @isset($Freelancer->meli_code)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">کد ملی</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->meli_code }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- شماره شناسنامه --}}
                            @isset($Freelancer->shenasnameh)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">شماره شناسنامه</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->shenasnameh }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- محل صدور --}}
                            @isset($Freelancer->mahale_sodoor)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">محل صدور</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->mahale_sodoor }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- سربازی --}}
                            @isset($Freelancer->sarbazi)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">سربازی</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->sarbazi }} @isset($Freelancer->sarbazi_file)
                                                <a target="_blank" style="float: left; font-weight: 600; font-size: 12px" href="{{ \App\Http\Controllers\HomeController::GetFilePath($Freelancer->sarbazi_file) }}">دانلود</a>
                                            @endisset</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- کشور --}}
                            @isset($Freelancer->country)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">کشور</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->country }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- آدرس --}}
                            @isset($Freelancer->address)
                                <div class="item-info col-12 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">آدرس</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->address }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- لینکدین --}}
                            @isset($Freelancer->linkedin)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">لینکدین</div>
                                        <a href="{{ $Freelancer->linkedin }}" target="_blank" class="item-value font-weight-bold" style="font-size: 14px">مشاهده پروفایل در لینکدین</a>
                                    </div>
                                </div>
                            @endisset
                            {{-- وب سایت --}}
                            @isset($Freelancer->website)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">وب سایت</div>
                                        <a href="{{ $Freelancer->website }}" target="_blank" class="item-value font-weight-bold" style="font-size: 14px">مشاهده وب سایت</a>
                                    </div>
                                </div>
                            @endisset
                            {{-- تاریخ تولد --}}
                            @isset($Freelancer->birthday)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">تاریخ تولد</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ $Freelancer->birthday }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- تلفن ثابت --}}
                            @isset($Freelancer->home_phone)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">تلفن ثابت</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ $Freelancer->home_phone }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- کد پستی --}}
                            @isset($Freelancer->postal_code)
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">کد پستی</div>
                                        <div class="item-value font-weight-bold num-fa" style="font-size: 14px">{{ $Freelancer->postal_code }}</div>
                                    </div>
                                </div>
                            @endisset
                            {{-- بیوگرافی --}}
                            @isset($Freelancer->biography)
                                <div class="item-info col-12 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">بیوگرافی</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Freelancer->biography }}</div>
                                    </div>
                                </div>
                            @endisset
                        </div>

                    </div>
                </div>

                {{-- سوابق تحصیلی --}}
                @if(isset($FreelancerEducation) && count($FreelancerEducation))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">سوابق تحصیلی</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="row last-child-mb-0">
                                @foreach($FreelancerEducation as $item)
                                    <div class="item-info col-12 mb-3">
                                        <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                            <div class="item-value num-fa" style="font-size: 14px">رشته {{ $item->field_of_study }} {{ $item->orientation ? ' گرایش ' . $item->orientation : '' }} {{ $item->education_level ? ' را در مقطع ' . $item->education_level : '' }} {{ $item->gpa ? ' با معدل ' . $item->gpa : '' }} {{ $item->university ? ' در دانشگاه ' . $item->university : '' }} {{ $item->to_time ? ' را در تاریخ ' . $item->to_time . ' تحصیل کرده ام ' : '' }} .</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- سوابق کاری --}}
                @if(isset($FreelancerWorkExperience) && count($FreelancerWorkExperience))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">سوابق کاری</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="row last-child-mb-0">
                                @foreach($FreelancerWorkExperience as $item)
                                    <div class="item-info col-12 mb-3">
                                        <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                            <div class="item-value num-fa" style="font-size: 14px">سابقه کار در مجموعه {{ $item->company . ' به عنوان ' . $item->field }} {{ $item->activity_type ? ' بصورت  ' . $item->activity_type : '' }} {{ $item->post ? ' در سمت ' . $item->post : '' }} {{ $item->to_time ? ' را در تاریخ ' . $item->to_time : '' }} را دارم.</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- سوابق گواهی نامه ها --}}
                @if(isset($FreelancerCourses) && count($FreelancerCourses))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">سوابق گواهی نامه ها</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="row last-child-mb-0">
                                @foreach($FreelancerCourses as $item)
                                    <div class="item-info col-12 mb-3">
                                        <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                            <div class="item-value num-fa" style="font-size: 14px">دوره {{ $item->title }} {{ $item->to_time ? ' را در تاریخ ' . $item->to_time : '' }} گذرانده ام.</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- تسلط به زبان --}}
                @if(isset($FreelancerLanguage) && count($FreelancerLanguage))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">تسلط به زبان</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="row last-child-mb-0">
                                @foreach($FreelancerLanguage as $item)
                                    <div class="item-info col-12 mb-3">
                                        <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                            <div class="item-value num-fa" style="font-size: 14px">زبان {{ $item->language_name }} {{ $item->language_level ? ' در سطح ' . $item->language_level : '' }} را تسلط دارم.</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- اطلاعات فنی --}}
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <div class="row justify-content-between">
                            <span class="widget-title col-8">اطلاعات فنی</span>
                        </div>
                    </div>

                    <div class="widget-content widget-content-padding">
                        <div @class('row')>
                            {{-- بخش --}}
                            @if($FreelancerGrade->section_id)
                                <div class="col-4 mb-4">
                                    <div class="form-group row no-gutters">
                                        <strong class="mb-2">بخش:</strong>
                                        <div class='col-12'>{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('section', $FreelancerGrade->section_id) }}</div>
                                    </div>
                                </div>
                            @endif
                            {{-- زیر بخش --}}
                            @if($FreelancerGrade->subsection_id)
                                <div class="col-4 mb-4">
                                    <div class="form-group row no-gutters">
                                        <strong class="mb-2">زیر بخش:</strong>
                                        <div class='col-12'>{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('subsection', $FreelancerGrade->subsection_id) }}</div>
                                    </div>
                                </div>
                            @endif
                            {{-- قسمت --}}
                            @if($FreelancerGrade->division_id)
                                <div class="col-4 mb-4">
                                    <div class="form-group row no-gutters">
                                        <strong class="mb-2">قسمت:</strong>
                                        <div class='col-12'>{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('division', $FreelancerGrade->division_id) }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(count($chatList))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">پیام های محرمانه</span>
                        </div>

                        <div class="widget-content widget-content-padding last-child-mb-0">
                            @foreach($chatList as $item)
                                <div class="p-2 px-3 mb-3 rounded" style="background: #f9f9f9">
                                    <strong class="mb-1 d-inline-block">{{ \App\Http\Controllers\HomeController::GetUserData($item->user_id) }}:</strong>
                                    <div>{{ $item->message }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form style="margin-bottom: 40px" action="{{ route('freelancer-grade-chat.store', $FreelancerGrade->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-0">
                        <input type="hidden" name="id" value='{{$FreelancerGrade->id}}'>
                        <textarea class="field-style input-text p-3 border" style="border-radius: 5px;" id="message" name="message" placeholder="پیام خود را ارسال کنید">{{ old('message') }}</textarea>
                        @if($errors->has('message'))
                            <span class="message-show">{{ $errors->first('message') }}</span>
                        @endif
                    </div>

                    <div class='text-left'>
                        <input type="submit" value="ارسال پیام" style="border-radius: 4px" class="border-0 bg-dark text-white cursor-pointer font-weight-bold px-3 p-1">
                    </div>
                </form>

                @if(count($FreelancerGradeLog))
                    <div style="margin-bottom: 40px; font-size: 11px;">
                        @foreach($FreelancerGradeLog as $log)
                            <div style="border-bottom: solid 1px #dddddd; padding-bottom: 5px; margin-bottom: 5px">
                                <strong>{{ $log->User->first_name }}</strong>
                                در تاریخ
                                <strong class="num-fa">{{ \Morilog\Jalali\Jalalian::forge($log->created_at)->format('H:i - Y/m/d') }}</strong>
                                نمره فنی را از <strong class="num-fa">{{ $log->from_grade ?: 'بدون نمره' }}</strong> به نمره <strong class="num-fa">{{ $log->to_grade }}</strong> تغییر داد.
                                <a target="_blank" href="{{ route('freelancer-grade.history.details', $log->id) }}" style="color: #0a58ca;"> جزئیات</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-3">
                @if($FreelancerGrade->grade || $FreelancerGrade->suggest_grade)
                    <div class="widget-block widget-item widget-style">
                        <div class="widget-content widget-content-padding widget-content-padding-side">

                            @if($FreelancerGrade->grade)
                                <div class="font-weight-bold text-center p-3 rounded mb-3 num-fa" style="background: #e8fdee;">
                                    <div class="mb-2">امتیاز نهایی فریلنسر</div>
                                    <div style="font-size: 28px; line-height: 30px; color: #00c400">{{ $FreelancerGrade->grade }}</div>
                                </div>
                            @endif

                            @if($FreelancerGrade->suggest_grade)
                                <div class="font-weight-bold text-center p-3 rounded mb-3 num-fa" style="background: #fff8e5;">
                                    <div class="mb-2">امتیاز پیشنهادی ارزیاب</div>
                                    <div style="font-size: 28px; line-height: 30px; color: #ffab00">{{ $FreelancerGrade->suggest_grade }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <form action="{{ route('freelancer-grade.update', $FreelancerGrade->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    {{-- Publish Options --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side num-fa">
                            @foreach($Labels as $index => $label)
                                @php
                                    $value = $OldGrades[$index] ?? 5;
                                    $valueMessage = $OldGradesMessage[$index] ?? '';
                                @endphp

                                <div class="range-wrapper">
                                    <label for="range{{ $index }}">{{ $label }}</label>
                                    <div class="range-row">
                                        <input
                                            name="grade_type[{{ $index }}]"
                                            type="range"
                                            id="range{{ $index }}"
                                            min="1"
                                            max="10"
                                            value="{{ $value }}"
                                            step="1"
                                        >
                                        <div class="range-value" data-for="range{{ $index }}">{{ $value }}</div>
                                    </div>
                                    <textarea rows="3" name="grade_message_text[{{ $index }}]" placeholder="توضیحات..." style="font-size: 12px; border: solid 1px #cccccc; border-radius: 5px; background: #f8f8f8; padding: 3px 7px; margin: 0 -10px">{{ $valueMessage }}</textarea>
                                </div>
                            @endforeach

                            <script>
                                document.addEventListener('input', e => {
                                    if (e.target.type === 'range') {
                                        const id = e.target.id;
                                        const val = document.querySelector(`.range-value[data-for="${id}"]`);
                                        if (val) val.textContent = e.target.value;
                                    }
                                });
                            </script>
                            <button type="submit" class="submit-form-btn create-btn">بروزرسانی نمره فنی</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
