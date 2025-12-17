@extends('dashboard::layouts.dashboard.master')

@section('title')
    اطلاعات فنی
@endsection

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/users/images/icons/user.gif') }}"></span>
    <span class="text">اطلاعات فنی</span>
@endsection

@section('content')
    <section class="form-section">
        <form action="{{ route('WorkPackageFreelancerGrade.update', $id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-9">
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">تنظیم نمرات فنی</span>
                        </div>
                        <div class="widget-content widget-content-padding widget-content-padding-side num-fa">
                            <div class="row">
                                @foreach($Labels as $index => $label)
                                    @php
                                        $value = $OldGrades[$index] ?? $OldSuggestGrades[$index] ?? 5;
                                    @endphp

                                    <div class="col-4">
                                        <div class="range-wrapper">
                                            <label for="range{{ $index }}">{{ $label }}</label>
                                            <div class="range-row">
                                                <input
                                                    name="grade_type[{{ $index }}]"
                                                    type="range"
                                                    id="range{{ $index }}"
                                                    min="1"
                                                    max="10"
                                                    value="{{ $value }}"
                                                    step="1"
                                                >
                                                <div class="range-value" data-for="range{{ $index }}">{{ $value }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <script>
                                document.addEventListener('input', e => {
                                    if (e.target.type === 'range') {
                                        const id = e.target.id;
                                        const val = document.querySelector(`.range-value[data-for="${id}"]`);
                                        if (val) val.textContent = e.target.value;
                                    }
                                });
                            </script>

                            <div class="mt-4">
                                <label for="grade_message">توضیحات</label>
                                <textarea rows="4" id="grade_message" name="grade_message" placeholder="توضیحات..." style="font-size: 12px; border: solid 1px #cccccc; border-radius: 5px; background: #f8f8f8; padding: 3px 7px; width: 100%">{{ $WorkPackageFreelancerGradeMessage }}</textarea>
                            </div>
                        </div>
                    </div>


                    @isset($FreelancerGradeLog)
                        @if(count($FreelancerGradeLog))
                            <div style="margin-bottom: 40px; font-size: 11px;">
                                @foreach($FreelancerGradeLog as $log)
                                    <div style="border-bottom: solid 1px #dddddd; padding-bottom: 5px; margin-bottom: 5px">
                                        <strong>{{ $log->User->first_name }}</strong>
                                        در تاریخ
                                        <strong class="num-fa">{{ \Morilog\Jalali\Jalalian::forge($log->created_at)->format('H:i - Y/m/d') }}</strong>
                                        نمره فنی را از <strong class="num-fa">{{ $log->from_grade ?: 'بدون نمره' }}</strong> به نمره <strong class="num-fa">{{ $log->to_grade }}</strong> تغییر داد.
                                        <a target="_blank" href="{{ route('WorkPackageFreelancerGrade.details', $log->id) }}" style="color: #0a58ca;"> جزئیات</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endisset
                </div>

                <div class="col-3">
                    {{-- Publish Options --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>
                        <div class="widget-content widget-content-padding widget-content-padding-side">

                            @if($WorkPackageFreelancerGrade && ($WorkPackageFreelancerGrade->grade || $WorkPackageFreelancerGrade->suggest_grade))
                                @if($WorkPackageFreelancerGrade->grade)
                                    <div class="font-weight-bold text-center p-3 rounded mb-3 num-fa" style="background: #e8fdee;">
                                        <div class="mb-2">امتیاز نهایی فریلنسر</div>
                                        <div style="font-size: 28px; line-height: 30px; color: #00c400">{{ $WorkPackageFreelancerGrade->grade }}</div>
                                    </div>
                                @endif

                                @if($WorkPackageFreelancerGrade->suggest_grade)
                                    <div class="font-weight-bold text-center p-3 rounded mb-3 num-fa" style="background: #fff8e5;">
                                        <div class="mb-2">امتیاز پیشنهادی ارزیاب</div>
                                        <div style="font-size: 28px; line-height: 30px; color: #ffab00">{{ $WorkPackageFreelancerGrade->suggest_grade }}</div>
                                    </div>
                                @endif
                            @endif
                            <button type="submit" class="submit-form-btn create-btn">بروزرسانی نمره فنی</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
