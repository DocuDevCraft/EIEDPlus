@extends('dashboard::layouts.dashboard.master')

@section('title')
    پرفایل {{ $Users->first_name . ' ' . $Users->last_name  }}
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/users/images/icons/user.gif') }}"></span>
    <span class="text">ویرایش کاربر {{ $Users->first_name . ' ' . $Users->last_name }}</span>
@endsection

@section('content')
    @if($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif

    <section class="form-section">
        <form action="{{ route('users.update', $Users->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="row">
                <div class="col-9">
                    {{-- تنظیمات حساب کاربری --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">تنظیمات حساب کاربری</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div @class('row')>
                                {{-- نام --}}
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('first_name'))
                                            <span class="col-12 message-show">{{ $errors->first('first_name') }}</span>
                                        @endif
                                        {{ Form::text('first_name',$Users->first_name,[ 'id'=>'first_name' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'نام کاربر را وارد نمایید']) }}
                                        {{ Form::label('first_name','نام:',['class'=>'col-12']) }}
                                    </div>
                                </div>
                                {{--  نام خانوادگی --}}
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('first_name'))
                                            <span class="col-12 message-show">{{ $errors->first('last_name') }}</span>
                                        @endif
                                        {{ Form::text('last_name',$Users->last_name,[ 'id'=>'last_name' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'نام خانوادگی کاربر را وارد نمایید']) }}
                                        {{ Form::label('last_name','نام خانوادگی:',['class'=>'col-12']) }}
                                    </div>
                                </div>

                                {{-- ایمیل --}}
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('email'))
                                            <span class="col-12 message-show">{{ $errors->first('email') }}</span>
                                        @endif
                                        {{ Form::email('email',$Users->email,[ 'id'=>'email' , 'class'=>'col-12 field-style input-text left', 'placeholder'=>'ایمیل کاربر را وارد نمایید']) }}
                                        {{ Form::label('email','ایمیل:',['class'=>'col-12']) }}
                                    </div>
                                </div>

                                {{-- تلفن --}}
                                <div class="col-6 mb-4">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('phone'))
                                            <span class="col-12 message-show">{{ $errors->first('phone') }}</span>
                                        @endif
                                        {{ Form::text('phone',$Users->phone,[ 'id'=>'phone' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'موبایل کاربر را وارد نمایید']) }}
                                        {{ Form::label('phone','موبایل:',['class'=>'col-12']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('isAdmin')
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
                                    <textarea class="field-style input-text" id="biography" name="biography" placeholder="بیوگرافی را وارد نمایید">{{ old('biography', $Users->biography) }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endcan

                    {{-- تنظیمات امنیتی --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">تنظیمات حساب کاربری</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div @class('row mb-3')>
                                <div class="col-12">
                                    <div class="form-group row no-gutters">
                                        @if($errors->has('password'))
                                            <span class="col-12 message-show">{{ $errors->first('password') }}</span>
                                        @endif
                                        <input type="password" name="password_change" class="col-12 field-style input-text" id="password" placeholder="رمز عبور کاربر را وارد نمایید" autocomplete="new-password">
                                        <label class="col-12" for="password">رمز عبور:</label>
                                    </div>
                                </div>
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
                                            <label for="thumbnail-image" id="thumbnail-label" class="thumbnail-label"><img id="thumbnail-preview" src="@if($Users->avatar){{ asset( 'storage/' . \Modules\FileLibrary\Entities\FileLibrary::find($Users->avatar)->path .'full/'. \Modules\FileLibrary\Entities\FileLibrary::find($Users->avatar)->file_name)  }}@else{{ asset('/modules/dashboard/admin/img/base/icons/image.svg') }}@endif"></label>
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
                            @can('isAdmin')
                                <div class="form-group row no-gutters">
                                    @if($errors->has('role'))
                                        <span class="message-show">{{ $errors->first('role') }}</span>
                                    @endif
                                    <div class="col-12 field-style custom-select-field">
                                        <select data-placeholder="یک مورد را انتخاب کنید" id="role" class="select chosen-rtl" name="role">
                                            <option></option>
                                            <option value="admin" @selected(old(
                                            "role", $Users->role) == "admin")> مدیر ارشد
                                            </option>
                                            <option value="engineeringManager" @selected(old(
                                            "role", $Users->role) == "engineeringManager")> مدیر امور مهندسی
                                            </option>
                                            <option value="sectionManager" @selected(old(
                                            "role", $Users->role) == "sectionManager")>مدیر بخش
                                            </option>
                                            <option value="workPackageManager" @selected(old(
                                            "role", $Users->role) == "workPackageManager")>مسئول بسته کاری
                                            </option>
                                            <option value="chiefAppraiser" @selected(old(
                                            "role", $Users->role) == "chiefAppraiser")>سرارزیاب
                                            </option>
                                            <option value="appraiser" @selected(old(
                                            "role", $Users->role) == "appraiser")>ارزیاب
                                            </option>
                                            <option value="operator" @selected(old(
                                            "role", $Users->role) == "operator")>اپراتور
                                            </option>
                                            <option value="support" @selected(old(
                                            "role", $Users->role) == "support")>پشتیبانی
                                            </option>
                                            <option value="author" @selected(old(
                                            "role", $Users->role) == "author")>نویسنده
                                            </option>
                                            <option value="user" @selected(old(
                                            "role", $Users->role) == "user")>کاربر
                                            </option>
                                        </select>
                                    </div>
                                    {{ Form::label('role','سطح دسترسی:',['class'=>'col-12']) }}
                                </div>

                                {{-- وضعیت حساب --}}
                                <div class="form-group row no-gutters">
                                    @if($errors->has('status'))
                                        <span class="message-show">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="col-12 field-style custom-select-field">
                                        <select data-placeholder="یک مورد را انتخاب کنید" id="status" class="select chosen-rtl" name="status">
                                            <option></option>
                                            <option value="deactivate" @selected(old(
                                            "status", $Users->status) == "deactivate")> غیرفعال
                                            </option>
                                            <option value="active" @selected(old(
                                            "status", $Users->status) == "active")>فعال
                                            </option>
                                        </select>
                                    </div>
                                    {{ Form::label('status','وضعیت حساب:',['class'=>'col-12']) }}
                                </div>
                            @endcan

                            <button type="submit" class="submit-form-btn">بروزرسانی اطلاعات</button>
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
