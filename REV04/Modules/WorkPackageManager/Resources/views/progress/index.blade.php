@extends('dashboard::layouts.dashboard.master')

@section('title','بسته های کاری')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">فرآیند بسته کاری</span>
@endsection

@section('content')
    @if(count($Activity))
        @foreach($Activity as $itemActivity)
            <div class="widget-block widget-item widget-style">
                <div class="heading-widget">
                    <div class="row align-items-end">
                        <div class="col field-block">
                            <span class="widget-title">{{ $itemActivity->title }}</span>
                        </div>
                        <strong class="col field-block left num-fa">
                            {{ \App\Http\Controllers\HomeController::CalculateProgressPercentage($itemActivity->id, 'activity') }} %
                        </strong>
                    </div>
                </div>

                @if($itemActivity->category)
                    <div class="widget-content widget-content-padding last-child-mb-0">
                        @foreach($itemActivity->category as $itemCategory)
                            <div class='border p-4 mb-4 rounded' style="background: #f5f5f5">
                                <div class='row'>
                                    <div class="col">
                                        <strong>{{ $itemCategory->title }}</strong>
                                        @if($itemCategory->milestone === 'on')
                                            <span style="font-weight: 600;margin-right:6px; border-radius: 4px; padding: 1px 5px; font-size: 10px; background: #ff8621; color: #FFFFFF">موعد پرداخت</span>
                                        @endif
                                    </div>
                                    <strong class="col field-block left num-fa">
                                        {{ \App\Http\Controllers\HomeController::CalculateProgressPercentage($itemCategory->id, 'category') }} %
                                    </strong>
                                </div>

                                @if($itemCategory->task)
                                    <div class="mt-3 last-child-mb-0">
                                        @foreach($itemCategory->task as $itemTask)
                                            <div class="border p-3 mb-3 rounded bg-white" style='{{ $itemTask->status === 'completed' ? 'border-color: #46cc05 !important; background: #f6fff1 !important' : ($itemTask->progress ? 'border-color: #ffbeae !important' :'') }}'>
                                                <div class="row justify-content-between align-items-center">
                                                    <div class="col row align-items-center">
                                                        <div class="col">
                                                            <div>عنوان</div>
                                                            <strong>{{ $itemTask->title }}</strong>
                                                        </div>
                                                        @if($itemTask->status)
                                                            <div class="col-3 num-fa">
                                                                <div>وضعیت</div>
                                                                <strong style="color: {{\App\Http\Controllers\HomeController::ConvertTaskStatus($itemTask->status)[1]}}">{{ \App\Http\Controllers\HomeController::ConvertTaskStatus($itemTask->status)[0] }}</strong>
                                                            </div>
                                                        @endif
                                                        <div class="col-3 num-fa">
                                                            <div>مبلغ</div>
                                                            <strong>{{ number_format($itemTask->price) }}</strong> تومان
                                                        </div>
                                                        <div class="col-3 num-fa">
                                                            <div>سر رسید</div>
                                                            <strong>{{ \Morilog\Jalali\Jalalian::forge($itemTask->due_date)->format('Y/m/d') }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 left">
                                                        <a href='{{route('task.show', $itemTask->id)}}' class="bg-white p-2 px-3 rounded border d-inline-block ml-2" style="position: relative">ارسال نسخه نهایی مدرک
                                                            @if($itemTask->status !== 'completed' && $itemTask->progress)
                                                                <span class="num-fa" style="font-size: 12px; position: absolute; left: -9px; top: -9px; background: red; color: #FFFFFF; font-weight: bold; padding: 3px 7px; border-radius: 20px; line-height: 12px">{{ $itemTask->progress }}</span>
                                                            @endif
                                                        </a>
                                                        <a href='{{route('task-comment.get', $itemTask->id)}}' class="p-2 px-3 rounded border d-inline-block bg-white" style="position: relative">ITC <span class="num-fa">
                                                                @if($itemTask->chat['new'])
                                                                    <span class="num-fa" style="font-size: 12px; position: absolute; left: -9px; top: -9px; background: red; color: #FFFFFF; font-weight: bold; padding: 3px 7px; border-radius: 20px; line-height: 12px">{{ $itemTask->chat['new'] }}</span>
                                                                @endif
                                                            </span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif
@endsection
