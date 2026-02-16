@php
    use Modules\Master\app\Models\Company;
    session()->forget('company_logo_url');
    $company = Company::first();
    if ($company && $company->company_logo) {
        $logoUrl = asset('storage/company_logos/' . $company->company_logo);
        session(['company_logo_url' => $logoUrl]);
    } else {
        $logoUrl = asset('uploads/default_company_logo.png');
        session(['company_logo_url' => null]); // or set a default image path
    }
    $type = session('type'); // 'user' or 'employee'
    $id = session('id');

    $employee = auth('employee')->user();
    $user     = auth('web')->user();
    $empDesig = $employee->designation ?? '';

@endphp

<aside class="main-sidebar elevation-4 sidebar-dark-navy">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{asset('admin-assets/dist/img/PNG.png')}}" alt="Logo" class="brand-image" style="width: 230px; height: auto; display: block; margin: 0; padding: 1px;">
        <span class="brand-text font-weight-light"></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ menuActive(['dashboard'], 'active') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(auth('web')->check() || (auth('employee')->check() && !in_array($empDesig, ['Employee'])))
                    <li class="nav-item">
                        <a href="{{ route('projects.index') }}" class="nav-link {{ menuActive(['projects.index', 'projects.create', 'projects.edit', 'projects.show'], 'active') }}">
                            <i class="nav-icon fas fa-folder-open"></i>
                            <p>Projects</p>
                        </a>
                    </li>
                @endif
                
                {{-- Employee-specific menus --}}
                @if(auth('employee')->check() && !in_array($empDesig, ['Employee']))
                    <li class="nav-item">
                        <a href="{{ route('projections.index') }}" class="nav-link {{ menuActive(['projections.index', 'projections.create', 'projections.edit', 'projections.show'], 'active') }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Projections</p>
                        </a>
                    </li>
                @endif
                @if(auth('employee')->check()) 
                    <li class="nav-item">
                        <a href="{{ route('timesheets.index') }}" class="nav-link {{ menuActive(['timesheets.index', 'timesheets.edit', 'timesheets.create', 'timesheets.show'], 'active') }}">
                            <i class="nav-icon fas fa-user-clock"></i>
                            <p>Timesheet</p>
                        </a>
                    </li>
                @endif
                {{-- Admin / PMO: show Master & Utility --}}
                @if(auth('employee')->check() && in_array($empDesig, ['Admin','PMO']))
                    <!-- Master Menu -->
                    <li class="nav-item {{ menuActive(['companies.index', 'companies.edit', 'companies.create', 'companies.show', 'users.index', 'users.create', 'users.edit', 'users.show', 'employees.index', 'employees.edit', 'employees.create', 'employees.show', 'vendors.index', 'vendors.create', 'vendors.edit', 'vendors.show','tasks.index', 'tasks.create', 'tasks.edit', 'tasks.show'], 'menu-open') }}">
                        <a href="#" class="nav-link {{ menuActive(['companies.index', 'companies.edit', 'companies.create', 'companies.show', 'users.index', 'users.create', 'users.edit', 'users.show', 'employees.index', 'employees.edit', 'employees.create', 'employees.show', 'vendors.index', 'vendors.create', 'vendors.edit', 'vendors.show','tasks.index', 'tasks.create', 'tasks.edit', 'tasks.show'], 'active') }}">
                            <i class="nav-icon far fa-newspaper"></i>
                            <p>Master <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('companies.index') }}" class="nav-link {{ menuActive(['companies.index', 'companies.edit', 'companies.create', 'companies.show'], 'active') }}">
                                    <i class="nav-icon far fa-building"></i>
                                    <p>Company</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link {{ menuActive(['users.index', 'users.create', 'users.edit', 'users.show'], 'active') }}">
                                    <i class="nav-icon fas fa-user-cog"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employees.index') }}" class="nav-link {{ menuActive(['employees.index', 'employees.edit', 'employees.create', 'employees.show'], 'active') }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Employees</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendors.index') }}" class="nav-link {{ menuActive(['vendors.index', 'vendors.edit', 'vendors.create', 'vendors.show'], 'active') }}">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>Vendors</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tasks.index') }}" class="nav-link {{ menuActive(['tasks.index', 'tasks.create', 'tasks.edit', 'tasks.show'], 'active') }}">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>Tasks</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Utility -->
                    <li class="nav-item {{ menuActive(['profile.dbbackup', 'profile.restore'], 'menu-open') }}">
                        <a href="#" class="nav-link {{ menuActive(['dbbackup', 'restore'], 'active') }}">
                            <i class="nav-icon fas fa-toolbox"></i>
                            <p>Utility <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('dbbackup') }}" class="nav-link {{ menuActive(['dbbackup'], 'active') }}">
                                    <i class="nav-icon fas fa-database"></i>
                                    <p>Back Up</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('restore') }}" class="nav-link {{ menuActive(['profile.restore'], 'active') }}">
                                    <i class="nav-icon far fa-window-restore"></i>
                                    <p>Restore</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
