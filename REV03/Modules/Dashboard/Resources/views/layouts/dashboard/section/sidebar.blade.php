@php
    if (Gate::allows('isChiefAppraiser')) {
                $OwnerType = 'section_id';
                $Owner = \Modules\SectionManager\Entities\Section::with('ChiefAppraiser')->whereHas('ChiefAppraiser', function ($query) {
                    $query->where('users_id', '=', auth()->user()->id);
                })->pluck('id')->toArray();
            } elseif (Gate::allows('isAppraiser')) {
                $OwnerType = 'subsection_id';
                $Owner = \Modules\SectionManager\Entities\Subsection::with('Appraiser')->whereHas('Appraiser', function ($query) {
                    $query->where('users_id', '=', auth()->user()->id);
                })->pluck('id')->toArray();
            } else {
                $OwnerType = '';
                $Owner = null;
            }


            $FreelancerGrade = \Modules\Freelancer\Entities\FreelancerSection::select('freelancer_section.*', 'users.created_at')
            ->join('users', 'freelancer_section.users_id', '=', 'users.id')
            ->when($Owner, function ($query) use ($OwnerType, $Owner) {
                $query->whereIn($OwnerType, $Owner);
            })
            ->when(Gate::allows('isChiefAppraiser'), function ($query) {
                $query->whereNull('freelancer_section.grade');
            })
            ->when(Gate::allows('isAppraiser'), function ($query) {
//                $query->whereNull('freelancer_section.grade');
                $query->whereNull('freelancer_section.suggest_grade');
            })
            ->count();
@endphp

<div class="branding-sidebar">
    <a href="{{ Url('/') }}" class="logo-panel center w-100 d-inline-block">
        {{--        <span style="font-size: 42px; font-weight: bold; color: #000000; height: 50px; line-height: 80px" dir="ltr">EIDO+</span>--}}
        <img width="130" src="{{ asset('/modules/dashboard/admin/img/base/dashboard-logo.png') }}">
    </a>
