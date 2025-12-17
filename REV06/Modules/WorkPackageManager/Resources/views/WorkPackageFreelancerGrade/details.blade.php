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
                            <div class="range-wrapper" style="max-width: 100%">
                                <label for="range{{ $index }}">{{ $label }}</label>
                                <div class="range-row">
                                    <input
                                        disabled
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
                    <textarea disabled readonly rows="4" id="grade_message" placeholder="توضیحات..." style="font-size: 12px; border: none; resize: none; border-radius: 5px; background: #f8f8f8; padding: 3px 7px; width: 100%">{{ $Log->grade_message }}</textarea>
                </div>
            </div>
        </div>
    </section>
@endsection
