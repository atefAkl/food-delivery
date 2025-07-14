<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">
                <img class="img-40 img-radius" src="{{ asset('assets/images/avatar-4.jpg') }}" alt="User-Profile-Image">
                <div class="user-details">
                    <span>{{ Auth::user()->name }}</span>
                    <span id="more-details">{{ Auth::user()->role ?? 'User' }}<i class="ti-angle-down"></i></span>
                </div>
            </div>

            <div class="main-menu-content">
                <ul>
                    <li class="more-details">
                        <a href="/profile"><i class="ti-user"></i>View Profile</a>
                        <a href="/admin/settings"><i class="ti-settings"></i>Settings</a>
                        <a href="{{ route('admin-logout') }}"><i class="ti-layout-sidebar-left"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pcoded-search">
            <span class="searchbar-toggle"> </span>
            <div class="pcoded-search-box ">
                <input type="text" placeholder="Search">
                <span class="search-icon"><i class="ti-search" aria-hidden="true"></i></span>
            </div>
        </div>
        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Layout</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="active">
                <a href="{{ route('admin-dashboard') }}">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li
                class="pcoded-hasmenu {{ Route::is('admin-customers-*') || Route::is('admin-restaurants-*') || Route::is('admin-deliveries-*') || Route::is('admin-admins-*') ? 'pcoded-trigger active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.basic-components.main">Partners</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ Route::is('admin-customers-*') ? 'active' : '' }}">
                        <a href="{{ route('admin-customers-list') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Customers</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ Route::is('admin-restaurants-*') ? 'active' : '' }}">
                        <a href="{{ route('admin-restaurants-list') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Restaurants</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ Route::is('admin-admins-*') ? 'active' : '' }}">
                        <a href="{{ route('admin-admins-list') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Admins</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ Route::is('admin-deliveries-*') ? 'active' : '' }}">
                        <a href="{{ route('admin-deliveries-list') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Deliveries</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- Interactions --}}
            <li
                class="pcoded-hasmenu {{ Route::is('admin-orders-*') || Route::is('admin-reviews-*') || Route::is('admin-deliveries-*') || Route::is('admin-admins-*') ? 'pcoded-trigger active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.basic-components.main">{{__('Interactions')}}</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ Route::is('admin-orders-*') ? 'active' : '' }}">
                        <a href="{{ route('admin-orders-list') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">{{__('Orders')}}</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ Route::is('admin-reviews-*') ? 'active' : '' }}">
                        <a href="{{ route('admin-reviews-list') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">{{__('Reviews')}}</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
</nav>