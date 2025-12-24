@extends('dashboard::layouts.dashboard.master')

@section('title','فضای گفتگو و ارسال مستندات بر روی کل بسته کاری')

@section('lib')
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">فضای گفتگو و ارسال مستندات بر روی کل بسته کاری</span>
@endsection

@section('content')
    <section class="form-section">
        <div class="row">
            <div class="col-12">
                @forelse($chatList as $item)
                    <div class="widget-block widget-item widget-style" style="padding: 20px;">
                        <div class="mb-1">{{ $item->users->first_name }} {{ $item->users->last_name }} @if($item->type == 'on_board')
                                <span style="color: #ff7133">(برنده بسته کاری)</span>
                            @endif:
                            <span class="num-fa" style="color: #888888">({{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('H:i - Y/m/d') }})</span>
                        </div>
                        <div class="p-4" style="border-radius: 10px; background: #f4f5f7">
                            {{ nl2br($item->message) }}

                            @if($item->attachment)
                                @if($item->message)
                                    <hr>
                                @endif
                                <a href="{{$item->attachment['path']}}" target="_blank" download="{{ $item->attachment['file_name'] }}"> فایل پیوست: {{ $item->attachment['file_name'] }}</a>
                            @endif
                        </div>

                        @forelse($item->parent as $itemParent)
                            <div style="margin: 20px 0; padding-right: 40px; border-right: solid 1px #EEEEEE">
                                <div>{{ $itemParent->users->first_name }} {{ $itemParent->users->last_name }}:
                                    <span class="num-fa" style="color: #888888">({{ \Morilog\Jalali\Jalalian::forge($itemParent->created_at)->format('H:i - Y/m/d') }})</span>
                                </div>
                                <div class="mt-3 p-3" style="border-radius: 5px; background: #EEEEEE">{{ nl2br($itemParent->message) }} </div>
                            </div>
                        @empty
                        @endforelse

                        <div class="mt-3">
                            <form action="{{ route('work-package-chat.store', $item->work_package_id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="replay_to_user_id" value="{{$item->id}}">
                                <div class="form-group mb-0">
                                    <textarea class="field-style input-text p-3 border" style="border-radius: 5px;" id="message" name="message" placeholder="برای این سوال پاسخی ارسال کنید">{{ old('message') }}</textarea>
                                    {{--                                    @if($errors->has('message'))--}}
                                    {{--                                        <span class="message-show">{{ $errors->first('message') }}</span>--}}
                                    {{--                                    @endif--}}
                                </div>

                                <div class='text-left'>
                                    <input type="submit" value="ارسال پاسخ" style="border-radius: 4px" class="border-0 bg-dark text-white cursor-pointer font-weight-bold px-3 p-1">
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="widget-block widget-item widget-style center no-item">
                        <div class="icon"><img src="{{ asset('/modules/dashboard/admin/img/base/icons/no-item.svg') }}"></div>
                        <h2>هیچ موردی یافت نشد!</h2>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
