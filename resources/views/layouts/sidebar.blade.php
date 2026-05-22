<nav class="sidebar sidebar-dark bg-dark d-flex flex-column flex-shrink-0 p-0 text-white"
    style="width: 260px; min-height: 100vh;">
    <div class="sidebar-header d-flex align-items-center p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                style="width: 38px; height: 38px;">
                <i class="bi bi-box-seam text-white fs-5"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">{{ config('app.name') }}</h6>
                <small class="text-secondary">Item Management</small>
            </div>
        </div>
    </div>

    <div class="sidebar-menu flex-grow-1 overflow-auto py-2">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item px-2 mb-1">
                <a href="{{ route('dashboard') }}"
                    class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : 'text-white' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            @can('item.view')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('items.index') }}"
                        class="nav-link text-white {{ request()->routeIs('items.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-box me-2"></i> Items
                    </a>
                </li>
            @endcan

            @can('warehouse.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('warehouses.index') }}"
                        class="nav-link text-white {{ request()->routeIs('warehouses.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-building me-2"></i> Warehouses
                    </a>
                </li>
            @endcan

            <li class="nav-item px-2 mt-2 mb-1">
                <small class="text-secondary text-uppercase px-2 fw-bold">Management</small>
            </li>

            @can('user.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('users.index') }}"
                        class="nav-link text-white {{ request()->routeIs('users.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
            @endcan

            @can('role.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('roles.index') }}"
                        class="nav-link text-white {{ request()->routeIs('roles.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-shield me-2"></i> Roles
                    </a>
                </li>
            @endcan

            @can('permission.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('permissions.index') }}"
                        class="nav-link text-white {{ request()->routeIs('permissions.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-key me-2"></i> Permissions
                    </a>
                </li>
            @endcan

            <li class="nav-item px-2 mt-2 mb-1">
                <small class="text-secondary text-uppercase px-2 fw-bold">Reports</small>
            </li>

            @can('item.export')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('export.items') }}" class="nav-link text-white">
                        <i class="bi bi-download me-2"></i> Export Excel
                    </a>
                </li>
            @endcan

            @can('item.import')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('import.create') }}"
                        class="nav-link text-white {{ request()->routeIs('import.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-upload me-2"></i> Import Excel
                    </a>
                </li>
            @endcan

            @can('activity-log.view')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('activity-logs.index') }}"
                        class="nav-link text-white {{ request()->routeIs('activity-logs.*') ? 'active bg-primary' : '' }}">
                        <i class="bi bi-clock-history me-2"></i> Activity Logs
                    </a>
                </li>
            @endcan
        </ul>
    </div>

    <div class="sidebar-footer border-top border-secondary p-3">
        <small class="text-secondary d-block text-center">v1.0.0</small>
    </div>
</nav>
