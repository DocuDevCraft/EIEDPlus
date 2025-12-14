@extends('dashboard::layouts.dashboard.master')

@section('title','بسته های کاری')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">بسته های کاری</span>
@endsection

@section('content')
    <section class="report-table">
        @if(count($WorkPackage))
            <div class="widget-block widget-item widget-style">
                <div class="heading-widget">
                    <div class="form-style small-filter">
                        <form action="{{ url('dashboard/freelancer') }}" method="get" name="search">
                            <div class="row">
                                <div class="col-10">
                                    {{--                                    <div class="row align-items-end">--}}
                                    {{--                                        <div class="col-3 field-block">--}}
                                    {{--                                            <input class="text-input" value="@isset($_GET['search']){{ $_GET['search'] }}@endisset" id="search" type="text" name="search" placeholder="نام و یا ایمیل را وارد نمایید">--}}
                                    {{--                                        </div>--}}

                                    {{--                                        <div class="col-auto submit-field">--}}
                                    {{--                                            <button type="submit">--}}
                                    {{--                                                <span class="zmdi zmdi-search"></span>--}}
                                    {{--                                            </button>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                                @can('isWorkPackageManager')
                                    <div class="col-2 left">
                                        <a href="{{ url('dashboard/work-package/create') }}" class="submit-form-btn">افزودن بسته کاری</a>
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>

                <div class="widget-content">
                    <form action="{{ url('dashboard/work-package/destroy') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
                        @csrf
                        <table class="table align-items-center">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <th>عنوان بسته کاری</th>
                                <th>نوع قرارداد</th>
                                <th>اندازه</th>
                                <th>بخش</th>
                                <th>وضعیت</th>
                                <th>انتشار</th>
                                <th>پایان مناقصه</th>
                                <th width="170px" class="center">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($WorkPackage as $item)
                                <tr>
                                    <td><a href="{{ route('work-package.edit', $item->id) }}">{{ $item->unique_id }}</a></td>
                                    <td><a href="{{ route('work-package.edit', $item->id) }}">{{ $item->title }}
                                            @if(is_array(json_decode($item->tag, true)))
                                                @forelse(json_decode($item->tag, true) as $tagItem)
                                                @empty($tagItem)
                                                @else
                                                    <span class="tag-item">{{$tagItem}}</span>
                                                @endempty
                                                @empty
                                                @endforelse
                                            @endif
                                        </a>
                                    </td>
                                    <td><span class="tag-item" style="color: {{ \App\Http\Controllers\HomeController::WorkPackageTypeConvertStatus($item->work_package_type)[1] }}; background: {{ \App\Http\Controllers\HomeController::WorkPackageTypeConvertStatus($item->work_package_type)[1] }}16">{{ \App\Http\Controllers\HomeController::WorkPackageTypeConvertStatus($item->work_package_type)[0] }}</span></td>
                                    <td>{{ $item->work_package_scale ? \App\Http\Controllers\HomeController::WorkPackageConvertScale($item->work_package_scale)[0] : '-' }}</td>
                                    <td class="num-fa">{{ $item->Section->title }} ({{ $item->subsection_id ? $item->Subsection->title : '-' }})</td>
                                    <td class="num-fa" style="color: {{ \App\Http\Controllers\HomeController::ConvertWorkPackageStatus($item->status)[1] }}">{{ \App\Http\Controllers\HomeController::ConvertWorkPackageStatus($item->status)[0] }}</td>
                                    <td class="num-fa">{{ $item->published_at ? \Morilog\Jalali\Jalalian::forge($item->published_at)->format('Y/m/d') : '-' }}</td>
                                    <td class="num-fa">{{ $item->published_at && $item->offer_time ? \Morilog\Jalali\Jalalian::forge($item->published_at)->addDays($item->offer_time)->format('Y/m/d') : '-' }}</td>
                                    <td class="left">
                                        <a href="{{ route('work-package.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-edit"></i></a>
                                        @if($item->status === 'completed')
                                            <a href="{{ route('WorkPackageFreelancerGrade.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-chart"></i></a>
                                        @else
                                            <a href="{{ url('dashboard/work-package-chat/' . $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-comments"></i></a>
                                            @if($item->status === 'activated')
                                                <a href="{{ url('dashboard/work-package-task/' . $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-inbox"></i></a>
                                            @else
                                                @cannot('isWorkPackageManagerOnly')
                                                    <a href="{{ route('freelancer.offer.checkAccess', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit position-relative">@if($item->freelancer_offers->count())
                                                            <span class="offer-badge num-fa">{{ $item->freelancer_offers->count() }}</span>
                                                        @endif<i class="zmdi zmdi-account-box-mail"></i></a>
                                                @endcannot
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot class="num-fa">
                            <tr class="titles">
                                <td>شناسه</td>
                                <th>عنوان بسته کاری</th>
                                <th>نوع قرارداد</th>
                                <th>اندازه</th>
                                <th>بخش</th>
                                <th>وضعیت</th>
                                <th>انتشار</th>
                                <th>پایان مناقصه</th>
                                <th width="100px" class="center">عملیات</th>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;" colspan="20">
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            نمایش موارد {{ $WorkPackage->firstItem() }} تا {{ $WorkPackage->lastItem() }} از {{ $WorkPackage->total() }} مورد (صفحه {{ $WorkPackage->currentPage() }} از {{ $WorkPackage->lastPage() }})
                                        </div>
                                        <div class="col-8 left">
                                            <div class="pagination-table">
                                                {{$WorkPackage->onEachSide(0)->links('vendor.pagination.default')}}
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
                <div class="icon"><img src="{{ asset('/modules/dashboard/admin/img/base/icons/no-item.svg') }}"></div>
                <h2>هیچ موردی یافت نشد!</h2>
                <div class="create-item"><a href="{{ url()->current() }}/create">افزودن مورد جدید</a></div>
            </div>
        @endif
    </section>
@endsection
