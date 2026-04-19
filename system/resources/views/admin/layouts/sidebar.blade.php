<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
        <h4>CMLW Portal</h4>
        {{-- <img src="{{asset('assets/images/logo.svg')}}" alt="Give Away Tips" width="150"> --}}
        {{-- <span class="brand-text font-weight-light">Give Away Tips</span> --}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex text-center">
            {{-- <div class="image">
          <img src="{{asset('assets/admin/dist/img/avatar3.png')}}" class="img-circle elevation-2" alt="User Image">
        </div> --}}
            <div class="info" style="margin:auto;">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @can('read-labours')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Labours',
                        'icon' => 'nav-icon fas fa-users',
                        'route' => route('admin.labours.index'),
                        'segment' => 'labours',
                    ])
                @endcan

                @can('read-children')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Children',
                        'icon' => 'nav-icon fas fa-users',
                        'route' => route('admin.children.index'),
                        'segment' => 'children',
                    ])
                @endcan

                @can('read-scholarships')
                    <li class="nav-item {{ request()->segment(2) == 'scholarships' ? 'menu-is-opening menu-open' : '' }}">
                        <a href="{{ route('admin.scholarships.general.index') }}"
                            class="nav-link {{ request()->segment(2) == 'scholarships' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                Scholarships
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ request()->segment(2) == 'scholarships' ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('admin.scholarships.general.index') }}"
                                    class="nav-link {{ request()->segment(3) == 'general' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>General</p>
                                </a>
                            </li>
                            @can('read-special-education')
                                <li class="nav-item">
                                    <a href="{{ route('admin.scholarships.special-education.index') }}"
                                        class="nav-link {{ request()->segment(3) == 'special-education' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Special Education</p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-top-position')
                                <li class="nav-item">
                                    <a href="{{ route('admin.scholarships.top-position.index') }}"
                                        class="nav-link  {{ request()->segment(3) == 'top-position' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Top 50</p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-quality-education')
                                <li class="nav-item">
                                    <a href="{{ route('admin.scholarships.quality-education.index') }}"
                                        class="nav-link  {{ request()->segment(3) == 'quality-education' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Quality Education</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('read-skill-development')
                    <li
                        class="nav-item {{ request()->segment(2) == 'skill-development' ? 'menu-is-opening menu-open' : '' }}">
                        <a href=""
                            class="nav-link {{ request()->segment(2) == 'skill-development' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>
                                Skill Development
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ request()->segment(2) == 'skill-development' ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('admin.skill-development.index', ['type' => 'gems-and-gemology']) }}"
                                    class="nav-link  {{ request()->has('type') && request()->type == 'gems-and-gemology' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gems & Gemology</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.skill-development.index', ['type' => 'lapidary']) }}"
                                    class="nav-link  {{ request()->has('type') && request()->type == 'lapidary' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Lapidary</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('read-disabled-mine-labour')
                    <li class="nav-item {{ request()->segment(2) == 'grants' ? 'menu-is-opening menu-open' : '' }}">
                        <a href="" class="nav-link {{ request()->segment(2) == 'grants' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-wheelchair"></i>
                            <p>
                                Grants
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ request()->segment(2) == 'grants' ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('admin.grants.disabled-mine-labour.index') }}"
                                    class="nav-link  {{ request()->segment(3) == 'disabled-mine-labour' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Disabled Labour</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.grants.pulmonary-mine-labour.index') }}"
                                    class="nav-link  {{ request()->segment(3) == 'pulmonary-mine-labour' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pulmonary Labour</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.grants.deceased-mine-labour.index') }}"
                                    class="nav-link  {{ request()->segment(3) == 'deceased-mine-labour' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Deceased Labour</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.grants.marriage-mine-labour.index') }}"
                                    class="nav-link  {{ request()->segment(3) == 'marriage-mine-labour' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Marriage Grant</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('read-complaints')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Complaints',
                        'icon' => 'nav-icon fas fa-exclamation-triangle',
                        'route' => route('admin.complaints.index'),
                        'segment' => 'complaints',
                    ])
                @endcan

                @can('read-schemes')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Schemes',
                        'icon' => 'nav-icon fas fa-sitemap',
                        'route' => route('admin.schemes.index'),
                        'segment' => 'schemes',
                    ])
                @endcan

                @can('read-object-heads')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Object Heads',
                        'icon' => 'nav-icon far fa-list-alt',
                        'route' => route('admin.object-heads.index'),
                        'segment' => 'object-heads',
                    ])
                @endcan

                @can('read-compilations')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Compilations',
                        'icon' => 'nav-icon fas fa-receipt',
                        'route' => route('admin.compilations.index'),
                        'segment' => 'compilations',
                    ])
                @endcan

                @can('read-reconciliations')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Reconciliations',
                        'icon' => 'nav-icon fas fa-compress-arrows-alt',
                        'route' => route('admin.reconciliations.index'),
                        'segment' => 'reconciliations',
                    ])
                @endcan


                @can('read-budgets')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Budgets',
                        'icon' => 'nav-icon fas fa-wallet',
                        'route' => route('admin.budgets.index'),
                        'segment' => 'budgets',
                    ])
                @endcan

                @can('read-accounts')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Accounts',
                        'icon' => 'nav-icon far fa-money-bill-alt',
                        'route' => route('admin.accounts.index'),
                        'segment' => 'accounts',
                    ])
                @endcan

                @can('read-mineral-titles')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Mineral Titles',
                        'icon' => 'nav-icon fas fa-gem',
                        'route' => route('admin.mineral-titles.index'),
                        'segment' => 'mineral-titles',
                    ])
                @endcan

                @can('read-offices')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Offices',
                        'icon' => 'nav-icon fa fa-building',
                        'route' => route('admin.offices.index'),
                        'segment' => 'offices',
                    ])
                @endcan

                @can('read-organizations')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Organizations',
                        'icon' => 'nav-icon fas fa-share-alt',
                        'route' => route('admin.organizations.index'),
                        'segment' => 'organizations',
                    ])
                @endcan

                @can('read-posts')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Cadres',
                        'icon' => 'nav-icon fas fa-address-card',
                        'route' => route('admin.posts.index'),
                        'segment' => 'posts',
                    ])
                @endcan

                @can('read-staffs')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Staffs',
                        'icon' => 'nav-icon fas fa-users',
                        'route' => route('admin.staffs.index'),
                        'segment' => 'staffs',
                    ])
                @endcan

                @can('read-districts')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Districts',
                        'icon' => 'nav-icon fas fa-sitemap',
                        'route' => route('admin.districts.index'),
                        'segment' => 'districts',
                    ])
                @endcan

                @can('read-users')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Users',
                        'icon' => 'nav-icon fas fa-users',
                        'route' => route('admin.users.index'),
                        'segment' => 'users',
                    ])
                @endcan

                @can('read-roles')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Roles',
                        'icon' => 'nav-icon fas fa-user-tag',
                        'route' => route('admin.roles.index'),
                        'segment' => 'roles',
                    ])
                @endcan

                @can('read-permissions')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Permissions',
                        'icon' => 'nav-icon fas fa-key',
                        'route' => route('admin.permissions.index'),
                        'segment' => 'permissions',
                    ])
                @endcan

                @can('read-modules')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Modules',
                        'icon' => 'nav-icon fas fa-shapes',
                        'route' => route('admin.modules.index'),
                        'segment' => 'modules',
                    ])
                @endcan

                @can('read-setting')
                    @include('admin.layouts.partials.navItem', [
                        'page' => 'Setting',
                        'icon' => 'nav-icon fas fa-shapes',
                        'route' => route('admin.setting.index'),
                        'segment' => 'setting',
                    ])
                @endcan

                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
