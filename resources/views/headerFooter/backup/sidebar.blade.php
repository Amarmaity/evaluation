<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
        <img src="{{ asset('images/logo.png') }}" class="brand-image opacity-75 shadow">
        <span class="brand-text fw-light">Delostyle Studio</span>
        </a>
    </div>

    <div class="sidebar-wrapper">

        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                {{-- Dashboard Handelling --}}
                @php
                    $superadmin = route('super-admin-view');
                    $admin = route('admin-dashboard');
                    $manager = route('manager-dashboard');
                    $hr = route('hr-dashboard');
                    $users = route('users-dashboard');
                    $client = route('client-dashboard');


                    $userType = Session::get('user_type');
                    $dashboardLink = '#';

                    if ($userType === 'Super User') {
                        $dashboardLink = $superadmin;
                    } elseif ($userType == 'admin' || $userType == 3) {
                        $dashboardLink = $admin;
                    } elseif ($userType == 'manager' || $userType == 2) {
                        $dashboardLink = $manager;
                    } elseif ($userType == 'hr' || $userType == 4) {
                        $dashboardLink = $hr;
                    } elseif ($userType == 'users' || $userType == 5) {
                        $dashboardLink = $users;
                    }elseif ($userType == 'client'){
                        $dashboardLink = $client;
                    }


                @endphp

                @if(in_array($userType, ['Super User', 'admin', 'manager', 'hr', 'users','client']))
                    <li class="nav-item menu-open">
                        <a href="{{ $dashboardLink }}" class="nav-link active">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>
                                Dashboard
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                    </li>
                @endif

                {{-- @dd(Session::all()); --}}
                {{-- {{ dd(Session::get('user_type')) }} --}}
                @if(Session::get('user_type') === 'Super User')
                    <li class="nav-item">
                        <a href="{{route('add-user')}}" class="nav-link">
                            <i class="nav-icon bi bi-person-plus"></i>
                            <p>Add Users</p>
                        </a>
                    </li>
                @endif
                @if(Session::get('user_type') === 'Super User')
                    <li class="nav-item">
                        <a href="{{route('userlist')}}" class="nav-link">
                            <i class="bi bi-people"></i>
                            <p>List User's</p>
                        </a>
                    </li>
                @endif
                @if(Session::get('user_type') === 'Super User')
                    <li class="nav-item">
                        <a href="{{route('super.search')}}" class="nav-link">
                            <i class="nav-icon bi bi-search"></i>
                            <p>View All Review's</p>
                        </a>
                    </li>
                @endif
                @if(Session::get('user_type') === 'Super User')
                    <li class="nav-item">
                        <a href="{{route('appraisal-view')}}" class="nav-link">
                            <i class="nav-icon bi bi-graph-up-arrow"></i>
                            <p>Appraisal</p>
                        </a>
                    </li>
                @endif
                @if(Session::get('user_type') === 'Super User')
                    <li class="nav-item">
                        <a href="{{route('financial.view')}}" class="nav-link">
                            <i class="bi bi-calendar-range"></i>
                            <p>Financial Year</p>
                        </a>
                    </li>
                @endif
                @if(Session::get('user_type') === 'Super User')
                    <li class="nav-item">
                        <a href="{{route('financial-view-tables')}}" class="nav-link">
                            <i class="bi bi-bar-chart-line"></i>
                            <p>Financial Data</p>
                        </a>
                    </li>
                @endif

                @if(Session::get('user_type') === 'hr')
                    <li class="nav-item">
                        <a href="{{route('hr-review')}}" class="nav-link">
                            <i class="nav-icon bi bi-list"></i>
                            <p>HR Review</p>
                        </a>
                    </li>
                @endif

                @if(Session::get('user_type') === 'manager')
                    <li class="nav-item">
                        <a href="{{route('manager-review')}}" class="nav-link">
                            <i class="nav-icon bi bi-list"></i>
                            <p>Manager Review Section</p>
                        </a>
                    </li>
                @endif

                @if(Session::get('user_type') === 'admin')
                    <li class="nav-item">
                        <a href="{{route('admin-review')}}" class="nav-link">
                            <i class="nav-icon bi bi-list"></i>
                            <p>Admin Review Section</p>
                        </a>
                    </li>
                @endif

                @if(Session::get('user_type') === 'client')
                    <li class="nav-item">
                        <a href="{{route('client-review')}}" class="nav-link">
                            <i class="nav-icon bi bi-list"></i>
                            <p>Client Review Section</p>
                        </a>
                    </li>
                @endif

                {{-- @if(in_array(Session::get('user_type'), ['client']))
                <li class="nav-item">
                    <a href="{{route('logout-users')}}" class="nav-link" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Client Log Out</p>
                    </a>
                </li>
                @endif --}}

                @if(in_array(Session::get('user_type'), ['users', 'admin', 'hr', 'manager']))
                    <li class="nav-item">
                        <a href="{{route('input-evaluation')}}" class="nav-link">
                            <i class="nav-icon bi bi-file-earmark-check"></i>
                            <p>Review Yourself</p>
                        </a>
                    </li>
                @endif

                @if(Session::get('user_type') === 'users')
                    <li class="nav-item">
                        <a href="{{ route('get-review-reports', ['emp_id' => Session::get('employee_id')]) }}"
                            class="nav-link">
                            <i class="nav-icon bi bi-file-earmark-check"></i>
                            <p>View Review Report</p>
                        </a>
                    </li>
                @endif

                @if(Session::get('user_type') === 'hr')
                    <li class="nav-item">
                        <a href="{{route('hr-review-list')}}" class="nav-link">
                            <i class="nav-icon bi bi-file-earmark-check"></i>
                            <p>View Employees Review List</p>
                    <li>
                @endif

                    @if(Session::get('user_type') === 'admin')
                        <li class="nav-item">
                            <a href="{{route('admin-review-list')}}" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-check"></i>
                                <p>View Employees Review List</p>
                        <li>
                    @endif
                    @if(Session::get('user_type') === 'manager')
                        <li class="nav-item">
                            <a href="{{route('manager-review-list')}}" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-check"></i>
                                <p>View Employees Review List</p>
                        <li>
                    @endif
                    @if(Session::get('user_type') === 'client')
                        <li class="nav-item">
                            <a href="{{route('client-review-list')}}" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-check"></i>
                                <p>View Employees Review List</p>
                        <li>
                    @endif


                    @if(
                        Session::get('user_type') === 'Super User' || in_array(Session::get('user_type'), [
                            'users',
                            'admin',
                            'manager',
                            'hr'
                        ]) || in_array(Session::get('user_type'), ['client'])
                    )
                                            <a href="{{ route('logout-users') }}" class="nav-link"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="nav-icon bi bi-box-arrow-right"></i>
                                                <p>Log Out</p>
                                            </a>
                                            <form id="logout-form" action="{{ route('logout-users') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                    @endif
        </nav>
    </div>
</aside>