@extends('dashboard::layouts.dashboard.master')

@section('title','افزودن بسته کاری')

@section('lib')
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">افزودن بسته کاری</span>
@endsection

@section('content')

    {{--        @if($errors->any())--}}
    {{--            @foreach($errors->all(':message') as $item)--}}
    {{--                <p>{{$item}}</p>--}}
    {{--            @endforeach--}}
    {{--        @endif--}}

    <section class="form-section">
        <form action="{{ route('work-package.store') }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    {{-- مشحصات کلی --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="row">
                                <div class="col-9">
                                    <span class="widget-title">مشخصات کلی</span>
                                </div>
                                <div class="col-3 left"></div>
                            </div>
                        </div>

                        <div class="widget-content widget-content-padding">
                            @csrf
                            <div class="form-group row no-gutters ">
                                @if($errors->has('package_number'))
                                    <span class="col-12 message-show">{{ $errors->first('package_number') }}</span>
                                @endif
                                {!! Form::text('package_number',null,[ 'id'=>'package_number' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'شماره بسته کاری را وارد نمایید']) !!}
                                {!! Form::label('package_number','شماره بسته کاری:',['class'=>'col-12']) !!}
                            </div>

                            <div class="form-group row no-gutters">
                                @if($errors->has('title'))
                                    <span class="col-12 message-show">{{ $errors->first('title') }}</span>
                                @endif
                                {!! Form::text('title',null,[ 'id'=>'title' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'عنوان بسته کاری را وارد نمایید']) !!}
                                {!! Form::label('title','عنوان بسته کاری:',['class'=>'col-12']) !!}
                            </div>

                            <div class="form-group">
                                <div style="margin-bottom: 10px;">{!! Form::label('desc','شرح بسته کاری') !!}
                                    <span class="required">(اختیاری)</span></div>
                                <textarea class="field-style input-text" id="desc" name="desc" placeholder="شرح بسته کاری را وارد نمایید">{{ old('desc') }}</textarea>
                                @if($errors->has('desc'))
                                    <span class="message-show">{{ $errors->first('desc') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div style="margin-bottom: 10px;">{!! Form::label('rules','نکات خاص') !!}
                                    <span class="required">(اختیاری)</span></div>
                                <textarea class="field-style input-text" id="rules" name="rules" placeholder="نکات خاص را وارد نمایید">{{ old('rules') }}</textarea>
                                @if($errors->has('rules'))
                                    <span class="message-show">{{ $errors->first('rules') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- اطلاعات بسته کاری --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">اطلاعات بسته کاری</span>
                        </div>

                        <div class="widget-content widget-content-padding">
                            <div class="row">
                                {{-- نفر ساعت --}}
                                <div class="col-4 mb-5">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('man_hour'))
                                            <span class="col-12 message-show">{{ $errors->first('man_hour') }}</span>
                                        @endif
                                        {!! Form::text('man_hour',null,[ 'id'=>'man_hour' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'نفر ساعت را وارد نمایید']) !!}
                                        {!! Form::label('man_hour','نفر ساعت:',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- حداقل نمره قبولی --}}
                                <div class="col-4 mb-5">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('minimum_technical_grade'))
                                            <span class="col-12 message-show">{{ $errors->first('minimum_technical_grade') }}</span>
                                        @endif
                                        {!! Form::text('minimum_technical_grade',null,[ 'id'=>'minimum_technical_grade' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'حداقل نمره قبولی را وارد نمایید']) !!}
                                        {!! Form::label('minimum_technical_grade','حداقل نمره قبولی:',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- ارشدیت --}}
                                <div class="col-4 mb-5">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('seniority'))
                                            <span class="col-12 message-show">{{ $errors->first('seniority') }}</span>
                                        @endif
                                        <div class="col-12 field-style">
                                            <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="seniority" id="seniority">
                                                <option></option>
                                                <option value="senior" @selected(old(
                                                'seniority') == 'senior')>Senior
                                                </option>
                                                <option value="junior" @selected(old(
                                                'seniority') == 'junior')>Junior
                                                </option>
                                            </select>
                                        </div>

                                        {!! Form::label('seniority','ارشدیت',['class'=>'col-12']) !!}
                                    </div>
                                </div>


                                {{-- نوع زمان بسته کاری --}}
                                <div class="col-4 mb-5">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('package_time_type'))
                                            <span class="col-12 message-show">{{ $errors->first('package_time_type') }}</span>
                                        @endif
                                        <div class="col-12 field-style">
                                            <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="package_time_type" id="package_time_type">
                                                <option></option>
                                                <option value="ثــابت" @selected(old(
                                                'package_time_type') == 'ثــابت')>ثــابت
                                                </option>
                                                <option value="مناقصه" @selected(old(
                                                'package_time_type') == 'مناقصه')>مناقصه
                                                </option>
                                            </select>
                                        </div>

                                        {!! Form::label('package_time_type','نوع زمان بسته کاری',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- نوع قیمت بسته کاری --}}
                                <div class="col-4 mb-5">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('package_price_type'))
                                            <span class="col-12 message-show">{{ $errors->first('package_price_type') }}</span>
                                        @endif
                                        <div class="col-12 field-style">
                                            <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="package_price_type" id="package_price_type">
                                                <option></option>
                                                <option value="ثــابت" @selected(old(
                                                'package_price_type') == 'ثــابت')>ثــابت
                                                </option>
                                                <option value="مناقصه" @selected(old(
                                                'package_price_type') == 'مناقصه')>مناقصه
                                                </option>
                                            </select>
                                        </div>

                                        {!! Form::label('package_price_type','نوع قیمت بسته کاری',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- زمان پیشنهادی کارفرما --}}
                                <div class="col-4 mb-5">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('recommend_time'))
                                            <span class="col-12 message-show">{{ $errors->first('recommend_time') }}</span>
                                        @endif
                                        {!! Form::text('recommend_time',null,[ 'id'=>'recommend_time' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'زمان پیشنهادی کارفرما را وارد نمایید']) !!}
                                        {!! Form::label('recommend_time','زمان پیشنهادی کارفرما (روز):',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- قیمت پیشنهادی کارفرما --}}
                                <div class="col-4">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('recommend_price'))
                                            <span class="col-12 message-show">{{ $errors->first('recommend_price') }}</span>
                                        @endif
                                        {!! Form::text('recommend_price',null,[ 'id'=>'recommend_price' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'قیمت پیشنهادی کارفرما را وارد نمایید']) !!}
                                        {!! Form::label('recommend_price','قیمت پیشنهادی کارفرما (تومان):',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- فرمول برنده بسته کاری --}}
                                <div class="col-4">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('winning_formula'))
                                            <span class="col-12 message-show">{{ $errors->first('winning_formula') }}</span>
                                        @endif
                                        <div class="col-12 field-style">
                                            <select data-placeholder="یک مورد را انتخاب کنید..." class="form-control chosen-rtl select" name="winning_formula" id="package_price_type">
                                                <option></option>
                                                <option value="lowest_price" @selected(old(
                                                'winning_formula') == 'lowest_price')>کمترین قیمت
                                                </option>
                                                <option value="less_time" @selected(old(
                                                'winning_formula') == 'less_time')>کمترین زمان
                                                </option>
                                                <option value="grade" @selected(old(
                                                'winning_formula') == 'grade')>بیشترین نمره فنی
                                                </option>
                                            </select>
                                        </div>

                                        {!! Form::label('winning_formula','فرمول برنده بسته کاری',['class'=>'col-12']) !!}
                                    </div>
                                </div>

                                {{-- حداقل تعداد پیشنهاد --}}
                                <div class="col-4">
                                    <div class="form-group row no-gutters ">
                                        @if($errors->has('minimum_offers'))
                                            <span class="col-12 message-show">{{ $errors->first('minimum_offers') }}</span>
                                        @endif
                                        {!! Form::text('minimum_offers',null,[ 'id'=>'minimum_offers' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'حداقل تعداد پیشنهاد را وارد نمایید']) !!}
                                        {!! Form::label('minimum_offers','حداقل تعداد پیشنهاد:',['class'=>'col-12']) !!}
                                    </div>
                                </div>
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
                            {{-- Section --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('section'))
                                    <span class="col-12 message-show">{{ $errors->first('section') }}</span>
                                @endif
                                {!! Form::label('section','بخش',['class'=>'col-12']) !!}
                                <select data-placeholder="بخش را انتخاب نمایید..." id="section" class="select chosen-rtl num-fa" name="section_id">
                                    <option value="">بخش را انتخاب نمایید...</option>
                                    @forelse($Section as $item)
                                        <option value="{{$item->id}}" @selected(old('section_id') == $item->id)>{{ $item->title }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            {{-- Subsection --}}
                            <div class="form-group row no-gutters subsection-block d-none">
                                @if($errors->has('subsection'))
                                    <span class="col-12 message-show">{{ $errors->first('subsection') }}</span>
                                @endif
                                {!! Form::label('subsection','زیر بخش',['class'=>'col-12']) !!}
                                <select data-placeholder="زیر بخش را انتخاب نمایید..." id="subsection" class="select chosen-rtl num-fa" name="subsection_id">
                                </select>
                            </div>

                            {{-- Division --}}
                            <div class="form-group row no-gutters division-block d-none">
                                @if($errors->has('division'))
                                    <span class="col-12 message-show">{{ $errors->first('division') }}</span>
                                @endif
                                <div class="col-12" style="margin-bottom: 10px;">{!! Form::label('division','قسمت') !!}<span class="required align-middle">(اختیاری)</span></div>
                                <select data-placeholder="قسمت را انتخاب نمایید..." id="division" class="select chosen-rtl num-fa" name="division_id">
                                </select>
                            </div>

                            {{-- WorkPackage Type --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('work_package_type'))
                                    <span class="col-12 message-show">{{ $errors->first('work_package_type') }}</span>
                                @endif
                                {!! Form::label('work_package_type','نوع بسته کاری',['class'=>'col-12']) !!}
                                <select data-placeholder="اندازه بسته کاری را انتخاب نمایید..." id="work_package_type" class="select chosen-rtl num-fa" name="work_package_type">
                                    <option value="public" @selected(old("work_package_type") == "hourly_contract")>انتشار عمومی</option>
                                    <option value="hourly_contract" @selected(old("work_package_type") == "hourly_contract")>قرارداد نفر/ساعت</option>
                                </select>
                            </div>

                            {{-- Select Freelancer --}}
                            <div class="form-group row no-gutters" id="freelancer_select_wrapper">
                                <div class="col-12 field-style">
                                    <select multiple data-placeholder="فریلنسر ها را انتخاب کنید..." class="form-control chosen-rtl select" name="freelancer[]" id="freelancer"></select>
                                </div>
                                {!! Form::label('freelancer','فریلنسرهای قرداداد ساعت کاری:',['class'=>'col-12']) !!}
                            </div>

                            {{-- Select public Freelancer --}}
                            <div class="form-group row no-gutters" id="normal_freelancer_select_wrapper">
                                <div class="col-12 field-style">
                                    <select multiple data-placeholder="فریلنسر ها را انتخاب کنید..." class="form-control chosen-rtl select" name="normal_freelancer[]" id="normal_freelancer"></select>
                                </div>
                                {!! Form::label('normal_freelancer','فریلنسرها:',['class'=>'col-12']) !!}
                            </div>


                            {{-- بازه زمانی ارائه پیشنهادات --}}
                            <div class="form-group row no-gutters ">
                                @if($errors->has('offer_time'))
                                    <span class="col-12 message-show">{{ $errors->first('offer_time') }}</span>
                                @endif
                                <div style="margin-bottom: 10px;">{!! Form::label('offer_time','بازه زمانی ارائه پیشنهادات') !!}
                                    <span class="required">(روز)</span></div>

                                {!! Form::text('offer_time',null,[ 'id'=>'offer_time' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'بازه زمانی ارائه پیشنهادات را وارد نمایید']) !!}
                            </div>

                            {{-- هماهنگ کننده --}}
                            <div class="form-group row no-gutters ">
                                @if($errors->has('coordinator'))
                                    <span class="col-12 message-show">{{ $errors->first('coordinator') }}</span>
                                @endif
                                {!! Form::text('coordinator',null,[ 'id'=>'coordinator' , 'class'=>'col-12 field-style input-text', 'placeholder'=>'هماهنگ کننده را وارد نمایید']) !!}
                                {!! Form::label('coordinator','هماهنگ کننده:',['class'=>'col-12']) !!}
                            </div>

                            <button type="submit" class="submit-form-btn create-btn">ایجاد بسته کاری</button>
                        </div>
                    </div>

                    {{-- جرایم تاخیرات --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">جرایم تاخیرات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            {{-- جرایم تاخیرات هر روز تاخیر --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('daily_fine'))
                                    <span class="col-12 message-show">{{ $errors->first('daily_fine') }}</span>
                                @endif
                                {!! Form::text('daily_fine',null,[ 'id'=>'daily_fine' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'جرایم تاخیرات هر روز تاخیر را وارد نمایید']) !!}
                                {!! Form::label('daily_fine','جرایم تاخیرات هر روز تاخیر:',['class'=>'col-12']) !!}
                            </div>

                            {{-- جرایم تاخیرات پس از گذشت n روز --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('fine_after_day'))
                                    <span class="col-12 message-show">{{ $errors->first('fine_after_day') }}</span>
                                @endif
                                {!! Form::text('fine_after_day',null,[ 'id'=>'fine_after_day' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'جرایم تاخیرات پس از گذشت n روز را وارد نمایید']) !!}
                                {!! Form::label('fine_after_day','جرایم تاخیرات پس از گذشت n روز:',['class'=>'col-12']) !!}
                            </div>

                            {{-- مبلغ جرایم تاخیرات پس از گذشت n روز --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('fine_after_price'))
                                    <span class="col-12 message-show">{{ $errors->first('fine_after_price') }}</span>
                                @endif
                                {!! Form::text('fine_after_price',null,[ 'id'=>'fine_after_price' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'مبلغ جرایم تاخیرات پس از گذشت n روز را وارد نمایید']) !!}
                                {!! Form::label('fine_after_price','مبلغ جرایم تاخیرات پس از گذشت n روز:',['class'=>'col-12']) !!}
                            </div>

                            {{-- نمره منفی پس از گذشت n روز --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('fine_after_negative'))
                                    <span class="col-12 message-show">{{ $errors->first('fine_after_negative') }}</span>
                                @endif
                                {!! Form::text('fine_after_negative',null,[ 'id'=>'fine_after_negative' , 'class'=>'col-12 field-style input-text number-format-only', 'placeholder'=>'نمره منفی پس از گذشت n روز را وارد نمایید']) !!}
                                {!! Form::label('fine_after_negative','نمره منفی پس از گذشت n روز:',['class'=>'col-12']) !!}
                            </div>
                        </div>
                    </div>

                    {{-- مستندات بسته کاری --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">مستندات بسته کاری</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            {{-- فایل عمومی بسته کاری --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('attachment_for_all'))
                                    <span class="col-12 message-show">{{ $errors->first('attachment_for_all') }}</span>
                                @endif
                                {!! Form::label('attachment_for_all','فایل عمومی بسته کاری:',['class'=>'col-12 mb-1']) !!}
                                <input type="file" value="{{ old('attachment_for_all') }}" name="attachment_for_all">
                            </div>

                            {{-- فایل مرتبط با برنده بسته کاری --}}
                            <div class="form-group row no-gutters">
                                @if($errors->has('attachment_for_winner'))
                                    <span class="col-12 message-show">{{ $errors->first('attachment_for_winner') }}</span>
                                @endif
                                {!! Form::label('attachment_for_winner','فایل مرتبط با برنده بسته کاری:',['class'=>'col-12 mb-1']) !!}
                                {!! Form::file('attachment_for_winner',null,[ 'id'=>'attachment_for_winner' , 'class'=>'col-12']) !!}
                            </div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">برچسب ها</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <div class="form-group">
                                <div class="answer-question text-field-repeater">
                                    <div class="field-list" id="repeat_list"></div>
                                    <div class="add-field center" id="addRepeatItem">
                                        <span class="icon-plus"></span>افزودن برچسب
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('footer')
    {{-- Users Check --}}
    <script>
        $(function () {
            const $normalFreelancerWrapper = $('#normal_freelancer_select_wrapper');
            const $freelancerWrapper = $('#freelancer_select_wrapper');
            const $typeSelect = $('#work_package_type');

            const toggleFreelancerSelect = () => {
                if ($typeSelect.val() === 'hourly_contract') {
                    $normalFreelancerWrapper.slideUp();
                    $freelancerWrapper.slideDown();
                } else {
                    $normalFreelancerWrapper.slideDown();
                    $freelancerWrapper.slideUp();
                }
            };

            $typeSelect.on('change', toggleFreelancerSelect);
            toggleFreelancerSelect();
        });
    </script>

    {{-- Subsection Check --}}
    <script>
        @php
            $selectedNormalFreelancers = $oldFreelancers = old('normal_freelancer', []);;
            $selectedFreelancers = $oldFreelancers = old('freelancer', []);
        @endphp
        var $subsection = $('#subsection');
        const $normal_freelancer = $('#normal_freelancer');
        const $freelancer = $('#freelancer');
        $subsection.chosen();
        $normal_freelancer.chosen();
        $freelancer.chosen();
        var section_id = '{{ old('section_id') ? old('section_id') : ''}}';
        var subsection_id = '{{old('subsection_id') ? old('subsection_id') : ''}}';
        const selectedNormalFreelancersList = @json($selectedNormalFreelancers);
        const selectedFreelancersList = @json($selectedFreelancers);
        if (section_id) {
            $('.subsection-block').addClass('d-none');
            var url = "{{ url('/dashboard/subsection/check') }}/" + section_id;
            $.ajax({
                url: url,
                type: "POST",
                data: {_token: '{{ csrf_token() }}'},
                cache: false,
                dataType: 'json',
                success: function (dataResult) {
                    $('.subsection-block').removeClass('d-none');
                    var newOptions = '<option>زیر بخش را انتحاب کنید...</option>';
                    dataResult.subsection.forEach(function (item) {
                        var selected = subsection_id == item.id ? 'selected' : null;
                        newOptions += '<option ' + selected + ' value=' + item.id + '>' + item.title + '</option>';
                    });
                    $subsection.html(newOptions);
                    $subsection.trigger("chosen:updated");

                    var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.normally_freelancer.forEach(function (item) {
                        const selectedNormallyFreelancer = selectedNormalFreelancersList.includes(item.id) ? 'selected' : '';
                        newOptionsNormallyFreelancer += '<option ' + selectedNormallyFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $normal_freelancer.html(newOptionsNormallyFreelancer);
                        $normal_freelancer.trigger("chosen:updated");
                    });

                    var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.freelancers.forEach(function (item) {
                        const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
                        newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $freelancer.html(newOptionsFreelancer);
                        $freelancer.trigger("chosen:updated");
                    });
                }
            });
        }
        $('#section').on('change', function (e, prom) {
            e.preventDefault();
            $('.subsection-block').addClass('d-none');
            var url = "{{ url('/dashboard/subsection/check') }}/" + prom.selected;
            $.ajax({
                url: url,
                type: "POST",
                data: {_token: '{{ csrf_token() }}'},
                cache: false,
                dataType: 'json',
                success: function (dataResult) {
                    $('.subsection-block').removeClass('d-none');
                    var newOptions = '<option>زیر بخش را انتحاب کنید...</option>';
                    dataResult.subsection.forEach(function (item) {
                        newOptions += '<option  value=' + item.id + '>' + item.title + '</option>';
                    });
                    $subsection.html(newOptions);
                    $subsection.trigger("chosen:updated");

                    var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.normally_freelancer.forEach(function (item) {
                        newOptionsNormallyFreelancer += '<option  value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $normal_freelancer.html(newOptionsNormallyFreelancer);
                        $normal_freelancer.trigger("chosen:updated");
                    });

                    var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.freelancers.forEach(function (item) {
                        const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
                        newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $freelancer.html(newOptionsFreelancer);
                        $freelancer.trigger("chosen:updated");
                    });
                }
            });
        });
    </script>

    {{-- Division Check --}}
    <script>
        var $division = $('#division');
        $division.chosen();
        var subsection_id = '{{old('subsection_id') ? old('subsection_id') : ''}}';
        var division_id = '{{old('division_id') ? old('division_id') : ''}}';
        if (subsection_id) {
            $('.division-block').addClass('d-none');
            var url = "{{ url('/dashboard/division/check') }}/" + subsection_id;
            $.ajax({
                url: url,
                type: "POST",
                data: {_token: '{{ csrf_token() }}'},
                cache: false,
                dataType: 'json',
                success: function (dataResult) {
                    if (dataResult.length) {
                        $('.division-block').removeClass('d-none');
                        var newOptions = '<option>قسمت را انتحاب کنید...</option>';
                        dataResult.forEach(function (item) {
                            var selected = division_id == item.id ? 'selected' : null;
                            newOptions += '<option ' + selected + ' value=' + item.id + '>' + item.title + '</option>';
                        });
                        $division.html(newOptions);
                        $division.trigger("chosen:updated");
                    }

                    var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.normally_freelancer.forEach(function (item) {
                        const selectedNormallyFreelancer = selectedNormalFreelancersList.includes(item.id) ? 'selected' : '';
                        newOptionsNormallyFreelancer += '<option ' + selectedNormallyFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $normal_freelancer.html(newOptionsNormallyFreelancer);
                        $normal_freelancer.trigger("chosen:updated");
                    });

                    var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.freelancers.forEach(function (item) {
                        const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
                        newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $freelancer.html(newOptionsFreelancer);
                        $freelancer.trigger("chosen:updated");
                    });
                }
            });
        }
        $('#subsection').on('change', function (e, prom) {
            e.preventDefault();
            $('.division-block').addClass('d-none');
            $("#division").find('option').remove();
            var url = "{{ url('/dashboard/division/check') }}/" + prom.selected;
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                cache: false,
                dataType: 'json',
                success: function (dataResult) {
                    if (dataResult.length) {
                        $('.division-block').removeClass('d-none');
                        var newOptions = '<option>قسمت را انتحاب کنید...</option>';
                        dataResult.forEach(function (item) {
                            newOptions += '<option value=' + item.id + '>' + item.title + '</option>';
                        });
                        $division.html(newOptions);
                        $division.trigger("chosen:updated");
                    }

                    var newOptionsNormallyFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.normally_freelancer.forEach(function (item) {
                        newOptionsNormallyFreelancer += '<option  value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $normal_freelancer.html(newOptionsNormallyFreelancer);
                        $normal_freelancer.trigger("chosen:updated");
                    });

                    var newOptionsFreelancer = '<option>فریلنسر را انتخاب کنید...</option>';
                    dataResult.freelancers.forEach(function (item) {
                        const selectedFreelancer = selectedFreelancersList.includes(item.id) ? 'selected' : '';
                        newOptionsFreelancer += '<option ' + selectedFreelancer + ' value=' + item.id + '>' + item.first_name + ' ' + item.last_name + '</option>';
                        $freelancer.html(newOptionsFreelancer);
                        $freelancer.trigger("chosen:updated");
                    });
                }
            });
        });
    </script>


    {{-- CKEditor Config --}}
    {{--    <script type="text/javascript">--}}
    {{--        CKEDITOR.replace('desc', {--}}
    {{--            language: 'fa',--}}
    {{--            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['path' => 'work_package','_token' => csrf_token()])}}",--}}
    {{--            filebrowserUploadMethod: 'form',--}}
    {{--            width: '100%',--}}
    {{--            height: '200',--}}
    {{--            uiColor: '#fdfdfd',--}}
    {{--        });--}}
    {{--        CKEDITOR.replace('rules', {--}}
    {{--            language: 'fa',--}}
    {{--            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['path' => 'work_package','_token' => csrf_token()])}}",--}}
    {{--            filebrowserUploadMethod: 'form',--}}
    {{--            width: '100%',--}}
    {{--            height: '200',--}}
    {{--            uiColor: '#fdfdfd',--}}
    {{--        });--}}
    {{--    </script>--}}

    {{-- Item Repeater --}}
    <script>
        var currentcheckcontent, lastcheckcontent;
        jQuery(document).ready(function () {
            var i = 0;
            jQuery("#addRepeatItem").click(function () {
                i += 1;
                jQuery("#repeat_list").append("" +
                    "<div id='field-repeat-item-" + i + "'>" +
                    "<div class='text-field'>" +
                    "<input placeholder='برچسب را وارد نمایید...' class='field-style input-text' type=\"text\" name=\"tag[" + i + "]\"> " +
                    "<span class='delete-row icon-close' onclick='delete_item(" + i + ")'>" +
                    "<span class='zmdi zmdi-close-circle'></span>" +
                    "</span>" +
                    "</div>" +
                    "</div>" +
                    "");
                return false;
            });

        });

        function delete_item($id) {
            $('#field-repeat-item-' + $id).remove();
        }
    </script>
@endsection
