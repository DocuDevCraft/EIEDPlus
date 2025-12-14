@extends('dashboard::layouts.dashboard.master')

@section('title')
    {{ $Task->title }}
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/freelancer/images/icons/freelancer.gif') }}"></span>
    <span class="text">{{$Task->title}}</span>
@endsection

@section('content')
    <section class="form-section">
        <div class="row">
            <div class="col-9">
                {{-- پیشنهاد --}}
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">اطلاعات وظیفه</span>
                    </div>

                    <div class="widget-content widget-content-padding num-fa">
                        <div @class('row')>
                            <div class="col-4 mb-4">
                                <div class="form-group row no-gutters">
                                    <strong class="mb-2">عنوان:</strong>
                                    <div class='col-12'>{{ $Task->title }}</div>
                                </div>
                            </div>
                            <div class="col-4 mb-4">
                                <div class="form-group row no-gutters">
                                    <strong class="mb-2">قیمت:</strong>
                                    <div class='col-12'>{{ number_format($Task->price) }} تومان</div>
                                </div>
                            </div>
                            <div class="col-4 mb-4">
                                <div class="form-group row no-gutters">
                                    <strong class="mb-2">زمان سر رسید:</strong>
                                    <div class='col-12'>{{ \Morilog\Jalali\Jalalian::forge($Task->due_date)->format('Y/m/d') }} <span style="font-size: 11px; padding-right: 5px; color: #999999">({{ \Morilog\Jalali\Jalalian::forge($Task->due_date)->ago() }})</span></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group row no-gutters border-top pt-3">
                                    <strong class="mb-2">توضیحات:</strong>
                                    <div class='col-12'>{{ $Task->desc }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- اطلاعات فنی --}}
                @if(count($TaskProgress))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">اطلاعات فنی</span>
                        </div>

                        <div class="widget-content widget-content-padding num-fa last-child-border-none last-child-mb-0">
                            @foreach($TaskProgress as $item)
                                <div class="row justify-content-between border-bottom mb-4">
                                    <div class="col row">
                                        <div class="col-4 mb-4">
                                            <div class="form-group row no-gutters">
                                                <strong class="mb-2">فایل</strong>
                                                <div class='col-12'><a href="{{ $item->attachment['path'] }}" target="_blank">{{ $item->attachment['name'] }}</a></div>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-4">
                                            <div class="form-group row no-gutters">
                                                <strong class="mb-2">زمان ثبت</strong>
                                                <div class='col-12'>{{ \Morilog\Jalali\Jalalian::forge($item->crearted_at) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-4">
                                            <div class="form-group row no-gutters">
                                                <strong class="mb-2">وضعیت</strong>
                                                <strong class='col-12' style="color: {{ \App\Http\Controllers\HomeController::ConvertProgressStatus($item->status)[1] }}">{{ \App\Http\Controllers\HomeController::ConvertProgressStatus($item->status)[0] }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 left">
                                        <form action="{{ route('progress.update', $item->id) }}" class="row pt-3" method="post" enctype="multipart/form-data">
                                            @csrf
                                            {{ method_field('PUT') }}
                                            <div class="col">
                                                <button type="submit" name="status" value="completed" class="submit-form-btn create-btn" {{ $item->status === 'completed' ? 'disabled' : ''  }} style="{{ $item->status === 'completed' ? 'opacity: .15; pointer-events: none;' : ''  }}">مورد تایید است</button>
                                            </div>
                                            <div class="col">
                                                <button type="submit" name="status" value="rejected" class="submit-form-btn bg-danger" {{ $item->status === 'rejected' ? 'disabled' : ''  }} style="{{ $item->status === 'rejected' ? 'opacity: .15; pointer-events: none;' : ''  }}">مورد تایید نیست</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center" style="font-size: 24px">هیچ فعالیتی ثبت نشده است</div>
                @endif
            </div>

            <div class="col-3">
                <a href="{{ url('dashboard/work-package-task/' . $Task->work_package_id) }}" class="submit-form-btn mb-4">بازگشت به فرآیند بسته کاری</a>
                <form action="{{ route('task.update', $Task->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    {{-- Publish Options --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت وضعیت</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <div class="form-group row no-gutters">
                                @if($errors->has('status'))
                                    <span class="message-show">{{ $errors->first('status') }}</span>
                                @endif
                                <div class="col-12 field-style ">
                                    <select data-placeholder="یک مورد را انتخاب کنید" id="status" class="select chosen-rtl" name="status">
                                        <option value="" @selected(old(
                                        "status", $Task->status) == "")> بدون وضعیت
                                        </option>
                                        <option value="completed" @selected(old(
                                        "status", $Task->status) == "completed")>تکمیل شد
                                        </option>
                                        <option value="stop" @selected(old(
                                        "status", $Task->status) == "stop")>متوقف شد
                                        </option>
                                    </select>
                                </div>
                                {!! Form::label('status','وضعیت:',['class'=>'col-12']) !!}
                            </div>

                            <button type="submit" class="submit-form-btn create-btn">ویرایش وضعیت</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
