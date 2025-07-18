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

@endphp

<aside class="main-sidebar elevation-4 sidebar-light-navy">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ $logoUrl }}" alt="Logo" class="brand-image" style="width: 230px; height: auto; display: block; margin: 0; padding: 1px;">
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

                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" class="nav-link {{ menuActive(['projects.index', 'projects.create', 'projects.edit', 'projects.show'], 'active') }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Projects</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('vendors.index') }}" class="nav-link {{ menuActive(['vendors.index', 'vendors.create', 'vendors.edit', 'vendors.show'], 'active') }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Suppliers</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('customers.index') }}" class="nav-link {{ menuActive(['customers.index', 'customers.create', 'customers.edit', 'customers.show'], 'active') }}">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>Customers</p>
                    </a>
                </li> --}}

                <!-- Master Menu -->
                {{-- <li class="nav-item {{ menuActive(['brands.index', 'brands.create', 'brands.edit', 'brands.show', 'groups.index', 'groups.create', 'groups.edit', 'subcategory.index', 'subcategory.create', 'subcategory.edit', 'category.index', 'category.create', 'category.edit', 'excategory.index', 'excategory.create', 'excategory.edit'], 'menu-open') }}">
                    <a href="#" class="nav-link {{ menuActive(['brands.index', 'brands.create', 'brands.edit', 'brands.show', 'groups.index', 'groups.create', 'groups.edit', 'subcategory.index', 'subcategory.create', 'subcategory.edit', 'category.index', 'category.create', 'category.edit', 'excategory.index', 'excategory.create', 'excategory.edit'], 'active') }}">
                        <i class="nav-icon far fa-newspaper"></i>
                        <p>Master <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Product Category -->
                        <li class="nav-item {{ menuActive(['category.index', 'category.create', 'category.edit', 'subcategory.index', 'subcategory.create', 'subcategory.edit','brands.index', 'brands.create', 'brands.edit', 'brands.show','groups.index', 'groups.create', 'groups.edit'], 'menu-open') }}">
                            <a href="#" class="nav-link {{ menuActive(['category.index', 'category.create', 'category.edit', 'subcategory.index', 'subcategory.create', 'subcategory.edit','brands.index', 'brands.create', 'brands.edit', 'brands.show','groups.index', 'groups.create', 'groups.edit'], 'active') }}">
                                <i class="fas fa-shopping-basket nav-icon"></i>
                                <p>Product Category <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}" class="nav-link {{ menuActive(['category.index', 'category.create', 'category.edit'], 'active') }}">
                                        <i class="fas fa-tasks nav-icon"></i>
                                        <p>Manage Category</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('subcategory.index') }}" class="nav-link {{ menuActive(['subcategory.index', 'subcategory.create', 'subcategory.edit', 'subcategory.show'], 'active') }}">
                                        <i class="fas fa-tags nav-icon"></i>
                                        <p>Manage Sub Category</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('brands.index') }}" class="nav-link {{ menuActive(['brands.index', 'brands.create', 'brands.edit', 'brands.show'], 'active') }}">
                                        <i class="nav-icon far fa-star"></i>
                                        <p>Brand</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('groups.index') }}" class="nav-link {{ menuActive(['groups.index', 'groups.create', 'groups.edit'], 'active') }}">
                                        <i class="far fa-object-group nav-icon"></i>
                                        <p>Manage Groups</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('excategory.index') }}" class="nav-link {{ menuActive(['excategory.index', 'excategory.create', 'excategory.edit'], 'active') }}">
                                <i class="nav-icon fas fa-rupee-sign"></i>
                                <p>Manage Ex-Category</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                @if(auth()->user()->role != 'Client')
                <!-- Master -->
                <li class="nav-item {{ menuActive(['companies.index', 'companies.edit', 'companies.create', 'companies.show', 'users.index', 'users.create', 'users.edit', 'users.show'], 'menu-open') }}">
                    <a href="#" class="nav-link {{ menuActive(['companies.index', 'companies.edit', 'companies.create', 'companies.show', 'users.index', 'users.create', 'users.edit', 'users.show'], 'active') }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Master <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ menuActive(['users.index', 'users.create', 'users.edit', 'users.show'], 'active') }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('companies.index') }}" class="nav-link {{ menuActive(['companies.index', 'companies.edit', 'companies.create', 'companies.show'], 'active') }}">
                                <i class="nav-icon far fa-building"></i>
                                <p>Company</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Members -->
                <li class="nav-item {{ menuActive(['employees.index', 'employees.edit', 'employees.create', 'employees.show', 'vendors.index', 'vendors.create', 'vendors.edit', 'vendors.show'], 'menu-open') }}">
                    <a href="#" class="nav-link {{ menuActive(['employees.index', 'employees.edit', 'employees.create', 'employees.show', 'vendors.index', 'vendors.create', 'vendors.edit', 'vendors.show'], 'active') }}">
                        <i class="nav-icon fas fa-spinner"></i>
                        <p>Members <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employees.index') }}" class="nav-link {{ menuActive(['employees.index', 'employees.edit', 'employees.create', 'employees.show'], 'active') }}">
                                <i class="nav-icon fas fa-database"></i>
                                <p>Employees</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendors.index') }}" class="nav-link {{ menuActive(['vendors.index', 'vendors.edit', 'vendors.create', 'vendors.show'], 'active') }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Vendors</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Utility -->
                <li class="nav-item {{ menuActive(['profile.dbbackup', 'profile.restore'], 'menu-open') }}">
                    <a href="#" class="nav-link {{ menuActive(['dbbackup', 'restore'], 'active') }}">
                        <i class="nav-icon fas fa-spinner"></i>
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
