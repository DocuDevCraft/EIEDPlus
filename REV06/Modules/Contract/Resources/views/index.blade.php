@extends('dashboard::layouts.dashboard.master')

@section('title','قراردادها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/contract/images/icons/contract.gif') }}"></span>
    <span class="text">قراردادها</span>
@endsection

@section('content')
    <section class="report-table">
        @if(count($Contract))
            <div class="row">
                <div class="col-12">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="form-style small-filter">
                                <form action="{{ url('dashboard/contract') }}" method="get" name="search">
                                    <div class="row align-items-end">
                                        <div class="col-3 field-block">
                                            <input class="text-input" value="@isset($_GET['search']){{ $_GET['search'] }}@endisset" id="search" type="text" name="search" placeholder="نام و یا ایمیل را وارد نمایید">
                                        </div>

                                        <div class="col-auto submit-field">
                                            <button type="submit">
                                                <span class="zmdi zmdi-search"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="widget-content">
                            <form action="{{ url('dashboard/contract/destroy') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
                                @csrf
                                <table class="table align-items-center">
                                    <thead>
                                    <tr>
                                        @can('isAdmin')
                                            <th class="delete-col">
                                                <input class="select-all" type="checkbox">
                                            </th>
                                        @endcan
                                        <th>نام بسته کاری</th>
                                        <th>نام و نام خانوادگی</th>
                                        <th>وضعیت</th>
                                        <th>بروزرسانی</th>
                                        @can('isAdmin')
                                            <th width="80px" class="center">عملیات</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Contract as $item)
                                        <tr>
                                            @can('isAdmin')
                                                <td class="delete-col">
                                                    <input class="delete-checkbox" type="checkbox" name="delete_item[{{ $item->id }}]" value="1">
                                                </td>
                                            @endcan
                                            <td>{{ $item->workPackage->title }}</td>
                                            <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                                            <td class="num-fa">
                                                <span class="tag-item" style="{{ $item->status == 'freelancer_signed' ? 'color: #ff6c00;background-color: #ff6c0010;' :'' }}">
                                                    @switch($item->status)
                                                        @case('no_sign') بدون امضا
                                                        @break
                                                        @case('freelancer_signed') فریلنسر امضا کرده است
                                                        @break
                                                        @case('employer_signed') کارفرما امضا کرده است
                                                        @break
                                                        @defaultبدون وضعیت
                                                        @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="num-fa">{{ \Morilog\Jalali\Jalalian::forge($item->updated_at)->format('H:i - Y/m/d') }}</td>
                                            @if($item->status !== 'no_sign')
                                                @can('isAdmin')
                                                    <td class="center">
                                                        <a href="{{ route('contract.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-eye"></i></a>
                                                    </td>
                                                @endcan
                                            @endif
                                        </tr>
                                    @endforeach

                                    </tbody>
                                    <tfoot class="num-fa">
                                    <tr class="titles">
                                        @can('isAdmin')
                                            <th class="delete-col">
                                                <button class="table-btn table-btn-icon table-btn-icon-delete">
                                                    <span><img src="{{ asset('/modules/dashboard/admin/img/base/icons/trash.svg') }}" alt="شناسه" title="حذف"></span>
                                                </button>
                                            </th>
                                        @endcan
                                        <th>نام بسته کاری</th>
                                        <th>نام و نام خانوادگی</th>
                                        <th>وضعیت</th>
                                        <th>بروزرسانی</th>
                                        @can('isAdmin')
                                            <th width="80px" class="center">عملیات</th>
                                        @endcan
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" colspan="20">
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    نمایش موارد {{ $Contract->firstItem() }} تا {{ $Contract->lastItem() }} از {{ $Contract->total() }} مورد (صفحه {{ $Contract->currentPage() }} از {{ $Contract->lastPage() }})
                                                </div>
                                                <div class="col-8 left">
                                                    <div class="pagination-table">
                                                        {{$Contract->onEachSide(0)->links('vendor.pagination.default')}}
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
                </div>
            </div>
        @else
            <div class="widget-block widget-item widget-style center no-item">
                <div class="icon"><img src="{{ asset('/modules/dashboard/admin/img/base/icons/no-item.svg') }}"></div>
                <h2>هیچ موردی یافت نشد!</h2>
            </div>
        @endif
    </section>
@endsection
