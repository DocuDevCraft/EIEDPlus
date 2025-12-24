@extends('dashboard::layouts.dashboard.master')

@section('title')
    پرفایل {{ $Freelancer->users->first_name . ' ' . $Freelancer->users->last_name  }}
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/users/images/icons/user.gif') }}"></span>
    <span class="text">فریلنسر {{ $Freelancer->users->first_name . ' ' . $Freelancer->users->last_name }}</span>
@endsection

@section('content')
    <section class="form-section">
        <form action="{{ route('freelancer.update', $Freelancer->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="row">
                <div class="col-9">
                    {{-- تنظیمات حساب کاربری --}}
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

                </div>

                @can('isAdmin')
                    <div class="col-3">
                        <div class="widget-block widget-item widget-style">
                            <div class="heading-widget">
                                <span class="widget-title">ثبت اطلاعات</span>
                            </div>

                            <div class="widget-content widget-content-padding widget-content-padding-side">
                                {{-- وضعیت حساب --}}
                                <div class="form-group row no-gutters">
                                    @if($errors->has('status'))
                                        <span class="message-show">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="col-12 field-style">
                                        <select data-placeholder="یک مورد را انتخاب کنید" id="status" class="select chosen-rtl" name="status">
                                            <option></option>
                                            <option value="deactivate" @selected(old(
                                        "status", $Freelancer->users->status) == "deactivate")> غیرفعال
                                            </option>
                                            <option value="active" @selected(old(
                                        "status", $Freelancer->users->status) == "active")>فعال
                                            </option>
                                        </select>
                                    </div>
                                    {{ Form::label('status','وضعیت حساب:',['class'=>'col-12']) }}
                                </div>

                                <button type="submit" class="submit-form-btn">بروزرسانی اطلاعات</button>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </form>
    </section>
@endsection
