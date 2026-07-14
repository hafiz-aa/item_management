<nav class="sidebar sidebar-dark bg-dark d-flex flex-column flex-shrink-0 p-0 text-white">
    <div class="sidebar-header d-flex align-items-center justify-content-between p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center gap-2 overflow-hidden sidebar-brand">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                style="width: 38px; height: 38px;">
                <i class="bi bi-box-seam text-white fs-5"></i>
            </div>
            <div class="sidebar-label">
                <h6 class="mb-0 fw-bold text-nowrap">{{ config('app.name') }}</h6>
                <small class="text-secondary text-nowrap">Item Management</small>
            </div>
        </div>
        <button id="sidebarToggle" class="btn btn-sm btn-outline-light border-0 flex-shrink-0" type="button" title="Minimize sidebar">
            <i class="bi bi-chevron-left sidebar-toggle-icon"></i>
        </button>
    </div>

    <div class="sidebar-menu flex-grow-1 overflow-auto py-2">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item px-2 mb-1">
                <a href="{{ route('dashboard') }}"
                    class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : 'text-white' }}"
                    data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Dashboard">
                    <i class="bi bi-speedometer2 me-2"></i><span class="sidebar-label">Dashboard</span>
                </a>
            </li>

            @can('item.view')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('items.index') }}"
                        class="nav-link text-white {{ request()->routeIs('items.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Items">
                        <i class="bi bi-box me-2"></i><span class="sidebar-label">Items</span>
                    </a>
                </li>
            @endcan

            @can('warehouse.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('warehouses.index') }}"
                        class="nav-link text-white {{ request()->routeIs('warehouses.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Warehouses">
                        <i class="bi bi-building me-2"></i><span class="sidebar-label">Warehouses</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item px-2 mt-2 mb-1 sidebar-section-header">
                <small class="text-secondary text-uppercase px-2 fw-bold sidebar-label">Management</small>
            </li>

            @can('user.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('users.index') }}"
                        class="nav-link text-white {{ request()->routeIs('users.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Users">
                        <i class="bi bi-people me-2"></i><span class="sidebar-label">Users</span>
                    </a>
                </li>
            @endcan

            @can('role.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('roles.index') }}"
                        class="nav-link text-white {{ request()->routeIs('roles.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Roles">
                        <i class="bi bi-shield me-2"></i><span class="sidebar-label">Roles</span>
                    </a>
                </li>
            @endcan

            @can('permission.manage')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('permissions.index') }}"
                        class="nav-link text-white {{ request()->routeIs('permissions.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Permissions">
                        <i class="bi bi-key me-2"></i><span class="sidebar-label">Permissions</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item px-2 mt-2 mb-1 sidebar-section-header">
                <small class="text-secondary text-uppercase px-2 fw-bold sidebar-label">Reports</small>
            </li>

            @can('item.export')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('export.items') }}" class="nav-link text-white"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Export Excel">
                        <i class="bi bi-download me-2"></i><span class="sidebar-label">Export Excel</span>
                    </a>
                </li>
            @endcan

            @can('item.import')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('import.create') }}"
                        class="nav-link text-white {{ request()->routeIs('import.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Import Excel">
                        <i class="bi bi-upload me-2"></i><span class="sidebar-label">Import Excel</span>
                    </a>
                </li>
            @endcan

            @can('activity-log.view')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('activity-logs.index') }}"
                        class="nav-link text-white {{ request()->routeIs('activity-logs.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Activity Logs">
                        <i class="bi bi-clock-history me-2"></i><span class="sidebar-label">Activity Logs</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>

    <div class="sidebar-footer border-top border-secondary p-3">
        <small class="text-secondary d-block text-center sidebar-label">v1.0.0</small>
    </div>
</nav>
