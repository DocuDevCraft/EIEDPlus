@extends('dashboard::layouts.dashboard.master')

@section('title','مدیریت بخش ها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">مدیریت بخش ها</span>
@endsection

@section('content')
    <section class="report-table product-cat-pages">
        <div class="row">
            <div class="col-4 create-col form-section">
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">افزودن بخش</span>
                    </div>
                    <div class="widget-content widget-content-padding">
                        <form action="{{ route('section.store') }}" method="POST">
                            @csrf
                            <div class="form-group row no-gutters">
                                @if($errors->has('title'))
                                    <span class="col-12 message-show">{{ $errors->first('title') }}</span>
                                @endif
                                {{ Form::text('title',null,[ 'id'=>'title' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'عنوان بخش را وارد نمایید']) }}
                                {{ Form::label('title','عنوان بخش:',['class'=>'col-12']) }}
                            </div>
                            <div class="form-group row no-gutters">
                                @if($errors->has('code'))
                                    <span class="col-12 message-show">{{ $errors->first('code') }}</span>
                                @endif
                                {{ Form::text('code',null,[ 'id'=>'code' , 'class'=>'col-12 field-style input-text text-right', 'dir'=>'ltr', 'placeholder'=>'کد اختصاری بخش را وارد نمایید']) }}
                                {{ Form::label('code','کد اختصاری بخش (en):',['class'=>'col-12']) }}
                            </div>
                            <div class="form-group row no-gutters">
                                <div class="col-12 field-style">
                                    <select multiple data-placeholder="مدیران را انتخاب کنید..." class="form-control chosen-rtl select" name="manager[]" id="manager">
                                        @forelse($SectionManager as $item)
                                            <option value="{{ $item->id }}" {{ in_array($item->id, old('manager') ?: [] ) ? 'selected' : '' }}>{{ $item->first_name . ' ' . $item->last_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                {{ Form::label('manager','مدیران بخش:',['class'=>'col-12']) }}
                            </div>
                            <div class="form-group row no-gutters">
                                <div class="col-12 field-style">
                                    <select multiple data-placeholder="سرارزیاب ها را انتخاب کنید..." class="form-control chosen-rtl select" name="chiefAppraiser[]" id="chiefAppraiser">
                                        @forelse($ChiefAppraiser as $item)
                                            <option value="{{ $item->id }}" {{ in_array($item->id, old('chiefAppraiser') ?: [] ) ? 'selected' : '' }}>{{ $item->first_name . ' ' . $item->last_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                {{ Form::label('chiefAppraiser','سرارزیاب ها:',['class'=>'col-12']) }}
                            </div>
                            <button type="submit" class="submit-form-btn ">افزودن بخش</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                @if(count($Section))
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <span class="widget-title">بخش ها</span>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content">
                            <form action="{{ route('section.multi.destroy') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
                                @csrf
                                <table class="table align-items-center">
                                    <thead>
                                    <tr>
                                        <th class="delete-col">
                                            <input class="select-all" type="checkbox">
                                        </th>
                                        <th>عنوان</th>
                                        <th>مدیران</th>
                                        <th>سرارزیاب ها</th>
                                        <th width="80px" class="icon-t center">
                                            <span><img src="{{ asset('/modules/dashboard/admin/img/base/icons') }}/gear.svg" alt="شناسه" title="شناسه"></span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Section as $item)
                                        <tr>
                                            <td class="delete-col">
                                                <input class="delete-checkbox" type="checkbox" name="delete_item[{{ $item->id }}]" value="1">
                                            </td>
                                            <td>
                                                {{ $item->title }}
                                                @if($item->code)
                                                    <span class="text-uppercase" style="font-size: 11px; color: #444444; background-color: #EEEEEE; margin-left: 4px; padding: 0 4px; border-radius: 3px">{{ $item->code }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @forelse($item->Users()->get() as $user)
                                                    <span style="font-size: 11px; color: #444444; background-color: #EEEEEE; margin-left: 4px; padding: 0 4px; border-radius: 3px">
                                                    {{ $user->first_name . ' ' . $user->last_name }}
                                                    </span>
                                                @empty
                                                @endforelse
                                            </td>
                                            <td>
                                                @forelse($item->ChiefAppraiser()->get() as $user)
                                                    <span style="font-size: 11px; color: #444444; background-color: #EEEEEE; margin-left: 4px; padding: 0 4px; border-radius: 3px">
                                                    {{ $user->first_name . ' ' . $user->last_name }}
                                                    </span>
                                                @empty
                                                @endforelse
                                            </td>
                                            <td class="center">
                                                <a href="{{ route('section.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-edit"></i></a>
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
                                        <th>مدیران</th>
                                        <th>سرارزیاب ها</th>
                                        <th width="80px" class="icon-t center">
                                            <span><img src="{{ asset('/modules/dashboard/admin/img/base/icons/gear.svg') }}" alt="شناسه" title="شناسه"></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" colspan="8">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col-2">
                                                    {{ $Section->total() }} مورد
                                                </div>
                                                <div class="col-10 left">
                                                    <div class="pagination-table">
                                                        {{$Section->appends(request()->input())->links('vendor.pagination.default')}}
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
