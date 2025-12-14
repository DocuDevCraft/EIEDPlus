@extends('dashboard::layouts.dashboard.master')

@section('title','پیشنهاد فریلنسرها')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">پیشنهاد فریلنسرها</span>
@endsection

@section('content')
    <section class="report-table">
        @if(count($FreelancerOffer))
            <div class="widget-block widget-item widget-style">
                @if(auth()->user()->role === 'admin')
                    <div class="heading-widget">
                        <div class="form-style small-filter">
                            <div class='row align-items-center'>
                                <div class="col">
                                    <div class="row align-items-start mt-3">
                                        <div class="col-12 col-md-8 col-lg-6">
                                            <form action="{{ route('work-package-offer-export-pdf-upload', $ID) }}"
                                                  method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                                                @csrf

                                                <div class="input-group">
                                                    <input required type="file" name="offer_list" class="form-control" id="offer_list" style="font-size: 13px">
                                                    <button type="submit" class="submit-form-btn create-btn px-5 cursor-pointer" style="width: auto;">
                                                        آپلود فایل امضا پیشنهادات
                                                    </button>
                                                </div>

                                                @if($errors->has('offer_list'))
                                                    <span class="text-danger small mt-1">{{ $errors->first('offer_list') }}</span>
                                                @endif
                                            </form>
                                            @if($WorkPackage->offer_list_file)
                                                <a style="padding: 5px 10px; background: #ced4da; border-radius: 0 0 5px 5px; margin-right: 5px" target="_blank" href="{{ route('work-package-offer-pdf-download', $ID) }}">دانلود فایل امضا شده</a></li>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <form action="{{ route('work-package-offer-export-pdf', $ID) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="submit-form-btn px-5 cursor-pointer">دانلود لیست پیشنهادات</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @can('isAdmin')
                    <div class="py-2 px-4 d-flex border-bottom" style="gap: 10px">
                        @if($WorkPackage['work_package_scale'])
                            <strong class="num-fa">اندازه بسته کاری: {{ \App\Http\Controllers\HomeController::WorkPackageConvertScale($WorkPackage['work_package_scale'])[0] }}</strong>
                            -
                        @endif
                        <span class="num-fa">قیمت پیشنهادی: {{ number_format($WorkPackage['recommend_price']) }} تومان</span> -
                        <span class="num-fa">زمان پیشنهادی: {{ $WorkPackage['recommend_time'] }} روز</span>
                    </div>
                    <div class="py-2 px-4 d-flex border-bottom" style="gap: 10px">
                        <span class="num-fa">محدود قیمت قابل قبول: {{ number_format($WorkPackage['recommend_price'] - ($WorkPackage['recommend_price'] * 0.2) )}} تومان --- {{ number_format(($WorkPackage['recommend_price'] * 0.2) + $WorkPackage['recommend_price'] )}} تومان</span>
                    </div>
                @endcan
                <div class="py-2 px-4 d-flex" style="gap: 10px">
                    <span class="num-fa">تعداد مشاهده: {{$viewCount}}</span>
                </div>
            </div>


            <div class="widget-block widget-item widget-style">
                <div class="heading-widget">
                    <div class="form-style small-filter">
                        <div class='row align-items-center'>
                            <div class="col">
                                <div class="row align-self-start">
                                    <div class="col-auto">
                                        <form style="min-width: 200px" action="{{ url('dashboard/freelancer-offer/' . $WorkPackage->id) }}" method="get" name="filter">
                                            <div>
                                                <div class="row align-items-end">
                                                    <div class="col field-block">
                                                        <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="winning_formula" id="package_price_type">
                                                            <option></option>
                                                            <option value="lowest_price" @selected(old(
                                                            'winning_formula', $WorkPackage->winning_formula) == 'lowest_price')>کمترین قیمت
                                                            </option>
                                                            <option value="less_time" @selected(old(
                                                            'winning_formula', $WorkPackage->winning_formula) == 'less_time')>کمترین زمان
                                                            </option>
                                                        </select>

                                                        {{--                                            <input class="text-input" value="@isset($_GET['search']){{ $_GET['search'] }}@endisset" id="search" type="text" name="search" placeholder="نام و یا ایمیل را وارد نمایید">--}}
                                                    </div>

                                                    <div class="col-auto submit-field">
                                                        <button type="submit">
                                                            <span class="zmdi zmdi-search"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-auto">
                                        @if($WorkPackage->offer_list_status == null && isset($_GET['winning_formula']))
                                            <form action="{{ route('work-package-offer-sorting', $ID) }}" method="post">
                                                @csrf
                                                {{ method_field('PUT') }}
                                                <input type="hidden" name="winning_formula" value="{{ $_GET['winning_formula'] }}">
                                                <button type="submit" class="submit-form-btn px-3 cursor-pointer">اعمال اولیت</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div style="font-weight: bold;color: {{ \App\Http\Controllers\HomeController::ConvertWorkPackageOfferListStatus($WorkPackage->offer_list_status)[1] }}">{{\App\Http\Controllers\HomeController::ConvertWorkPackageOfferListStatus($WorkPackage->offer_list_status)[0]}}</div>
                            </div>
                            <div class="col-auto">
                                @if($WorkPackage->offer_list_status == null && auth()->user()->role === 'sectionManager')
                                    <form action="{{ route('work-package-offer-status', $ID) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="submit-form-btn create-btn px-5 cursor-pointer">تایید اولیه مدیر بخش</button>
                                    </form>
                                @endif

                                {{--                                @if($WorkPackage->offer_list_status !== 'accepted' && auth()->user()->role === 'admin')--}}
                                {{--                                    <form action="{{ route('work-package-offer-status', $ID) }}" method="POST" enctype="multipart/form-data">--}}
                                {{--                                        @csrf--}}
                                {{--                                        {{ method_field('PUT') }}--}}
                                {{--                                        <button type="submit" class="submit-form-btn create-btn px-5 cursor-pointer">تایید لیست</button>--}}
                                {{--                                    </form>--}}
                                {{--                                @endif--}}

                                @if($WorkPackage->offer_list_status == 'accepted' && auth()->user()->role === 'sectionManager')
                                    <form action="{{ route('work-package-offer-status', $ID) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="submit-form-btn create-btn px-5 cursor-pointer">قرارداد ارسال گردید</button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-content">
                    <form action="{{ url('dashboard/freelancer/destroy') }}" method="post" onsubmit="return confirm('<?php echo "آیا از حذف موارد انتخاب شده مطمئن هستید؟";?>');">
                        @csrf
                        <table class="table align-items-center">
                            <thead>
                            <tr>
                                @can('isAdmin')
                                    <th class="delete-col">
                                        <input class="select-all" type="checkbox">
                                    </th>
                                @endcan
                                <th>پیشنهاد دهنده</th>
                                @can('isAdmin')
                                    <th>پیشنهاد قیمت</th>
                                @endcan
                                <th>پیشنهاد زمانی</th>
                                <th>نمره فنی</th>
                                <th>پیوست</th>
                                <th style="min-width: 140px">وضعیت</th>
                                <th>زمان ثبت</th>
                                @can('isAdmin')
                                    @if($WorkPackage->offer_list_status == 'accepted')
                                        @if($WorkPackage->offer_list_file !== null || $WorkPackage->offer_list_file !== '' )
                                            <th width="80px" class="center">عملیات</th>
                                        @endif
                                    @endif
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($FreelancerOffer as $item)
                                <tr>
                                    @can('isAdmin')
                                        <td class="delete-col">
                                            <input class="delete-checkbox" type="checkbox" name="delete_item[{{ $item->id }}]" value="1">
                                        </td>
                                    @endcan
                                    <td class="num-fa">{{ $item->users->first_name . ' ' . $item->users->last_name }}</td>
                                    @can('isAdmin')
                                        <td class="num-fa">{{ number_format($item->price) }} تومان</td>
                                    @endcan
                                    <td class="num-fa">{{ $WorkPackage->work_package_type === 'hourly_contract' ? $item->time*8 : $item->time }}  {{ $WorkPackage->work_package_type === 'hourly_contract' ? 'ساعت' : 'روز' }}</td>
                                    <td class="num-fa">{{$item->gradeScore}}</td>
                                    <td class="num-fa">@isset($item->attachment)
                                            <a href="{{ asset('storage/work-package/offer'. '/' . \Modules\FileLibrary\Entities\FileLibrary::find($item->attachment)->file_name) }}" download="{{ \Modules\FileLibrary\Entities\FileLibrary::find($item->attachment)->org_name }}" class="">دانلود</a>
                                        @else
                                            بدون پیوست
                                        @endisset</td>
                                    <td class="num-fa" style="color: {{\App\Http\Controllers\HomeController::OfferConvertStatus($item->status)[1]}}">{{ \App\Http\Controllers\HomeController::OfferConvertStatus($item->status)[0] }}</td>
                                    <td class="num-fa">{{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('H:i - Y/m/d') }}</td>
                                    @can('isAdmin')
                                        @if($WorkPackage->offer_list_status == 'accepted')
                                            @if($WorkPackage->offer_list_file !== null || $WorkPackage->offer_list_file !== '' )
                                                <td class="center">
                                                    <a href="{{ route('freelancer-offer.edit', $item->id) }}" class="table-btn table-btn-icon table-btn-icon-edit"><i class="zmdi zmdi-eye"></i></a>
                                                </td>
                                            @endif
                                        @endif
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
                                <th>پیشنهاد دهنده</th>
                                @can('isAdmin')
                                    <th>پیشنهاد قیمت</th>
                                @endcan
                                <th>پیشنهاد زمانی</th>
                                <th>نمره فنی</th>
                                <th>پیوست</th>
                                <th>وضعیت</th>
                                <th>زمان ثبت</th>
                                @can('isAdmin')
                                    @if($WorkPackage->offer_list_status == 'accepted')
                                        @if($WorkPackage->offer_list_file !== null || $WorkPackage->offer_list_file !== '' )
                                            <th width="80px" class="center">عملیات</th>
                                        @endif
                                    @endif
                                @endcan
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;" colspan="20">
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            نمایش موارد {{ $FreelancerOffer->firstItem() }} تا {{ $FreelancerOffer->lastItem() }} از {{ $FreelancerOffer->total() }} مورد (صفحه {{ $FreelancerOffer->currentPage() }} از {{ $FreelancerOffer->lastPage() }})
                                        </div>
                                        <div class="col-8 left">
                                            <div class="pagination-table">
                                                {{$FreelancerOffer->onEachSide(0)->links('vendor.pagination.default')}}
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

            @if(count($FreelancerOffer) <= 2)
                <div class="num-fa" style="margin-bottom: 30px;background: #ffefd9; padding: 7px 10px; border-radius: 5px; display: flex; align-items: center; gap: 10px; color: #975600; border: solid 1px #eccfac; font-size: 14px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 430 430">
                        <path fill="#ffc738" d="M354 348.39H76a13 13 0 0 1-11.29-19.55l139-240.8a13 13 0 0 1 11.285-6.546 13 13 0 0 1 11.285 6.546l139 240.8a13.005 13.005 0 0 1-4.749 17.82 13 13 0 0 1-6.531 1.73"/>
                        <path fill="#ffc738" d="M104.71 348.39H76a13 13 0 0 1-11.29-19.55l139-240.8a13 13 0 0 1 17.818-4.797 13 13 0 0 1 4.762 4.797l3.08 5.34-136 235.46a13 13 0 0 0 11.34 19.55" opacity=".5" style="mix-blend-mode:multiply"/>
                        <path fill="#92140c" d="M230.85 300.98a15.848 15.848 0 0 1-27.057 11.208 15.852 15.852 0 0 1 2.402-24.387 15.851 15.851 0 0 1 24.655 13.179m3.5-112.95-2.81 57.89a13.87 13.87 0 0 1-13.83 13.17h-5.42a13.83 13.83 0 0 1-13.81-13.17l-2.83-57.89a16.4 16.4 0 0 1 4.491-12.102 16.4 16.4 0 0 1 5.406-3.766A16.4 16.4 0 0 1 212 170.83h6c2.219.003 4.414.456 6.453 1.332a16.4 16.4 0 0 1 8.893 9.356 16.4 16.4 0 0 1 1.004 6.512"/>
                        <path fill="#92140c" d="M221.07 315.63a15.85 15.85 0 0 1-20.03-7.182 15.86 15.86 0 0 1 0-14.936 15.85 15.85 0 0 1 20.03-7.182 15.86 15.86 0 0 0 0 29.3m0-56.95a13.8 13.8 0 0 1-3.36.41h-5.42a13.85 13.85 0 0 1-13.82-13.17l-2.82-57.89a16.4 16.4 0 0 1 4.491-12.102 16.4 16.4 0 0 1 5.406-3.766A16.4 16.4 0 0 1 212 170.83h6a15.7 15.7 0 0 1 3.09.3 16.38 16.38 0 0 0-13.28 16.9l2.82 57.89a13.88 13.88 0 0 0 10.44 12.76" opacity=".5" style="mix-blend-mode:multiply"/>
                    </svg>
                    با توجه به اینکه تعداد پیشنهادات فریلنسرها کمتر از 3 می باشد نیاز به ارجاع به کمیسیون معاملات دارد.
                </div>
            @endif
        @else
            <div class="widget-block widget-item widget-style center no-item">
                <div class="icon"><img src="{{ asset('/modules/dashboard/admin/img/base/icons/no-item.svg') }}"></div>
                <h2>هیچ موردی یافت نشد!</h2>
            </div>
        @endif
    </section>
@endsection
