@extends('dashboard::layouts.dashboard.master')

@section('title')
    مشاهده قرارداد
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/contract/images/icons/contract.gif') }}"></span>
    <span class="text">اطلاعات قرارداد {{ $Contract->users->first_name . ' ' . $Contract->users->last_name }}</span>
@endsection

@section('content')
    <section class="form-section">
        <form action="{{ route('contract.update', $Contract->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="row">
                <div class="col-9">
                    {{-- تنظیمات حساب کاربری --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">اطلاعات قرارداد</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="row">
                                {{-- نام و نام خانوادگی --}}
                                <div class="item-info col-4 mb-3">
                                    <div style="background: #f9f9f9" class="p-2 px-3 rounded">
                                        <div class="item-label" style="font-size: 12px; font-weight: 600; color: #7e7e7e">نام و نام خانوادگی</div>
                                        <div class="item-value font-weight-bold" style="font-size: 14px">{{ $Contract->users->first_name . ' ' . $Contract->users->last_name }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-3">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            {{-- قرارداد --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('status'))
                                    <span class="message-show">{{ $errors->first('status') }}</span>
                                @endif
                                <div class="col-12 field-style text-center font-weight-bold border mt-1" style="padding: 20px 0">
                                    @switch($Contract->status)
                                        @case('no_sign') بدون امضا
                                        @break
                                        @case('freelancer_signed') فریلنسر امضا کرده است
                                        @break
                                        @case('employer_signed') کارفرما امضا کرده است
                                        @break
                                        @defaultبدون وضعیت
                                        @break
                                    @endswitch

                                </div>
                                {{ Form::label('status','وضعیت حساب:',['class'=>'col-12']) }}
                            </div>

                            <button type="submit" class="submit-form-btn">آپلود قرار داد کارفرما</button>
                        </div>
                    </div>
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            {{-- قرارداد --}}
                            <div class="form-group row no-gutters border-bottom pb-2">
                                @if($errors->has('status'))
                                    <span class="message-show">{{ $errors->first('status') }}</span>
                                @endif
                                @if($Contract->status === 'freelancer_signed' || $Contract->status === 'employer_signed')
                                    @if($Contract->contract_freelancer_signed)
                                        <div class="col-12 field-style">
                                            <a target="_blank" href="{{ route('contract.employer.download', ['signed',$Contract->contract_freelancer_signed]) }}">دانلود قرارداد با امضای فرلنسر</a>
                                        </div>
                                    @endif
                                @endif
                                @if($Contract->status === 'employer_signed' && $Contract->contract_employer_signed)
                                    <div class="col-12 field-style border-top pt-2 mt-2">
                                        <a target="_blank" href="{{ route('contract.employer.download', ['employer-signed',$Contract->contract_employer_signed]) }}">دانلود قرارداد با امضای کارفرما</a>
                                    </div>
                                @endif
                                {{ Form::label('status','دانلود قراردادها:',['class'=>'col-12']) }}
                            </div>

                            <div class="form-group row no-gutters">
                                @if($errors->has('contract_employer_signed'))
                                    <span class="col-12 message-show">{{ $errors->first('contract_employer_signed') }}</span>
                                @endif
                                {{ Form::label('contract_employer_signed','آپلود قرارداد امضا شده کارفرما:',['class'=>'col-12 mb-1']) }}
                                <input type="file" value="{{ old('contract_employer_signed') }}" name="contract_employer_signed">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
