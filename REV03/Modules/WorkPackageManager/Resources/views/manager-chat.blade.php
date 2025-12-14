@extends('dashboard::layouts.dashboard.master')

@section('title','فرایند تایید بسته کاری')

@section('lib')
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">فرایند تایید بسته کاری</span>
@endsection

@section('content')
    <section class="form-section">
        <div class="row">
            <div class="col-9">
                @forelse($chatList as $item)
                    <div class="widget-block widget-item widget-style" style="padding: 20px">
                        <div style="border-bottom: solid 1px #EEEEEE;" class="mb-3 pb-3">{{ $item->users->first_name }} {{ $item->users->last_name }} ({{ \App\Http\Controllers\HomeController::RoleTranslation($item->users->role) }})</div>
                        {!! nl2br($item->message) !!}
                    </div>
                @empty
                @endforelse

                <div class="mt-3">
                    <form action="{{ route('work-package-manager-chat.store', $ID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($item->id)
                            <input type="hidden" name="replay_to_user_id" value="{{$item->id}}">
                        @endisset
                        <div class="form-group mb-0">
                            <textarea class="field-style input-text p-3 border" style="border-radius: 5px;" id="message" name="message" placeholder="پیام خود را ارسال کنید">{{ old('message') }}</textarea>
                            @if($errors->has('message'))
                                <span class="message-show">{{ $errors->first('message') }}</span>
                            @endif
                        </div>

                        <div class='text-left'>
                            <input type="submit" value="ارسال پیام" style="border-radius: 4px" class="border-0 bg-dark text-white cursor-pointer font-weight-bold px-3 p-1">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-3">
                <div class="font-weight-bold text-center p-3 rounded mb-3 num-fa" style="background: {{ \App\Http\Controllers\HomeController::ConvertWorkPackageStatus($WorkPackage->status)[1] }}20;">
                    <div class="mb-2">وضعیت بسته کاری</div>
                    <div style="font-size: 28px; line-height: 30px; color: {{ \App\Http\Controllers\HomeController::ConvertWorkPackageStatus($WorkPackage->status)[1] }}">{{ \App\Http\Controllers\HomeController::ConvertWorkPackageStatus($WorkPackage->status)[0] }}</div>
                </div>

                <a href='{{ route('work-package.edit', $ID) }}' class="submit-form-btn mb-3" style="background-color: #0092ee">مشاهده بسته کاری</a>
                @if(($WorkPackage->status === 'pending' && auth()->user()->role === 'sectionManager') || ($WorkPackage->status === 'pre_accept' && auth()->user()->role === 'admin'))
                    <form action="{{ route('work-package.status', $WorkPackage->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <button type="submit" class="submit-form-btn" style="background-color: #0ccc00">تایید بسته کاری</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection
