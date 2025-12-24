@extends('dashboard::layouts.dashboard.master')

@section('title','مدیریت زیر بخش ها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">مدیریت قسمت ها</span>
@endsection

@section('content')
    <section class="report-table product-cat-pages">
        <div class="row">
            <div class="col-4 create-col form-section">
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">افزودن قسمت</span>
                    </div>
                    <div class="widget-content widget-content-padding">
                        <form action="{{ route('division.store') }}" method="POST">
                            @csrf
                            <div class="form-group row no-gutters">
                                @if($errors->has('title'))
                                    <span class="col-12 message-show">{{ $errors->first('title') }}</span>
    @endif
    {{ Form::text('title',null,[ 'id'=>'title' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'عنوان قسمت را وارد نمایید'])
    {{ Form::label('title','عنوان قسمت:',['class'=>'col-12'])
</div>
<div class="form-group row no-gutters">
    <div class="col-12 field-style">
        <select data-placeholder="زیر بخش را انتخاب کنید..." class="form-control chosen-rtl select" name="subsection" id="subsection">
            @forelse($Subsection as $item)
                <option value="{{ $item->id }}" {{ $item->id == old('subsection') ? 'selected' : '' }}>{{ $item->title }}</option>
            @empty
            @endforelse
        </select>
    </div>
    {{ Form::label('subsection','زیر بخش:',['class'=>'col-12'])
</div>

<button type="submit" class="submit-form-btn ">افزودن قسمت</button>
</form>
</div>
</div>
</div>
<div class="col-8">
@if(count($Division))
<div class="widget-block widget-item widget-style">
<div class="heading-widget">
<div class="row align-items-center">
    <div class="col-12">
        <span class="widget-title">قسمت ها</span>
    </div>
</div>
</div>
<div class="widget-content">
<form action="{{ route('division.multi.destroy') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
    @csrf
    <table class="table align-items-center">
        <thead>
        <tr>
            <th class="delete-col">
                <input class="select-all" type="checkbox">
            </th>
            <th>عنوان</th>
            <th>زیر بخش</th>
            <th width="80px" class="icon-t center">
                <span><img src="{{ asset('/modules/dashboard/admin/img/base/icons') }}/gear.svg" alt="شناسه" title="شناسه"></span>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($Division as $item)
            <tr>
                <td class="delete-col">
                    <input class="delete-checkbox" type="checkbox" name="delete_item[{{ $item->id }}]" value="1">
                </td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->Subsection->title }}</td>
                <td class="center">
                    <a href="{{ route('division.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-edit"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot class="num-fa">
        <tr class="titles">
            <th class="delete-col">
                <button class="table-btn table-btn-icon table-btn-icon-delete">
                    <span><img src="{{ asset('/modules/dashboard/admin/img/base/icons') }}/trash.svg" alt="شناسه" title="حذف"></span>
                </button>
            </th>
            <th>عنوان</th>
            <th>زیر بخش</th>
            <th width="80px" class="icon-t center">
                <span><img src="{{ asset('/modules/dashboard/admin/img/base/icons/gear.svg') }}" alt="شناسه" title="شناسه"></span>
            </th>
        </tr>
        <tr>
            <td style="vertical-align: middle;" colspan="8">
                <div class="row align-items-center no-gutters">
                    <div class="col-2">
                        {{ $Division->total() }} مورد
                    </div>
                    <div class="col-10 left">
                        <div class="pagination-table">
                            {{$Division->appends(request()->input())->links('vendor.pagination.default')}}
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</form>
</div>
</div>
@else
<div class="widget-block widget-item widget-style center no-item">
<div class="icon">
<img src="{{ asset('/modules/dashboard/admin/img/base/icons') }}/no-item.svg">
</div>
<h2>هیچ موردی یافت نشد!</h2>
</div>
@endif
</div>
</div>
</section>
@endsection
