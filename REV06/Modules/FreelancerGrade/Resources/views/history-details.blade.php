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
        <div class="row">
            <div class="col-12">
                <div class="widget-block widget-item widget-style">
                    <div class="heading-widget">
                        <span class="widget-title">جزئیات نمره فنی</span>
                    </div>

                    <div class="widget-content widget-content-padding widget-content-padding-side num-fa">
                        <div class="row">
                            @foreach($Labels as $index => $label)
                                @php
                                    $value = $OldGrades[$index];
                                    $valueMessage = $OldGradesMessage[$index] ?? '';
                                @endphp
                                <div class="col-4">
                                    <div class="range-wrapper" style="max-width: 100%">
                                        <label for="range{{ $index }}">{{ $label }}</label>
                                        <div class="range-row">
                                            <input
                                                name="grade_type[{{ $index }}]"
                                                type="range"
                                                id="range{{ $index }}"
                                                value="{{ $value }}"
                                                min="1"
                                                step="1"
                                                disabled
                                            >
                                            <div class="range-value" data-for="range{{ $index }}">{{ $value }}</div>
                                        </div>
                                        <textarea disabled readonly rows="3" name="grade_message[{{ $index }}]" placeholder="توضیحات..." style="font-size: 12px; border: none; resize: none; border-radius: 5px; background: #EEEEEE; padding: 3px 7px;">{{ $valueMessage }}</textarea>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
