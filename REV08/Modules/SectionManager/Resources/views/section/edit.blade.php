@extends('dashboard::layouts.dashboard.master')

@section('title','ویرایش بخش')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">ویرایش بخش</span>
@endsection

@section('content')
    <section class="form-section">
        <form action="{{ route('section.update', $Section->id) }}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="row">
                                <div class="col-9">
                                    <span class="widget-title">ویرایش بخش</span>
                                </div>
                                <div class="col-3 left"></div>
                            </div>
                        </div>

                        <div class="widget-content widget-content-padding">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="form-group row no-gutters">
                                @if($errors->has('title'))
                                    <span class="col-12 message-show">{{ $errors->first('title') }}</span>
    @endif
    {{ Form::text('title',$Section->title,[ 'id'=>'title' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'عنوان بخش بندی را وارد نمایید', 'dir' => 'auto'])
    {{ Form::label('title','عنوان بخش:',['class'=>'col-12'])
</div>
<div class="form-group row no-gutters">
    @if($errors->has('code'))
        <span class="col-12 message-show">{{ $errors->first('code') }}</span>
    @endif
    {{ Form::text('code',$Section->code,[ 'id'=>'code' , 'class'=>'col-12 field-style input-text text-right', 'dir'=>'ltr', 'placeholder'=>'کد اختصاری بخش را وارد نمایید'])
    {{ Form::label('code','کد اختصاری بخش (en):',['class'=>'col-12'])
</div>
</div>
</div>
</div>
<div class="col-3">
{{-- Publish Options --}}
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<span class="widget-title">ثبت اطلاعات</span>
</div>

<div class="widget-content widget-content-padding widget-content-padding-side">
<div class="form-group row no-gutters">
    <div class="col-12 field-style">
        <select multiple data-placeholder="مدیران را انتخاب کنید..." class="form-control chosen-rtl select" name="manager[]" id="manager">
            @forelse($SectionManager as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, old('manager') ?: $Section->Users()->pluck('id')->toArray() ?: [] ) ? 'selected' : '' }}>{{ $item->first_name . ' ' . $item->last_name }}</option>
            @empty
            @endforelse
        </select>
    </div>
    {{ Form::label('manager','مدیران:',['class'=>'col-12'])
</div>
<div class="form-group row no-gutters">
    <div class="col-12 field-style">
        <select multiple data-placeholder="سرارزیاب ها را انتخاب کنید..." class="form-control chosen-rtl select" name="chiefAppraiser[]" id="chiefAppraiser">
            @forelse($ChiefAppraiser as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, old('chiefAppraiser') ?: $Section->ChiefAppraiser()->pluck('id')->toArray() ?: [] ) ? 'selected' : '' }}>{{ $item->first_name . ' ' . $item->last_name }}</option>
            @empty
            @endforelse
        </select>
    </div>
    {{ Form::label('chiefAppraiser','سرارزیاب ها:',['class'=>'col-12'])
</div>

<button type="submit" class="submit-form-btn">بروزرسانی اطلاعات</button>
</div>
</div>
</div>
</div>
</form>
</section>

@endsection
