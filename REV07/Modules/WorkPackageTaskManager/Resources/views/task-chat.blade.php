@extends('dashboard::layouts.dashboard.master')

@section('title','سوال وظیفه')

@section('lib')
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">ITC</span>
@endsection

@section('content')
    <section class="form-section">
        <div class="row">
            <div class="col-9 mb-4">
                @forelse($chatList as $item)
                    <div class="widget-block widget-item widget-style" style="padding: 20px">
                        {!! nl2br($item->message) !!}
                        @if($item->attachment)
                            <div class='border-top mt-3 pt-3 font-weight-bold'>فایل پیوست: <a href="{{$item->attachment['path']}}" target="_blank" style="color: #0a87ca">{{$item->attachment['file_name']}}</a></div>
                        @endif
                    </div>
                @empty
                @endforelse

                <div class="mt-3">
                    <form action="{{ route('task-comment.store', $ID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($item->id)
                            <input type="hidden" name="replay_to_user_id" value="{{$item->id}}">
                        @endisset
                        <div class="form-group mb-0">
                            <textarea class="field-style input-text p-3 border" style="border-radius: 5px;" id="message" name="message" placeholder="پیام خود را ارسال کنید">{{ old('message') }}</textarea>
                            {{--                                    @if($errors->has('message'))--}}
                            {{--                                        <span class="message-show">{{ $errors->first('message') }}</span>--}}
                            {{--                                    @endif--}}
                        </div>
                        <div class="form-group mb-10">
                            <label class="btn-block">پیوست فایل:</label>
                            <input type="file" name="attachment">
                        </div>

                        <div class='text-left'>
                            <input type="submit" value="ارسال پیام" style="border-radius: 4px" class="border-0 bg-dark text-white cursor-pointer font-weight-bold px-3 p-1">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-3">
                <div style="position: sticky; top: 0px">
                    <a href="{{ url('dashboard/work-package-task/' . $WorkPackageID) }}" class="submit-form-btn mb-4">بازگشت به فرآیند بسته کاری</a>
                </div>
            </div>
        </div>
    </section>
@endsection
