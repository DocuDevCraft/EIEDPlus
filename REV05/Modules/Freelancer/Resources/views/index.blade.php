@extends('dashboard::layouts.dashboard.master')

@section('title','فریلنسرها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/freelancer/images/icons/freelancer.gif') }}"></span>
    <span class="text">فریلنسرها</span>
@endsection

@section('content')
    <section class="report-table">
        @if(count($Freelancer))
            <div class="row">
                <div class="col-12">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="form-style small-filter">
                                <form action="{{ url('dashboard/freelancer') }}" method="get" name="search">
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
                            <form action="{{ route('freelancer.hourly-contract.send') }}" method="post" onsubmit="return confirm('<?php echo "آیا از موارد انتخاب شده مطمئن هستید؟";?>');">
                                @csrf

                                <div class="flex p-3">
                                    <button type="submit" style="font-weight: 400; height: auto; width: auto; padding: 4px 10px !important" class="submit-form-btn create-btn">ارسال قرارداد نفر/ساعت</button>
                                </div>

                                <table class="table align-items-center">
                                    <thead>
                                    <tr>
                                        <th class="delete-col" style="width: 100px">
                                            <input class="select-all" type="checkbox">
                                        </th>
                                        <th>نام و نام خانوادگی</th>
                                        <th>بخش</th>
                                        <th>قرارداد نفرساعت</th>
                                        <th>تاریخ عضویت</th>
                                        <th width="80px" class="center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Freelancer as $item)
                                        <tr>
                                            <td class="delete-col" style="width: 100px">
                                                @if($item->hourly_contract == 'no')
                                                    <input class="delete-checkbox" type="checkbox" name="item[{{ $item->id }}]" value="1">
                                                @endif
                                            </td>
                                            <td>{{ $item->users->first_name . ' ' . $item->users->last_name }}</td>
                                            <td class="num-fa">
                                                @php
                                                    $uniqueSections = $item->FreelancerSection
                                                        ->filter(fn($fs) => $fs->sectionTable)
                                                        ->unique('section_id');
                                                @endphp

                                                @if($uniqueSections->isNotEmpty())
                                                    @foreach ($uniqueSections as $fs)
                                                        <li class="text-uppercase d-inline-block" style="font-size: 11px; color: #444444; background-color: #EEEEEE; margin-left: 4px; padding: 0 4px; border-radius: 3px">{{ $fs->sectionTable->title }}</li>
                                                    @endforeach
                                                @else
                                                    <li>بدون بخش</li>
                                                @endif
                                            </td>
                                            <td class="num-fa">
                                                <span style="font-size: 11px; color: {{ \App\Http\Controllers\HomeController::FreelancerHourlyContractConvertStatus($item->hourly_contract)[1] }}; background-color: {{ \App\Http\Controllers\HomeController::FreelancerHourlyContractConvertStatus($item->hourly_contract)[1] }}16; margin-left: 4px; padding: 0 4px; border-radius: 3px">{{ \App\Http\Controllers\HomeController::FreelancerHourlyContractConvertStatus($item->hourly_contract)[0] }}</span>
                                            </td>
                                            <td class="num-fa">{{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td class="center d-flex justify-content-end">
                                                <a href="{{ route('freelancer.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                    <tfoot class="num-fa">
                                    <tr class="titles">
                                        <th class="delete-col">
                                        </th>
                                        <th>نام و نام خانوادگی</th>
                                        <th>بخش</th>
                                        <th>قرارداد نفرساعت</th>
                                        <th>تاریخ عضویت</th>
                                        <th width="80px" class="center">عملیات</th>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;" colspan="20">
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    نمایش موارد {{ $Freelancer->firstItem() }} تا {{ $Freelancer->lastItem() }} از {{ $Freelancer->total() }} مورد (صفحه {{ $Freelancer->currentPage() }} از {{ $Freelancer->lastPage() }})
                                                </div>
                                                <div class="col-8 left">
                                                    <div class="pagination-table">
                                                        {{$Freelancer->onEachSide(0)->links('vendor.pagination.default')}}
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
