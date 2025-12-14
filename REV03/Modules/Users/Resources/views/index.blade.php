@extends('dashboard::layouts.dashboard.master')

@section('title','لیست کاربران')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/users/images/icons/user.gif') }}"></span>
    <span class="text">مدیریت کاربران</span>
@endsection

@section('content')
    <section class="report-table">
        @if(count($Users))
        <div class="row">
            <div class="col-12">
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <div class="form-style small-filter">
                                    <form action="{{ url('dashboard/users') }}" method="get" name="search">
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
                            <div class="col-2 left">
                                <a href="{{ url('dashboard/users/create') }}" class="submit-form-btn">افزودن کاربر جدید</a>
                            </div>
                        </div>
                    </div>

                    <div class="widget-content">
                        <form action="{{ url('dashboard/users/destroy') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
                            @csrf
                            <table class="table align-items-center">
                                <thead>
                                <tr>
                                    @can('isAdmin')
                                        <th class="delete-col">
                                            <input class="select-all" type="checkbox">
                                        </th>
                                    @endcan
                                    <th>شناسه</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th>نقش</th>
                                    <th>تاریخ عضویت</th>
                                    @can('isAdmin')
                                        <th width="80px" class="center">عملیات</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($Users as $item)
                                    <tr>
                                        @can('isAdmin')
                                            <td class="delete-col">
                                                <input class="delete-checkbox" type="checkbox" name="delete_item[{{ $item->id }}]" value="1">
                                            </td>
                                        @endcan
                                        <td class="num-fa">{{ $item->id }}</td>
                                        <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                                        <td>{{ \App\Http\Controllers\HomeController::RoleTranslation($item->role) }}</td>
                                        <td class="num-fa">{{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('H:i - Y/m/d') }}</td>
                                        @can('isAdmin')
                                            <td class="center">
                                                <a href="{{ route('users.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-edit"></i></a>
                                            </td>
                                        @endcan
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
                                    <th>شناسه</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th>نقش</th>
                                    <th>تاریخ عضویت</th>
                                    @can('isAdmin')
                                        <th width="80px" class="center">عملیات</th>
                                    @endcan
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;" colspan="20">
                                        <div class="row align-items-center">
                                            <div class="col-4">
                                                نمایش موارد {{ $Users->firstItem() }} تا {{ $Users->lastItem() }} از {{ $Users->total() }} مورد (صفحه {{ $Users->currentPage() }} از {{ $Users->lastPage() }})
                                            </div>
                                            <div class="col-8 left">
                                                <div class="pagination-table">
                                                    {{$Users->onEachSide(0)->links('vendor.pagination.default')}}
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
