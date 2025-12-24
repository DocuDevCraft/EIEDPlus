@extends('dashboard::layouts.dashboard.master')

@section('title','افزودن کاربر جدید')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/users/images/icons/user.gif') }}"></span>
    <span class="text">افزودن کاربر جدید</span>
@endsection

@section('content')
    <section class="form-section">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-9">
                    {{-- تنظیمات حساب کاربری --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">تنظیمات حساب کاربری</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div @class('row')>
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('first_name'))
                                            <span class="col-12 message-show">{{ $errors->first('first_name') }}</span>
                                        @endif
                                        {{ Form::text('first_name',null,[ 'id'=>'first_name' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'نام کاربر را وارد نمایید']) }}
                                        {{ Form::label('first_name','نام:',['class'=>'col-12']) }}
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('last_name'))
                                            <span class="col-12 message-show">{{ $errors->first('last_name') }}</span>
                                        @endif
                                        {{ Form::text('last_name',null,[ 'id'=>'last_name' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'نام خانوادگی کاربر را وارد نمایید']) }}
                                        {{ Form::label('last_name','نام خانوادگی:',['class'=>'col-12']) }}
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('email'))
                                            <span class="col-12 message-show">{{ $errors->first('email') }}</span>
                                        @endif
                                        {{ Form::email('email',null,[ 'id'=>'email' , 'class'=>'col-12 field-style input-text left', 'placeholder'=>'ایمیل کاربر را وارد نمایید']) }}
                                        {{ Form::label('email','ایمیل:',['class'=>'col-12']) }}
                                    </div>
                                </div>
                                {{-- تلفن --}}
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('phone'))
                                            <span class="col-12 message-show">{{ $errors->first('phone') }}</span>
                                        @endif
                                        {{ Form::text('phone',null,[ 'id'=>'phone' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'موبایل کاربر را وارد نمایید']) }}
                                        {{ Form::label('phone','موبایل:',['class'=>'col-12']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- بیوگرافی --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="row">
                                <div class="col-9">
                                    <span class="widget-title"><label for="biography">بیوگرافی</label></span>
                                </div>
                                <div class="col-3 left"></div>
                            </div>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="form-group row no-gutters">
                                @if($errors->has('biography'))
                                    <span class="col-12 message-show">{{ $errors->first('biography') }}</span>
                                @endif
                                <textarea class="field-style input-text" id="biography" name="biography" placeholder="بیوگرافی را وارد نمایید">{{ old('biography') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    {{-- تصویر پروفایل --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">تصویر پروفایل</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <div class="form-group row no-gutters">
                                @if($errors->has('avatar'))
                                    <span class="message-show">{{ $errors->first('avatar') }}</span>
                                @endif
                                <div class="col-12 field-style custom-select-field">
                                    <div class="thumbnail-image-upload">
                                        <div>
                                            <label for="thumbnail-image" id="thumbnail-label" class="thumbnail-label"><img id="thumbnail-preview" src="{{ asset('/modules/dashboard/admin/img/base/icons/image.svg') }}"></label>
                                            <input onchange="readURL(this)" name="avatar" type="file" class="thumbnail-image" id="thumbnail-image" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <div class="form-group row no-gutters">
                                @if($errors->has('role'))
                                    <span class="message-show">{{ $errors->first('role') }}</span>
                                @endif
                                <div class="col-12 field-style ">
                                    <select data-placeholder="یک مورد را انتخاب کنید" id="role" class="select chosen-rtl" name="role">
                                        <option value="admin" @selected(old("role") == "admin")> مدیر ارشد</option>
                                        <option value="engineeringManager" @selected(old("role") == "engineeringManager")> مدیر امور مهندسی</option>
                                        <option value="sectionManager" @selected(old("role") == "sectionManager")>مدیر بخش</option>
                                        <option value="workPackageManager" @selected(old("role") == "workPackageManager")>مسئول بسته کاری</option>
                                        <option value="chiefAppraiser" @selected(old("role") == "chiefAppraiser")>سرارزیاب</option>
                                        <option value="appraiser" @selected(old("role") == "appraiser")>ارزیاب</option>
                                        <option value="operator" @selected(old("role") == "operator")>اپراتور</option>
                                        <option value="support" @selected(old("role") == "support")>پشتیبانی</option>
                                        <option value="author" @selected(old("role") == "author")>نویسنده</option>
                                        <option value="freelancer" @selected(old("role") == "freelancer")>فریلنسر</option>
                                    </select>
                                </div>
                                {{ Form::label('role','سطح دسترسی:',['class'=>'col-12']) }}
                            </div>
                            <div class="form-group row no-gutters">
                                @if($errors->has('status'))
                                    <span class="message-show">{{ $errors->first('status') }}</span>
                                @endif
                                <div class="col-12 field-style ">
                                    <select data-placeholder="یک مورد را انتخاب کنید" id="status" class="select chosen-rtl" name="status">
                                        <option value="deactivate" @selected(old("status") == "deactivate")> غیرفعال</option>
                                        <option value="active" @selected(old("status") == "active")>فعال</option>
                                    </select>
                                </div>
                                {{ Form::label('status','وضعیت حساب:',['class'=>'col-12']) }}
                            </div>
                            <button type="submit" class="submit-form-btn create-btn">ایجاد حساب کاربری</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('footer')
    {{-- Avatar Preview --}}
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(input).prev().find('img').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }
    </script>
@endsection
