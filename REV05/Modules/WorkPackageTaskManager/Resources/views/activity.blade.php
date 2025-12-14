@extends('dashboard::layouts.dashboard.master')

@section('title','فعالیت های بسته کاری')

@section('title-page')
    <span class="icon"><img src="{{ asset('/modules/sectionmanager/images/icons/work-package.gif') }}"></span>
    <span class="text">فعالیت های بسته کاری</span>
@endsection

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="form-section">
        <div class="row">
            <div class="col-9 mb-5">
                @if (\Session::has('importError'))
                    <div class="alert alert-danger num-fa">
                        <ul class="mb-0">
                            @forelse(\Session::get('importError') as $error)
                                @if(is_array($error))
                                    @forelse($error as $item)
                                        <li>{{ $item }}</li>
                                    @empty
                                    @endforelse
                                @else
                                    <li>{{ $error }}</li>
                                @endif
                            @empty
                            @endforelse
                        </ul>
                    </div>
                @endif

                <form class='repeater' action="{{ route('work-package-task-manager.store', $ID) }}" method="POST">
                    @csrf
                    {{-- Publish Options--}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <span class="widget-title">ثبت اطلاعات</span>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <button type="submit" class="submit-form-btn create-btn">ذخیره فعالیت ها</button>
                        </div>
                    </div>


                    <script>
                        var ActivityIndex = 0
                        var CategoryIndex = 0
                        var TaskIndex = 0
                    </script>
                    <div data-repeater-list="activity_list">
                        <div data-repeater-item class="widget-block widget-item widget-style repeater-block activity-item">
                            <div class="heading-widget">
                                <div class="row">
                                    <div class="col-9">
                                        <span class="widget-title num-fa">فعالیت <span class="repeaterItemNumber">1</span></span>
                                    </div>
                                    <div class="col-3 left">
                                        <input data-repeater-delete type="button" class="px-4 badge-danger py-2 rounded border-0 font-weight-bold cursor-pointer" value="حذف فعالیت"/>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-padding">
                                <!-- Activity repeater -->
                                <div class="row">
                                    <div class="col-6 mb-5">
                                        {!! Form::label('title','عنوان فعالیت',['class'=>'']) !!}
                                        {!! Form::text('title',null,['required', 'id'=>'title' , 'class'=>'border w-100 p-1 px-2 rounded', 'placeholder'=>'عنوان فعالیت']) !!}
                                    </div>
                                    <div class="col-6 mb-5">
                                        {!! Form::label('price_percentage','درصد کل این فعالیت',['class'=>'']) !!}
                                        <input type="text" required name="price_percentage" id="price_percentage" class="border w-100 p-1 px-2 rounded percentage" placeholder="درصد از کل هزینه پروژه" max="100" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                </div>

                                <!-- Category repeater -->
                                <div class="inner-repeater">
                                    <div data-repeater-list="category_list">
                                        <div data-repeater-item class="category-item border p-3 rounded mb-3" style="background: #fff">
                                            <div class="row">
                                                <div class="col-9 num-fa">مرحله <span></span></div>
                                                <div class="col-3 left">
                                                    <input data-repeater-delete type="button" value="حذف مرحله" class="px-4 badge-danger py-2 rounded border-0 font-weight-bold cursor-pointer"/>
                                                </div>
                                            </div>
                                            <div class="row align-items-end">
                                                <div class="col-4 mb-5">
                                                    {!! Form::label('title','عنوان مرحله',['class'=>'']) !!}
                                                    {!! Form::text('title',null,[ 'required', 'id'=>'title' , 'class'=>'border w-100 p-1 px-2 rounded', 'placeholder'=>'عنوان مرحله']) !!}
                                                </div>
                                                <div class="col-4 mb-5">
                                                    {!! Form::label('price_percentage','درصد از کل این فعالیت',['class'=>'']) !!}
                                                    <input type="text" required name="price_percentage" id="price_percentage" class="border w-100 p-1 px-2 rounded percentage" placeholder="درصد از کل این فعالیت" max="100" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                </div>
                                                <div class="col-4 mb-5">
                                                    {!! Form::label('due_date','زمان سر رسید (روز)',['class'=>'']) !!}
                                                    {!! Form::text('due_date',null,[ 'required', 'id'=>'due_date' , 'class'=>'border w-100 p-1 px-2 rounded', 'placeholder'=>'زمان سر رسید (روز)', 'oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"]) !!}
                                                </div>
                                            </div>

                                            <!-- Task repeater -->
                                            <div class="deep-inner-repeater mb-3">
                                                <div data-repeater-list="task_list" class="task_list">
                                                    <div data-repeater-item class="task-item p-3 border rounded mb-3" style="background: #f6f6f6">
                                                        <div class="row">
                                                            <div class="col-9 num-fa">وظیفه <span class="taskItemNumber123"></span></div>
                                                            <div class="col-3 left">
                                                                <input data-repeater-delete type="button" value="حذف وظیفه" class="px-4 badge-danger py-2 rounded border-0 font-weight-bold cursor-pointer"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4 mb-3">
                                                                {!! Form::label('title','عنوان وظیفه',['class'=>'']) !!}
                                                                {!! Form::text('title',null,[ 'required', 'id'=>'title' , 'class'=>'border w-100 p-1 px-2 rounded', 'placeholder'=>'عنوان وظیفه']) !!}
                                                            </div>
                                                            <div class="col-4 mb-3">
                                                                {!! Form::label('price_percentage','درصد از کل این مرحله',['class'=>'']) !!}
                                                                <input type="text" required name="price_percentage" id="price_percentage" class="border w-100 p-1 px-2 rounded percentage" placeholder="درصد از کل این مرحله" max="100" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                {!! Form::label('desc','توضیحات وظیفه',['class'=>'']) !!}
                                                                {!! Form::textarea('desc',null,[ 'id'=>'desc' , 'class'=>'border w-100 p-1 px-2 rounded', 'placeholder'=>'توضیحات وظیفه', 'rows'=> 6]) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input data-repeater-create type="button" value="افزودن وظیفه" class="px-4 badge-secondary py-2 rounded border-0 font-weight-bold cursor-pointer"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="left">
                                        <input data-repeater-create class="px-4 badge-secondary py-2 rounded border-0 font-weight-bold cursor-pointer" type="button" value="افزودن مرحله"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input data-repeater-create type="button" class="px-4 badge-secondary py-2 rounded border-0 font-weight-bold cursor-pointer" value="افزودن فعالیت"/>
                </form>
            </div>
            <div class="col-3">
                <a href='{{ url('dashboard/work-package/' . $ID . '/edit') }}' class="submit-form-btn mb-4" style="background-color: #e2e2e2; color: #333333 !important; border: solid 1px #d7d7d7;">بازگشت به بسته کاری</a>

                <div style="position: sticky; top: 0">
                    {{-- Import --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="widget-title d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.5 10.19h-2.89c-2.37 0-4.3-1.93-4.3-4.3V3c0-.55-.45-1-1-1H8.07C4.99 2 2.5 4 2.5 7.57v8.86C2.5 20 4.99 22 8.07 22h7.86c3.08 0 5.57-2 5.57-5.57v-5.24c0-.55-.45-1-1-1Zm-8.22 5.59-2 2c-.07.07-.16.13-.25.16a.671.671 0 0 1-.56 0 .662.662 0 0 1-.22-.15c-.01-.01-.02-.01-.02-.02l-2-2a.754.754 0 0 1 0-1.06c.29-.29.77-.29 1.06 0l.71.73v-4.19c0-.41.34-.75.75-.75s.75.34.75.75v4.19l.72-.72c.29-.29.77-.29 1.06 0 .29.29.29.77 0 1.06Z" fill="#555555"></path>
                                    <path d="M17.43 8.81c.95.01 2.27.01 3.4.01.57 0 .87-.67.47-1.07-1.44-1.45-4.02-4.06-5.5-5.54-.41-.41-1.12-.13-1.12.44v3.49c0 1.46 1.24 2.67 2.75 2.67Z" fill="#555555"></path>
                                </svg>
                                <span class="pl-2">دریافت نسخه MDL</span>
                            </div>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <form action="{{ route('work-package-task-manager.export', $ID) }}" method="POST">
                                @csrf
                                <div class="form-group row no-gutters">
                                    <button type="submit" class="submit-form-btn" style="background-color: #1179ef;">دانلود فایل اکسل</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Import --}}
                    <div class="widget-block widget-item widget-style">
                        <div class="heading-widget">
                            <div class="widget-title d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.5 10.19h-2.89c-2.37 0-4.3-1.93-4.3-4.3V3c0-.55-.45-1-1-1H8.07C4.99 2 2.5 4 2.5 7.57v8.86C2.5 20 4.99 22 8.07 22h7.86c3.08 0 5.57-2 5.57-5.57v-5.24c0-.55-.45-1-1-1Zm-8.97 3.34c-.15.15-.34.22-.53.22s-.38-.07-.53-.22l-.72-.72V17c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-4.19l-.72.72c-.29.29-.77.29-1.06 0a.754.754 0 0 1 0-1.06l2-2c.07-.06.14-.11.22-.15.02-.01.05-.02.07-.03.06-.02.12-.03.19-.04h.08c.08 0 .16.02.24.05h.02c.08.03.16.09.22.15.01.01.02.01.02.02l2 2c.29.29.29.77 0 1.06Z" fill="#555555"></path>
                                    <path d="M17.43 8.81c.95.01 2.27.01 3.4.01.57 0 .87-.67.47-1.07-1.44-1.45-4.02-4.06-5.5-5.54-.41-.41-1.12-.13-1.12.44v3.49c0 1.46 1.24 2.67 2.75 2.67Z" fill="#555555"></path>
                                </svg>
                                <span class="pl-2">درون ریزی نسخه MDL</span>
                            </div>
                        </div>

                        <div class="widget-content widget-content-padding widget-content-padding-side">
                            <form action="{{ route('work-package-task-manager.import', $ID) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row no-gutters">
                                    <input type="file" name="file">

                                    <button type="submit" class="submit-form-btn" style="background-color: #8143c4;">آپلود فایل اکسل</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <script>
        // $('#importModal').modal('show')
    </script>

    <!-- Percentage -->
    <script>
        $(document).on('keyup', '.percentage', function () {
            var _this = $(this);
            var min = parseInt(_this.attr('min')) || 0; // if min attribute is not defined, 1 is default
            var max = parseInt(_this.attr('max')) || 100; // if max attribute is not defined, 100 is default
            var val = parseInt(_this.val()) || (min - 1); // if input char is not a number the value will be (min - 1) so first condition will be true
            if (val < min)
                _this.val(min);
            if (val > max)
                _this.val(max);
        });
    </script>

    {{-- Repeater --}}
    <script src="{{ asset('/lib/repeater/jquery.repeater.min.js') }}"></script>

    <script>
        var $repeater = $('.repeater').repeater({
            initEmpty: true,
            show: function () {
                var selfRepeaterItem = this;
                $(this).slideDown();
                var activityIndex = $(this).closest('.activity-item').index();
                var categoryIndex = $(this).find('.category-item').index();
                var taskIndex = $(this).find('.task-item').index();
                var id = activityIndex + '-' + categoryIndex + '-' + taskIndex
                $(this).find('#due-date').addClass('due-date-' + id)
                $(this).find('.check-box-styled').attr('id', 'check-box-' + id)
                $(this).find('.check-box-label').attr('for', 'check-box-' + id)
                $(selfRepeaterItem).find('.repeaterItemNumber').text(activityIndex + 1);
                $(selfRepeaterItem).find('.categoryItemNumber').text(categoryIndex + 1);
                $(selfRepeaterItem).find('.taskItemNumber').text(taskIndex + 1);
            },
            hide: function (deleteElement) {
                if (confirm('آیا از حذف این فعالیت مطمئن هستید؟')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {
                $('.task_list').find('.category-item').remove()
            },
            repeaters: [{
                selector: '.inner-repeater',
                show: function () {
                    var selfRepeaterItem = this;
                    var activityIndex = $(this).closest('.activity-item').index();
                    var categoryIndex = $(this).closest('.category-item').index();
                    var taskIndex = $(this).find('.task-item').index();
                    var id = activityIndex + '-' + categoryIndex + '-' + taskIndex
                    $(this).find('#due-date').addClass('due-date-' + id)
                    $(this).find('.check-box-styled').attr('id', 'check-box-' + id)
                    $(this).find('.check-box-label').attr('for', 'check-box-' + id)
                    $(selfRepeaterItem).find('.categoryItemNumber').text(categoryIndex + 1);
                    $(selfRepeaterItem).slideDown();

                    $(this).find('.task-item').after().remove()
                },
                ready: function (setIndexes) {
                    $('.task_list').find('.category-item').remove()
                },
                repeaters: [{
                    selector: '.deep-inner-repeater',
                    show: function () {
                        var selfRepeaterItem = this;
                        var activityIndex = $(this).closest('.activity-item').index();
                        var categoryIndex = $(this).closest('.category-item').index();
                        var taskIndex = $(this).closest('.task-item').index();
                        var id = activityIndex + '-' + categoryIndex + '-' + taskIndex
                        $(this).find('#due-date').addClass('due-date-' + id)
                        $(selfRepeaterItem).find('.taskItemNumber').text(taskIndex + 1);
                        $(selfRepeaterItem).slideDown();
                    },
                    ready: function (setIndexes) {
                        $('.task_list').find('.category-item').remove()
                    },
                }]
            }]
        });

        @if(session('form_debug'))
        @php
            $Activity = session('form_debug')['activity_list'];
        @endphp
        $repeater.setList([
                @isset($Activity)
                @forelse($Activity as $itemActivity)
            {
                'title': '{{$itemActivity['title']}}',
                'price_percentage': '{{$itemActivity['price_percentage']}}',
                @isset($itemActivity['category_list'])
                'category_list': [
                        @forelse($itemActivity['category_list'] as $itemCategory)
                    {
                        'title': '{{$itemCategory['title']}}',
                        'price_percentage': '{{$itemCategory['price_percentage']}}',
                        'due_date': '{{isset($itemCategory['due_date']) ? $itemCategory['due_date'] : ''}}',
                        @isset($itemCategory['task_list'])
                        'task_list': [
                                @forelse($itemCategory['task_list'] as $itemTask)
                            {
                                'title': '{{$itemTask['title']}}',
                                'price_percentage': '{{$itemTask['price_percentage']}}',
                                'desc': '{{isset($itemTask['desc']) ? $itemTask['desc'] : ''}}',
                            },
                            @empty
                            @endforelse
                        ]
                        @endisset
                    },
                    @empty
                    @endforelse
                ],
                @endisset
            },
            @empty
            @endforelse
            @endisset
        ]);
        @else
        $repeater.setList([
                @isset($Activity)
                @forelse($Activity as $itemActivity)
            {
                'title': '{{$itemActivity->title}}',
                'price_percentage': '{{$itemActivity->price_percentage}}',
                @isset($itemActivity->category)
                'category_list': [
                        @forelse($itemActivity->category as $itemCategory)
                    {
                        'title': '{{$itemCategory->title}}',
                        'price_percentage': '{{$itemCategory->price_percentage}}',
                        'due_date': '{{isset($itemCategory->due_date) ? $itemCategory->due_date : ''}}',
                        @isset($itemCategory->task)
                        'task_list': [
                                @forelse($itemCategory->task as $itemTask)
                            {
                                'title': '{{$itemTask->title}}',
                                'price_percentage': '{{$itemTask->price_percentage}}',
                                'desc': '{{isset($itemTask->desc) ? $itemTask->desc : ''}}',
                            },
                            @empty
                            @endforelse
                        ]
                        @endisset
                    },
                    @empty
                    @endforelse
                ],
                @endisset
            },
            @empty
            @endforelse
            @endisset
        ]);
        @endif


        $('.task-item').find('.category-item').remove()
    </script>
@endsection
