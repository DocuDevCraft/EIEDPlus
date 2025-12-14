@extends('dashboard::layouts.dashboard.master')

@section('title','تعیین سطح فریلنسرها')

@section('title-page')
    <span class="icon" style="width: 30px;"><img src="{{ asset('/modules/freelancergrade/images/icons/freelancer-grade.gif') }}"></span>
    <span class="text">نمره فنی فریلنسرها</span>
@endsection

@section('content')
    <section class="report-table">
        @if(count($FreelancerGrade))
            <div class="row">
                <div class="col-12">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="form-style small-filter">
                                <form action="{{ url('dashboard/freelancer-grade') }}" method="get" name="search">
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
                            <form action="{{ url('dashboard/freelancer-grade-delete') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
                                @csrf
                                <table class="table align-items-center">
                                    <thead>
                                    <tr>
                                        @can('isAdmin')
                                            <th class="delete-col">
                                                <input class="select-all" type="checkbox">
                                            </th>
                                        @endcan
                                        <th>نام و نام خانوادگی</th>
                                        <th>بخش</th>
                                        <th>امتیاز ارزیاب</th>
                                        <th>امتیاز سرارزیاب</th>
                                        <th>نمره کلی</th>
                                        @canany(['isChiefAppraiser', 'isAppraiser'])
                                            <th width="80px" class="center">عملیات</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($FreelancerGrade as $item)
                                        <tr>
                                            @can('isAdmin')
                                                <td class="delete-col">
                                                    <input class="delete-checkbox" type="checkbox" name="delete_item[{{ $item->id }}]" value="1">
                                                </td>
                                            @endcan
                                            <td>{{ $item->users->first_name . ' ' . $item->users->last_name }}</td>
                                            <td class="num-fa">{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('section', $item->section_id) }}</td>
                                            <td class="num-fa">{{ $item->suggest_grade ?: 'در انتظار تعیین سطح' }}</td>
                                            <td class="num-fa">{{ $item->grade ?: 'در انتظار تعیین سطح' }}</td>
                                            <td class="num-fa">{{ $item->final_grade ?: '-' }}</td>
                                            @canany(['isChiefAppraiser', 'isAppraiser'])
                                                <td class="center">
                                                    <a href="{{ route('freelancer-grade.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-edit"></i></a>
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
                                        <th>نام و نام خانوادگی</th>
                                        <th>بخش</th>
                                        <th>امتیاز ارزیاب</th>
                                        <th>امتیاز سرارزیاب</th>
                                        <th>نمره کلی</th>
                                        @canany(['isChiefAppraiser', 'isAppraiser'])
                                            <th width="80px" class="center">عملیات</th>
                                        @endcan
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" colspan="20">
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    نمایش موارد {{ $FreelancerGrade->firstItem() }} تا {{ $FreelancerGrade->lastItem() }} از {{ $FreelancerGrade->total() }} مورد (صفحه {{ $FreelancerGrade->currentPage() }} از {{ $FreelancerGrade->lastPage() }})
                                                </div>
                                                <div class="col-8 left">
                                                    <div class="pagination-table">
                                                        {{$FreelancerGrade->onEachSide(0)->links('vendor.pagination.default')}}
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
