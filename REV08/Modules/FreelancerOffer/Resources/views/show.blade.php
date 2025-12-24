@extends('dashboard::layouts.dashboard.master')

@section('title')
    مشاهده پیشنهاد
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/freelancer/images/icons/freelancer.gif') }}"></span>
    <span class="text">مشاهده پیشنهاد {{ $FreelancerOffer->first_name . ' ' . $FreelancerOffer->last_name }}</span>
@endsection

@section('content')
    @if($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif

    <section class="form-section">
        <form action="{{ route('freelancer-offer.update', $FreelancerOffer->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            <div class="row">
                <div class="col-9">
                    {{-- پیشنهاد --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">پیشنهاد</span>
                        </div>

                        <div class="widget-content widget-content-padding num-fa">
                            <div @class('row')>
                                <div class="col-4 mb-4">
                                    <div class="form-group row no-gutters">
                                        <strong class="mb-2">پیشنهاد دهنده:</strong>
                                        <div class='col-12'>{{ $FreelancerOffer->users->first_name . ' ' . $FreelancerOffer->users->last_name }}</div>
                                    </div>
                                </div>

                                <div class="col-4 mb-4">
                                    <div class="form-group row no-gutters">
                                        <strong class="mb-2">قیمت پیشنهادی:</strong>
                                        <div class='col-12'>{{ number_format($FreelancerOffer->price) }} تومان</div>
                                    </div>
                                </div>

                                <div class="col-4 mb-4">
                                    <div class="form-group row no-gutters">
                                        <strong class="mb-2">قیمت پیشنهادی:</strong>
                                        <div class='col-12'>{{ number_format($FreelancerOffer->time) }} روز</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- اطلاعات فنی --}}
                    @if($FreelancerSection)
                        <div class="widget-block widget-item widget-style">
                            <div class="heading-widget">
                                <span class="widget-title">اطلاعات فنی</span>
                            </div>

                            <div class="widget-content widget-content-padding num-fa last-child-border-none last-child-mb-0">
                                @foreach($FreelancerSection as $item)
                                    <div class="row border-bottom mb-4">
                                        <div class="col-3 mb-4">
                                            <div class="form-group row no-gutters">
                                                <strong class="mb-2">بخش:</strong>
                                                <div class='col-12'>{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('section', $item->section_id) }}</div>
                                            </div>
                                        </div>

                                        {{-- زیر بخش --}}
                                        @if($item->subsection_id)
                                            <div class="col-3 mb-4">
                                                <div class="form-group row no-gutters">
                                                    <strong class="mb-2">زیر بخش:</strong>
                                                    <div class='col-12'>{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('subsection', $item->subsection_id) }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- قسمت --}}
                                        @if($item->division_id)
                                            <div class="col-3 mb-4">
                                                <div class="form-group row no-gutters">
                                                    <strong class="mb-2">قسمت:</strong>
                                                    <div class='col-12'>{{ \Modules\SectionManager\Http\Controllers\SectionAPIHandlerController::getName('division', $item->division_id) }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- نمره فنی --}}
                                        @if($item->grade)
                                            <div class="col-3 mb-4">
                                                <div class="form-group row no-gutters">
                                                    <strong class="mb-2">نمره فنی:</strong>
                                                    <div class='col-12'>{{ $item->grade }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-3">
                    {{-- Publish Options --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت وضعیت</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <div class="form-group row no-gutters">
                                @if($errors->has('status'))
                                    <span class="message-show">{{ $errors->first('status') }}</span>
                                @endif
                                <div class="col-12 field-style ">
                                    <select data-placeholder="یک مورد را انتخاب کنید" id="status" class="select chosen-rtl" name="status">
                                        <option value="winner" @selected(old(
                                        "status", $FreelancerOffer->status) == "winner")> برنده شد
                                        </option>
                                        <option value="awaiting_signature" @selected(old(
                                        "status", $FreelancerOffer->status) == "awaiting_signature")>در انتظار امضا
                                        </option>
                                        <option value="pending" @selected(old(
                                        "status", $FreelancerOffer->status) == "pending")>در حال بررسی
                                        </option>
                                        <option value="rejected" @selected(old(
                                        "status", $FreelancerOffer->status) == "rejected")>رد شد
                                        </option>
                                    </select>
                                </div>
    {{ Form::label('status','وضعیت:',['class'=>'col-12'])
</div>

<button type="submit" class="submit-form-btn create-btn">ویرایش وضعیت</button>
</div>
</div>
</div>
</div>
</form>
</section>
@endsection
