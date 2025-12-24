@extends('dashboard::layouts.dashboard.master')

@section('title','ویرایش زیر بخش')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">ویرایش زیر بخش</span>
@endsection

@section('content')
    <section class="form-section">
        <form action="{{ route('subsection.update', $Subsection->id) }}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="row">
                                <div class="col-9">
                                    <span class="widget-title">ویرایش زیر بخش</span>
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
    {{ Form::text('title',$Subsection->title,[ 'id'=>'title' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'عنوان زیر بخش را وارد نمایید', 'dir' => 'auto'])
    {{ Form::label('title','عنوان زیر بخش:',['class'=>'col-12'])
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
        <select data-placeholder="بخش را انتخاب کنید..." class="form-control chosen-rtl select" name="section" id="section">
            @forelse($Section as $item)
                <option value="{{ $item->id }}" {{ $item->id == (old('section') ?: $Subsection->section_id ?: '') ? 'selected' : '' }}>{{ $item->title }}</option>
            @empty
            @endforelse
        </select>
    </div>
    {{ Form::label('section','بخش:',['class'=>'col-12'])
</div>

<div class="form-group row no-gutters">
    <div class="col-12 field-style">
        <select multiple data-placeholder="مسئول بسته کاری را انتخاب کنید..." class="form-control chosen-rtl select" name="manager[]" id="manager">
            @forelse($SectionManager as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, old('manager') ?: $Subsection->Users()->pluck('id')->toArray() ?: [] ) ? 'selected' : '' }}>{{ $item->first_name . ' ' . $item->last_name }}</option>
            @empty
            @endforelse
        </select>
    </div>
    {{ Form::label('manager','مسئول بسته کاری:',['class'=>'col-12'])
</div>

<div class="form-group row no-gutters">
    <div class="col-12 field-style">
        <select multiple data-placeholder="ارزیاب ها را انتخاب کنید..." class="form-control chosen-rtl select" name="appraiser[]" id="appraiser">
            @forelse($Appraiser as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, old('appraiser') ?: $Subsection->Appraiser()->pluck('id')->toArray() ?: [] ) ? 'selected' : '' }}>{{ $item->first_name . ' ' . $item->last_name }}</option>
            @empty
            @endforelse
        </select>
    </div>
    {{ Form::label('appraiser','ارزیاب ها:',['class'=>'col-12'])
</div>

<button type="submit" class="submit-form-btn">بروزرسانی اطلاعات</button>
</div>
</div>
</div>
</div>
</form>
</section>

@endsection