</div>
<div class="branding-sidebar user-card" style="padding: 20px">
    <div class="d-flex align-items-center " style="gap: 20px">
        <div><img width="50px" alt="{{ auth()->user()->first_name }}" src="{{ asset('modules/dashboard/admin/img/base/avatar-sample.svg') }}"/></div>
        <div>
            <strong>{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</strong>
            <div style="color: #777">{{ \App\Http\Controllers\HomeController::RoleTranslation(auth()->user()->role) }}</div>
        </div>
    </div>
</div>
<ul class="menu-admin">
    <li class="@if (Request::is('dashboard')) {{ 'active-menu' }}@endif">
        <a href="{{ Url('dashboard') }}"><i class="zmdi zmdi-desktop-windows"></i><span class="text">میزکار</span></a>
    </li>

    @can('isSectionManager')
        <li class="@if (Route::is('freelancer.*') && !Route::is('freelancer.offer*')) {{ 'active-menu' }}@endif">
            <a href="{{ Url('dashboard/freelancer') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" style="width:24px !important; height: 24px !important;transform:translate3d(0,0,0);content-visibility:visible" viewBox="0 0 500 500">
                    <path fill="#121330" d="M151.046-114.606H83.362v-36.434c0-20.117-16.366-36.484-36.483-36.484h-93.75c-20.117 0-36.483 16.367-36.483 36.484v36.434h-67.684c-20.117 0-36.484 16.367-36.484 36.484v229.168c0 20.117 16.367 36.483 36.484 36.483h302.084c20.117 0 36.484-16.366 36.484-36.483V-78.122c0-20.117-16.367-36.484-36.484-36.484zm-203.1-36.434a5.19 5.19 0 0 1 5.183-5.184h93.75a5.19 5.19 0 0 1 5.183 5.184v36.434H-52.054v-36.434zm-98.984 67.734h302.084a5.19 5.19 0 0 1 5.183 5.184v78.1h-312.451v-78.1a5.19 5.19 0 0 1 5.184-5.184zm302.084 239.535h-302.084a5.19 5.19 0 0 1-5.184-5.183V31.278h52.034v36.438c0 8.643 7.006 15.65 15.65 15.65s15.65-7.007 15.65-15.65V31.278H72.896v36.438c0 8.643 7.006 15.65 15.65 15.65s15.65-7.007 15.65-15.65V31.278h52.033v119.768a5.189 5.189 0 0 1-5.183 5.183z" class="primary design" style="display:block" transform="translate(250 250)"/>
                </svg>
                <span class="text">فریلنسرها</span></a>
        </li>
    @endcan

    @canany(['isChiefAppraiser', 'isAppraiser'])
        <li class="@if (Route::is('freelancer-grade*')) {{ 'active-menu' }}@endif">
            <a href="{{ Url('dashboard/freelancer-grade') }}">
                <svg class="stroke-hover" xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="width:24px !important; height: 24px !important;transform:translate3d(0,0,0);content-visibility:visible" viewBox="0 0 24 24">
                    <g stroke="#121330" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" clip-path="url(#g8Te6EbYhHa)">
                        <path style="fill: transparent" d="M21.25 4.75v5.42m-3 .079-2 3.5h-.22a1.5 1.5 0 0 1-1.5-1.5v-2h-1.784a1 1 0 0 1-.986-1.168l.598-3.5a1 1 0 0 1 .986-.832h4.906zM2.75 19.25v-5.42m3-.08 2-3.5h.22a1.5 1.5 0 0 1 1.5 1.5v2h1.784a1 1 0 0 1 .986 1.168l-.598 3.5a1 1 0 0 1-.986.832H5.75z"/>
                    </g>
                    <defs>
                        <clipPath id="g8Te6EbYhHa">
                            <path d="M0 0h24v24H0z"/>
                        </clipPath>
                    </defs>
                </svg>
                <span class="text">نمره فنی فریلنسرها
                        <span class="badge-menu badge-info num-fa">{{$FreelancerGrade}}</span></span>
            </a>
        </li>
    @endcan

    @canany(['isSectionManager'])
        <li class="child-menu {{ (request()->is('dashboard/*section*') || request()->is('dashboard/division*')) ? 'active-menu' : '' }}">
            <a href="#" class="{{ (request()->is('dashboard/*section*') || request()->is('dashboard/division*')) ? 'active-sub-menu' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" style="width:24px;height:24px;transform:translate3d(0,0,0);content-visibility:visible" viewBox="0 0 500 500">
                    <path fill="#121330" d="M8.96-.51 6.25-8c-.11-.3-.39-.5-.71-.5H-5.53c-.32 0-.6.2-.71.49L-8.95-.52c-.03.09-.05.18-.05.26v7.01c0 .96.79 1.75 1.75 1.75h14.5C8.22 8.5 9 7.71 9 6.75V-.26c0-.08-.01-.17-.04-.25zM-5.01-7H5.01L7.36-.51H4.25c-.97 0-1.75.79-1.75 1.75v1.52c0 .14-.11.25-.25.25h-4.5c-.14 0-.25-.11-.25-.25V1.24c0-.97-.79-1.75-1.75-1.75h-3.11L-5.01-7zM7.5 6.75c0 .14-.11.25-.25.25h-14.5c-.14 0-.25-.11-.25-.25V.99h3.25c.14 0 .25.11.25.25v1.52c0 .96.79 1.75 1.75 1.75h4.5C3.22 4.51 4 3.72 4 2.76V1.24c0-.14.11-.25.25-.25H7.5v5.76z" class="primary design" style="display:block" transform="translate(250.002 260.417) scale(20.83)"/>
                </svg>
                <span class="text">بخش ها</span></a>
            <ul class="sub-menu-1 {{ (request()->is('dashboard/*section*') || request()->is('dashboard/division*')) ? 'd-block' : '' }}">
                <li class="{{ (request()->is('dashboard/section*')) ? 'active-zir-menu' : '' }}">
                    <a href="{{ Url('dashboard/section') }}"><span class="text">بخش ها</span></a>
                </li>
                <li class="{{ (request()->is('dashboard/subsection*')) ? 'active-zir-menu' : '' }}">
                    <a href="{{ Url('dashboard/subsection') }}"><span class="text">زیر بخش ها</span></a>
                </li>
                <li class="{{ (request()->is('dashboard/division*')) ? 'active-zir-menu' : '' }}">
                    <a href="{{ Url('dashboard/division') }}"><span class="text">قسمت</span></a>
                </li>
            </ul>
        </li>
    @endcanany

    @canany(['isWorkPackageManager', 'isSectionManager', 'isChiefAppraiser', 'isAppraiser', 'isEngineeringManager'])
        <li class="child-menu {{ (request()->is('dashboard/work-package*')) || Route::is('freelancer.offer*') ? 'active-menu' : '' }}">
            <a href="#" class="{{ (request()->is('dashboard/work-package*')) || Route::is('freelancer.offer*') ? 'active-sub-menu' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" style="width:24px;height:24px;transform:translate3d(0,0,0);content-visibility:visible" viewBox="0 0 500 500">
                    <path fill="#121330" d="M8.96-.51 6.25-8c-.11-.3-.39-.5-.71-.5H-5.53c-.32 0-.6.2-.71.49L-8.95-.52c-.03.09-.05.18-.05.26v7.01c0 .96.79 1.75 1.75 1.75h14.5C8.22 8.5 9 7.71 9 6.75V-.26c0-.08-.01-.17-.04-.25zM-5.01-7H5.01L7.36-.51H4.25c-.97 0-1.75.79-1.75 1.75v1.52c0 .14-.11.25-.25.25h-4.5c-.14 0-.25-.11-.25-.25V1.24c0-.97-.79-1.75-1.75-1.75h-3.11L-5.01-7zM7.5 6.75c0 .14-.11.25-.25.25h-14.5c-.14 0-.25-.11-.25-.25V.99h3.25c.14 0 .25.11.25.25v1.52c0 .96.79 1.75 1.75 1.75h4.5C3.22 4.51 4 3.72 4 2.76V1.24c0-.14.11-.25.25-.25H7.5v5.76z" class="primary design" style="display:block" transform="translate(250.002 260.417) scale(20.83)"/>
                </svg>
                <span class="text">بسته های کاری
                    @can('isAdmin')
                        <span class="badge-menu badge-danger num-fa">{{\Modules\WorkPackageManager\Entities\WorkPackage::where('status', 'pre_accept')->count()}}</span>
                    @endcan
                    @cannot('isAdmin')
                        @can('isSectionManager')
                            <span class="badge-menu badge-danger num-fa">{{\Modules\WorkPackageManager\Entities\WorkPackage::where('status', 'pending')->count()}}</span>
                        @endcan
                    @endcannot
                </span>
            </a>
            <ul class="sub-menu-1 {{ (request()->is('dashboard/work-package*')) || Route::is('freelancer.offer*') ? 'd-block' : '' }}">
                <li class="{{ (request()->is('dashboard/work-package*')) || Route::is('freelancer.offer*') && (!request()->is('dashboard/work-package/create')) ? 'active-zir-menu' : '' }}">
                    <a href="{{ Url('dashboard/work-package/') }}"><span class="text">لیست بسته کاری</span></a>
                </li>
                @can('isWorkPackageManager')
                    <li class="{{ (request()->is('dashboard/work-package/create')) ? 'active-zir-menu' : '' }}">
                        <a href="{{ Url('dashboard/work-package/create') }}"><span class="text">افزودن بسته کاری</span></a>
                    </li>
                @endcan
            </ul>
    @endcanany
    @can('isAdmin')
        <li class="@if (request()->is('dashboard/contract*')) {{ 'active-menu' }}@endif">
            <a href="{{ Url('dashboard/contract') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><style>.st4 {
                            fill: #121331
                        }</style>
                    <path style="fill:none" d="M0 0h24v24H0z" id="bounding_box"/>
                    <g id="design">
                        <path class="st4" d="m19.78 8.72-6.5-6.5a.77.77 0 0 0-.52-.22H5.75C4.79 2 4 2.79 4 3.75v16.5c0 .96.79 1.75 1.75 1.75h12.5c.96 0 1.75-.79 1.75-1.75V9.24c0-.2-.09-.38-.22-.52zM13.5 4.56l3.94 3.94H13.5V4.56zm5 15.69c0 .14-.11.25-.25.25H5.75c-.14 0-.25-.11-.25-.25V3.75c0-.14.11-.25.25-.25H12v5.75c0 .41.34.75.75.75h5.75v10.25z"/>
                        <path class="st4" d="M19.78 8.72c.13.14.22.32.22.52 0-.19-.07-.38-.22-.52zm-6.5-6.5a.704.704 0 0 0-.52-.22c.2 0 .38.09.52.22zM15.25 14.5h-6.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h6.5c.41 0 .75.34.75.75s-.34.75-.75.75zM15.25 17.5h-6.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h6.5c.41 0 .75.34.75.75s-.34.75-.75.75z"/>
                    </g></svg>
                <span class="text">قراردادها<span class="badge-menu badge-danger num-fa">{{\Modules\Freelancer\Entities\FreelancerContract::where('status', 'freelancer_signed')->count()}}</span></span></a>
        </li>
    @endcan

    {{--@can('isAuthor')--}}
    {{--        <li class="child-menu @if (Request::is('dashboard/blog*')) {{ 'active-menu' }}@endif">--}}
    {{--            <a href="#" class="@if (Request::is('dashboard/blog*')) {{ 'active-sub-menu' }}@endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><style>.st4 {--}}
    {{--                            fill: #121331--}}
    {{--                        }</style>--}}
    {{--                    <path style="fill:none" d="M0 0h24v24H0z" id="bounding_box"/><g id="design"><path class="st4" d="M20.25 9H16V3.75c0-.41-.34-.75-.75-.75H3.75c-.41 0-.75.34-.75.75v14C3 19.54 4.46 21 6.25 21h11.5c1.79 0 3.25-1.46 3.25-3.25v-8c0-.41-.34-.75-.75-.75zm-14 10.5c-.96 0-1.75-.79-1.75-1.75V4.5h10v13.25c0 .64.19 1.24.51 1.75H6.25zm13.25-1.75c0 .76-.5 1.41-1.18 1.64-.18.08-.37.11-.57.11-.38 0-.74-.13-1.03-.35a1.7 1.7 0 0 1-.72-1.4V10.5h3.5v7.25z"/><path class="st4" d="M12.25 6h-5.5c-.41 0-.75.34-.75.75v3.5c0 .41.34.75.75.75h5.5c.41 0 .75-.34.75-.75v-3.5c0-.41-.34-.75-.75-.75zm-.75 3.5h-4v-2h4v2zM12.25 14h-5.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h5.5c.41 0 .75.34.75.75s-.34.75-.75.75zM12.25 17.06h-5.5a.749.749 0 1 1 0-1.5h5.5c.41 0 .75.34.75.75s-.34.75-.75.75z"/></g></svg><span class="text">وبلاگ</span></a>--}}
    {{--            <ul class="sub-menu-1" @if ( Request::is('dashboard/blog*') ) style="display: block"@endif>--}}
    {{--                <li class="@if ( Request::is('dashboard/blog') ) {{ 'active-zir-menu' }}@endif">--}}
    {{--                    <a href="{{ Url('dashboard/blog') }}"><span class="text">همه مطالب</span></a>--}}
    {{--                </li>--}}
    {{--                <li class="@if ( Request::is('dashboard/blog/create') ) {{ 'active-zir-menu' }}@endif">--}}
    {{--                    <a href="{{ Url('dashboard/blog/create') }}"><span class="text">افزودن مطلب</span></a>--}}
    {{--                </li>--}}
    {{--                <li class="@if ( Request::is('dashboard/blog-category*') ) {{ 'active-zir-menu' }}@endif">--}}
    {{--                    <a href="{{ Url('dashboard/blog-category') }}"><span class="text">دسته بندی</span></a>--}}
    {{--                </li>--}}
    {{--            </ul>--}}
    {{--        </li>--}}

    {{--        <li class="@if (Request::is('dashboard/comment*')) {{ 'active-menu' }}@endif">--}}
    {{--            <a href="{{ url('dashboard/comment') }}" class="@if (Request::is('dashboard/comment*')) {{ 'active-sub-menu' }}@endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><style>.st4 {--}}
    {{--                            fill: #121331--}}
    {{--                        }</style>--}}
    {{--                    <path style="fill:none" d="M0 0h24v24H0z" id="bounding_box"/><g id="design"><path class="st4" d="M15.25 20H2.75c-.41 0-.75-.34-.75-.75v-8.5C2 7.03 5.03 4 8.75 4h6.5C18.97 4 22 7.03 22 10.75v2.5c0 3.72-3.03 6.75-6.75 6.75zM3.5 18.5h11.75c2.89 0 5.25-2.36 5.25-5.25v-2.5c0-2.9-2.36-5.25-5.25-5.25h-6.5c-2.89 0-5.25 2.35-5.25 5.25v7.75z"/><path class="st4" d="M8 13c-.26 0-.52-.11-.71-.29-.18-.19-.29-.45-.29-.71 0-.27.11-.52.29-.71.37-.37 1.05-.37 1.42 0l.12.15c.04.06.07.12.09.18.03.06.05.12.06.18.01.07.02.14.02.2s-.01.13-.02.2c-.01.06-.03.12-.06.18-.02.06-.05.12-.09.17-.04.06-.08.11-.12.16-.19.18-.45.29-.71.29zM12 13c-.13 0-.26-.03-.38-.08-.13-.05-.23-.12-.33-.21-.18-.19-.29-.45-.29-.71 0-.13.03-.26.08-.38.05-.13.12-.23.21-.33.1-.09.2-.16.33-.21.18-.08.38-.1.57-.06.07.01.13.03.19.06.06.02.12.05.17.09.06.03.11.08.16.12.09.1.16.2.21.33.05.12.08.25.08.38 0 .26-.11.52-.29.71-.05.04-.1.09-.16.12-.05.04-.11.07-.17.09-.06.03-.12.05-.19.06-.06.01-.13.02-.19.02zM16 13c-.06 0-.13-.01-.19-.02a.603.603 0 0 1-.19-.06.757.757 0 0 1-.18-.09l-.15-.12c-.18-.19-.29-.44-.29-.71 0-.13.03-.26.08-.38s.12-.23.21-.33l.15-.12c.06-.04.12-.07.18-.09.06-.03.12-.05.19-.06.32-.06.66.04.9.27.18.19.29.45.29.71 0 .06-.01.13-.02.2-.01.06-.03.12-.06.18-.02.06-.05.12-.09.18l-.12.15c-.19.18-.45.29-.71.29z"/></g></svg><span class="text">دیدگاه کاربران</span><span class="badge-menu badge-default num-fa">9+</span></a>--}}
    {{--        </li>--}}
    {{--    @endcan--}}

    @can('isAuthor')
        {{--        <li class="child-menu @if (Request::is('dashboard/blog*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="#" class="@if (Request::is('dashboard/blog*')) {{ 'active-sub-menu' }}@endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><style>.st4{fill:#121331}</style><path style="fill:none" d="M0 0h24v24H0z" id="bounding_box"/><g id="design"><path class="st4" d="M20.25 9H16V3.75c0-.41-.34-.75-.75-.75H3.75c-.41 0-.75.34-.75.75v14C3 19.54 4.46 21 6.25 21h11.5c1.79 0 3.25-1.46 3.25-3.25v-8c0-.41-.34-.75-.75-.75zm-14 10.5c-.96 0-1.75-.79-1.75-1.75V4.5h10v13.25c0 .64.19 1.24.51 1.75H6.25zm13.25-1.75c0 .76-.5 1.41-1.18 1.64-.18.08-.37.11-.57.11-.38 0-.74-.13-1.03-.35a1.7 1.7 0 0 1-.72-1.4V10.5h3.5v7.25z"/><path class="st4" d="M12.25 6h-5.5c-.41 0-.75.34-.75.75v3.5c0 .41.34.75.75.75h5.5c.41 0 .75-.34.75-.75v-3.5c0-.41-.34-.75-.75-.75zm-.75 3.5h-4v-2h4v2zM12.25 14h-5.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h5.5c.41 0 .75.34.75.75s-.34.75-.75.75zM12.25 17.06h-5.5a.749.749 0 1 1 0-1.5h5.5c.41 0 .75.34.75.75s-.34.75-.75.75z"/></g></svg><span class="text">وبلاگ</span></a>--}}
        {{--            <ul class="sub-menu-1" @if ( Request::is('dashboard/blog*') ) style="display: block"@endif>--}}
        {{--                <li class="@if ( Request::is('dashboard/blog') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/blog') }}"><span class="text">همه مطالب</span></a>--}}
        {{--                </li>--}}
        {{--                <li class="@if ( Request::is('dashboard/blog/create') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/blog/create') }}"><span class="text">افزودن مطلب</span></a>--}}
        {{--                </li>--}}
        {{--                <li class="@if ( Request::is('dashboard/blog-category*') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/blog-category') }}"><span class="text">دسته بندی</span></a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}

        {{--        <li class="@if (Request::is('dashboard/comment*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="{{ url('dashboard/comment') }}" class="@if (Request::is('dashboard/comment*')) {{ 'active-sub-menu' }}@endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><style>.st4{fill:#121331}</style><path style="fill:none" d="M0 0h24v24H0z" id="bounding_box"/><g id="design"><path class="st4" d="M15.25 20H2.75c-.41 0-.75-.34-.75-.75v-8.5C2 7.03 5.03 4 8.75 4h6.5C18.97 4 22 7.03 22 10.75v2.5c0 3.72-3.03 6.75-6.75 6.75zM3.5 18.5h11.75c2.89 0 5.25-2.36 5.25-5.25v-2.5c0-2.9-2.36-5.25-5.25-5.25h-6.5c-2.89 0-5.25 2.35-5.25 5.25v7.75z"/><path class="st4" d="M8 13c-.26 0-.52-.11-.71-.29-.18-.19-.29-.45-.29-.71 0-.27.11-.52.29-.71.37-.37 1.05-.37 1.42 0l.12.15c.04.06.07.12.09.18.03.06.05.12.06.18.01.07.02.14.02.2s-.01.13-.02.2c-.01.06-.03.12-.06.18-.02.06-.05.12-.09.17-.04.06-.08.11-.12.16-.19.18-.45.29-.71.29zM12 13c-.13 0-.26-.03-.38-.08-.13-.05-.23-.12-.33-.21-.18-.19-.29-.45-.29-.71 0-.13.03-.26.08-.38.05-.13.12-.23.21-.33.1-.09.2-.16.33-.21.18-.08.38-.1.57-.06.07.01.13.03.19.06.06.02.12.05.17.09.06.03.11.08.16.12.09.1.16.2.21.33.05.12.08.25.08.38 0 .26-.11.52-.29.71-.05.04-.1.09-.16.12-.05.04-.11.07-.17.09-.06.03-.12.05-.19.06-.06.01-.13.02-.19.02zM16 13c-.06 0-.13-.01-.19-.02a.603.603 0 0 1-.19-.06.757.757 0 0 1-.18-.09l-.15-.12c-.18-.19-.29-.44-.29-.71 0-.13.03-.26.08-.38s.12-.23.21-.33l.15-.12c.06-.04.12-.07.18-.09.06-.03.12-.05.19-.06.32-.06.66.04.9.27.18.19.29.45.29.71 0 .06-.01.13-.02.2-.01.06-.03.12-.06.18-.02.06-.05.12-.09.18l-.12.15c-.19.18-.45.29-.71.29z"/></g></svg><span class="text">دیدگاه کاربران</span><span class="badge-menu badge-default num-fa">{{ \Modules\CommentSystem\Entities\CommentSystem::where('status', '=', 'new' )->count() }}</span></a>--}}
        {{--        </li>--}}

        {{--        <li class="child-menu @if (Request::is('dashboard/page-builder*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="#" class="@if (Request::is('dashboard/page-builder*')) {{ 'active-sub-menu' }}@endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><style>.st4{fill:#121331}</style><path style="fill:none" d="M0 0h24v24H0z" id="bounding_box"/><g id="design"><path class="st4" d="m19.78 8.72-6.5-6.5a.77.77 0 0 0-.52-.22H5.75C4.79 2 4 2.79 4 3.75v16.5c0 .96.79 1.75 1.75 1.75h12.5c.96 0 1.75-.79 1.75-1.75V9.24c0-.2-.09-.38-.22-.52zM13.5 4.56l3.94 3.94H13.5V4.56zm5 15.69c0 .14-.11.25-.25.25H5.75c-.14 0-.25-.11-.25-.25V3.75c0-.14.11-.25.25-.25H12v5.75c0 .41.34.75.75.75h5.75v10.25z"/><path class="st4" d="M19.78 8.72c.13.14.22.32.22.52 0-.19-.07-.38-.22-.52zm-6.5-6.5a.704.704 0 0 0-.52-.22c.2 0 .38.09.52.22zM15.25 14.5h-6.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h6.5c.41 0 .75.34.75.75s-.34.75-.75.75zM15.25 17.5h-6.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h6.5c.41 0 .75.34.75.75s-.34.75-.75.75z"/></g></svg><span class="text">برگه ها</span></a>--}}
        {{--            <ul class="sub-menu-1" @if ( Request::is('dashboard/page-builder*') ) style="display: block"@endif>--}}
        {{--                <li class="@if ( Request::is('dashboard/page-builder') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/page-builder') }}"><span class="text">همه برگه ها</span></a>--}}
        {{--                </li>--}}
        {{--                <li class="@if ( Request::is('dashboard/page-builder/create') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/page-builder/create') }}"><span class="text">افزودن برگه</span></a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
    @endcan

    @can('isOperator')
        <li class="@if (Request::is('dashboard/payment*')) {{ 'active-menu' }}@endif">
            <a href="{{ url('dashboard/payment') }}" class="@if (Request::is('dashboard/payment*')) {{ 'active-sub-menu' }}@endif">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><path style="fill:none" d="M0 0h24v24H0z" id="bounding_area"/>
                    <g id="design">
                        <path class="st1" d="M10.25 17h-4.5c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h4.5c.41 0 .75.34.75.75s-.34.75-.75.75z"/>
                        <path class="st1" d="M20.25 4H3.75C2.79 4 2 4.79 2 5.75v12.5c0 .96.79 1.75 1.75 1.75h16.5c.97 0 1.75-.79 1.75-1.75V5.75C22 4.79 21.22 4 20.25 4zM3.5 5.75c0-.14.11-.25.25-.25h16.5c.14 0 .25.11.25.25V8h-17V5.75zm0 3.75h17v2h-17v-2zm17 8.75c0 .14-.11.25-.25.25H3.75c-.14 0-.25-.11-.25-.25V13h17v5.25z"/>
                    </g></svg>
                <span class="text">صورتحساب ها<span class="badge-menu badge-success num-fa">{{ \Modules\Payment\Entities\Payment::where('status', 'pending')->get()->count() }}</span></span></a>
        </li>

        <li class="child-menu @if (Request::is('dashboard/support*')) {{ 'active-menu' }}@endif">
            <a href="#" class="@if (Request::is('dashboard/support*')) {{ 'active-sub-menu' }}@endif">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><path style="fill:none" d="M0 0h24v24H0z" id="bounding_area"></path>
                    <path d="M18 14.75h2.3a.749.749 0 1 0 0-1.5H18V11.5h2.3a.749.749 0 1 0 0-1.5H18v-.26c0-.97-.78-1.75-1.75-1.75H16c0-1.34-.66-2.53-1.68-3.25l1.45-1.45c.29-.3.29-.77 0-1.07a.754.754 0 0 0-1.06 0l-1.86 1.86c-.27-.06-.56-.09-.85-.09-.3 0-.59.03-.86.09L9.28 2.22a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06l1.46 1.46A3.97 3.97 0 0 0 8 7.99h-.25C6.79 7.99 6 8.77 6 9.74V10H3.79c-.41 0-.75.34-.75.75s.34.75.75.75H6v1.75H3.79c-.41 0-.75.34-.75.75s.34.75.75.75H6v1.5c0 .08 0 .17.01.25H3.79c-.41 0-.75.34-.75.75s.34.75.75.75h2.49a5.75 5.75 0 0 0 5.47 3.99h.51c2.56 0 4.72-1.68 5.46-3.99h2.58a.749.749 0 1 0 0-1.5h-2.31c.01-.08.01-.17.01-.25v-1.5zm-6-9.26c.26 0 .51.04.74.11.02.01.04.01.06.02.99.34 1.7 1.27 1.7 2.37h-5a2.5 2.5 0 0 1 2.5-2.5zm4.5 10.76c0 2.17-1.64 3.97-3.75 4.21v-6.71c0-.41-.34-.75-.75-.75s-.75.34-.75.75v6.71c-2.11-.25-3.75-2.04-3.75-4.21V9.74c0-.14.12-.25.25-.25h8.5c.14 0 .25.11.25.25v6.51z" id="design"></path></svg>
                <span class="text">پشتیبانی</span><span class="badge-menu badge-warning num-fa">{{ \Modules\SupportSystem\Entities\SupportSystem::where('status', 'new')->where('status', 'replay')->get()->count() }}</span></a>
            <ul class="sub-menu-1" @if (Request::is('dashboard/support*') || Request::is('dashboard/resume*')) style="display: block"@endif>
                <li class="@if ( Request::is('dashboard/support') ) {{ 'active-zir-menu' }}@endif">
                    <a href="{{ url('dashboard/support') }}"><span class="text">همه تیکت ها</span></a>
                </li>
                <li class="@if ( Request::is('dashboard/support-departments*') ) {{ 'active-zir-menu' }}@endif">
                    <a href="{{ Url('dashboard/support-departments') }}"><span class="text">دپارتمان ها</span></a>
                </li>
            </ul>
        </li>

        {{--        <li class="child-menu @if (Request::is('dashboard/question-center*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="#" class="@if (Request::is('dashboard/question-center*')) {{ 'active-sub-menu' }}@endif"><i class="zmdi zmdi-help-outline"></i><span class="text">مرکز سوالات</span></a>--}}
        {{--            <ul class="sub-menu-1" @if ( Request::is('dashboard/question-center*') ) style="display: block"@endif>--}}
        {{--                <li class="@if ( Request::is('dashboard/question-center') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/question-center') }}"><span class="text">همه سوالات</span></a>--}}
        {{--                </li>--}}
        {{--                <li class="@if ( Request::is('dashboard/question-center/create*') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/question-center/create') }}"><span class="text">افزودن سوال</span></a>--}}
        {{--                </li>--}}
        {{--                <li class="@if ( Request::is('dashboard/question-center-category*') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/question-center-category') }}"><span class="text">دسته بندی</span></a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}

        {{--        <li class="@if (Request::is('dashboard/null*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="{{ url('dashboard/') }}" class="@if (Request::is('dashboard/null*')) {{ 'active-sub-menu' }}@endif"><i class="zmdi zmdi-notifications-none"></i><span class="text">اعلانات</span><span class="badge-menu badge-danger num-fa">9</span></a>--}}
        {{--        </li>--}}

        {{--        <li class="@if (Request::is('dashboard/consultation-request*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="{{ url('dashboard/consultation-request') }}" class="@if (Request::is('dashboard/consultation-request*')) {{ 'active-sub-menu' }}@endif"><i class="zmdi zmdi-headset-mic"></i><span class="text">تماس ها</span><span class="badge-menu badge-default num-fa">{{ \Modules\ConsultationRequest\Entities\ConsultationRequest::where('status', 'new')->get()->count() }}</span></a>--}}
        {{--        </li>--}}

        {{--        <li class="child-menu @if (Request::is('dashboard/support*')) {{ 'active-menu' }}@endif">--}}
        {{--            <a href="#" class="@if (Request::is('dashboard/support*')) {{ 'active-sub-menu' }}@endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve"><path style="fill:none" d="M0 0h24v24H0z" id="bounding_area"></path><path d="M18 14.75h2.3a.749.749 0 1 0 0-1.5H18V11.5h2.3a.749.749 0 1 0 0-1.5H18v-.26c0-.97-.78-1.75-1.75-1.75H16c0-1.34-.66-2.53-1.68-3.25l1.45-1.45c.29-.3.29-.77 0-1.07a.754.754 0 0 0-1.06 0l-1.86 1.86c-.27-.06-.56-.09-.85-.09-.3 0-.59.03-.86.09L9.28 2.22a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06l1.46 1.46A3.97 3.97 0 0 0 8 7.99h-.25C6.79 7.99 6 8.77 6 9.74V10H3.79c-.41 0-.75.34-.75.75s.34.75.75.75H6v1.75H3.79c-.41 0-.75.34-.75.75s.34.75.75.75H6v1.5c0 .08 0 .17.01.25H3.79c-.41 0-.75.34-.75.75s.34.75.75.75h2.49a5.75 5.75 0 0 0 5.47 3.99h.51c2.56 0 4.72-1.68 5.46-3.99h2.58a.749.749 0 1 0 0-1.5h-2.31c.01-.08.01-.17.01-.25v-1.5zm-6-9.26c.26 0 .51.04.74.11.02.01.04.01.06.02.99.34 1.7 1.27 1.7 2.37h-5a2.5 2.5 0 0 1 2.5-2.5zm4.5 10.76c0 2.17-1.64 3.97-3.75 4.21v-6.71c0-.41-.34-.75-.75-.75s-.75.34-.75.75v6.71c-2.11-.25-3.75-2.04-3.75-4.21V9.74c0-.14.12-.25.25-.25h8.5c.14 0 .25.11.25.25v6.51z" id="design"></path></svg><span class="text">پشتیبانی</span><span class="badge-menu badge-warning num-fa">{{ \Modules\SupportSystem\Entities\SupportSystem::where('status', 'new')->where('status', 'replay')->get()->count() }}</span></a>--}}
        {{--            <ul class="sub-menu-1" @if (Request::is('dashboard/support*') || Request::is('dashboard/resume*')) style="display: block"@endif>--}}
        {{--                <li class="@if ( Request::is('dashboard/support') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ url('dashboard/support') }}"><span class="text">همه تیکت ها</span></a>--}}
        {{--                </li>--}}
        {{--                <li class="@if ( Request::is('dashboard/support-departments*') ) {{ 'active-zir-menu' }}@endif">--}}
        {{--                    <a href="{{ Url('dashboard/support-departments') }}"><span class="text">دپارتمان ها</span></a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
    @endcan

    <li class="child-menu @if (Request::is('dashboard/users*')) {{ 'active-menu' }}@endif">
        <a href="#" class="@if (Request::is('dashboard/users*')) {{ 'active-sub-menu' }}@endif">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="width:24px;height:24px;transform:translate3d(0,0,0);content-visibility:visible" viewBox="0 0 500 500">
                <path d="M393.729 99.186c-40.41-38.327-93.11-58.74-148.726-57.491-55.616 1.25-107.483 24.371-145.81 64.573-38.327 40.41-58.74 93.11-57.491 148.726 1.25 55.616 24.371 107.483 64.573 145.81 39.16 37.286 89.777 57.491 143.519 57.491H255c55.616-1.25 107.483-24.371 145.81-64.573s58.74-93.11 57.49-148.726c-1.457-55.616-24.37-107.483-64.572-145.81zM254.376 427.05c-41.868 1.041-82.07-12.498-114.356-38.536 9.581-26.245 34.786-44.576 63.323-44.576h93.735c28.537 0 53.741 18.33 63.323 44.368-30.204 24.163-67.073 37.702-106.025 38.744zm129.563-61.449c-16.872-31.661-49.992-52.908-87.07-52.908h-93.735c-37.077 0-70.405 21.247-87.069 53.117-26.87-31.037-42.077-69.99-42.91-111.44-1.25-47.285 16.04-92.278 48.742-126.439 32.704-34.161 76.655-53.741 123.939-54.783h4.374c45.618 0 88.736 17.29 122.064 48.95 34.161 32.704 53.741 76.655 54.783 123.94 1.041 43.95-14.373 86.235-43.118 119.563z"/>
                <path d="M255.21 104.185h-10.416c-37.285 0-67.697 30.412-67.697 67.698v41.66c0 37.285 30.412 67.697 67.697 67.697h62.49c8.54 0 15.623-7.082 15.623-15.622v-93.735c0-37.286-30.412-67.698-67.698-67.698zm36.452 145.81h-46.868c-19.996 0-36.452-16.456-36.452-36.452v-41.66c0-19.997 16.456-36.453 36.452-36.453h10.415c19.997 0 36.453 16.247 36.453 36.453v78.112z"/>
            </svg>
            <span class="text">مدیریت کاربران</span></a>
        <ul class="sub-menu-1" @if ( Request::is('dashboard/users*') ) style="display: block"@endif>
            @can('isAdmin')
                <li class="@if ( Request::is('dashboard/users') ) {{ 'active-zir-menu' }}@endif">
                    <a href="{{ Url('dashboard/users') }}"><span class="text">کاربران</span></a>
                </li>
                <li class="@if ( Request::is('dashboard/users/create') ) {{ 'active-zir-menu' }}@endif">
                    <a href="{{ Url('dashboard/users/create') }}"><span class="text">افزودن کاربر</span></a>
                </li>
            @endcan
            <li class="@if ( Request::is('dashboard/users/'.auth()->user()->id  . '/edit') ) {{ 'active-zir-menu' }}@endif">
                <a href="{{ Url('dashboard/users/'. auth()->user()->id.'/edit')}}"><span class="text">پروفایل من</span></a>
            </li>
        </ul>
    </li>
</ul>
