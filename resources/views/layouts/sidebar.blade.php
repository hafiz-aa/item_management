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

            @canany(['item.view', 'item-category.view', 'item-description.view'])
                <li class="nav-item px-2 mb-1">
                    <button class="nav-link text-white w-100 text-start {{ request()->routeIs('items.*', 'items.all-branches', 'items.summary', 'item-categories.*', 'item-descriptions.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#itemMenu">
                        <i class="bi bi-box me-2"></i><span class="sidebar-label">Item</span>
                        <i class="bi bi-chevron-down float-end mt-1"></i>
                    </button>
                    <div id="itemMenu" class="collapse {{ request()->routeIs('items.*', 'items.all-branches', 'items.summary', 'item-categories.*', 'item-descriptions.*') ? 'show' : '' }}">
                        <ul class="nav nav-pills flex-column ps-3">
                            @can('item-category.view')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('item-categories.index') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('item-categories.*') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-tags me-2"></i><span class="sidebar-label">Item Categories</span>
                                    </a>
                                </li>
                            @endcan
                            @can('item-description.view')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('item-descriptions.index') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('item-descriptions.*') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-list-ul me-2"></i><span class="sidebar-label">Item Descriptions</span>
                                    </a>
                                </li>
                            @endcan
                            @can('item.view')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('items.index') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('items.*') && !request()->routeIs('items.all-branches', 'items.summary') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-box me-2"></i><span class="sidebar-label">Items</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a href="{{ route('items.all-branches') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('items.all-branches') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-grid me-2"></i><span class="sidebar-label">Item All Branches</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a href="{{ route('items.summary') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('items.summary') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-bar-chart me-2"></i><span class="sidebar-label">Item Summary</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            <li class="nav-item px-2 mt-2 mb-1 sidebar-section-header">
                <small class="text-secondary text-uppercase px-2 fw-bold sidebar-label">Master Data</small>
            </li>

            @can('branch.view')
                <li class="nav-item px-2 mb-1">
                    <a href="{{ route('branches.index') }}"
                        class="nav-link text-white {{ request()->routeIs('branches.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Branches">
                        <i class="bi bi-diagram-3 me-2"></i><span class="sidebar-label">Branches</span>
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
                <small class="text-secondary text-uppercase px-2 fw-bold sidebar-label">Settings</small>
            </li>

            @canany(['user.manage'])
                <li class="nav-item px-2 mb-1">
                    <button class="nav-link text-white w-100 text-start {{ request()->routeIs('settings.*', 'uoms.*', 'customer-types.*', 'customers.*', 'users.*', 'profile.*', 'roles.*', 'permissions.*') ? 'active bg-primary' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#settingsMenu">
                        <i class="bi bi-gear me-2"></i><span class="sidebar-label">Settings</span>
                        <i class="bi bi-chevron-down float-end mt-1"></i>
                    </button>
                    <div id="settingsMenu" class="collapse {{ request()->routeIs('settings.*', 'uoms.*', 'customer-types.*', 'customers.*', 'users.*', 'profile.*', 'roles.*', 'permissions.*') ? 'show' : '' }}">
                        <ul class="nav nav-pills flex-column ps-3">
                            @can('user.manage')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('users.*') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-people me-2"></i><span class="sidebar-label">User</span>
                                    </a>
                                </li>
                            @endcan
                            @can('user.manage')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('settings.employee') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('settings.employee') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-person-badge me-2"></i><span class="sidebar-label">Employee</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item mb-1">
                                <a href="{{ route('uoms.index') }}"
                                    class="nav-link text-white py-1 {{ request()->routeIs('uoms.*') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-rulers me-2"></i><span class="sidebar-label">Unit of Measurement</span>
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="{{ route('customer-types.index') }}"
                                    class="nav-link text-white py-1 {{ request()->routeIs('customer-types.*') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-people-fill me-2"></i><span class="sidebar-label">Customer Type</span>
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="{{ route('customers.index') }}"
                                    class="nav-link text-white py-1 {{ request()->routeIs('customers.*') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-person-lines-fill me-2"></i><span class="sidebar-label">Customer</span>
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="{{ route('settings.vendor') }}"
                                    class="nav-link text-white py-1 {{ request()->routeIs('settings.vendor') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-truck me-2"></i><span class="sidebar-label">Vendor</span>
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="{{ route('settings.transaction-period') }}"
                                    class="nav-link text-white py-1 {{ request()->routeIs('settings.transaction-period') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-calendar-range me-2"></i><span class="sidebar-label">Transaction Period</span>
                                </a>
                            </li>
                            @can('role.manage')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('roles.*') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-shield me-2"></i><span class="sidebar-label">Roles</span>
                                    </a>
                                </li>
                            @endcan
                            @can('permission.manage')
                                <li class="nav-item mb-1">
                                    <a href="{{ route('permissions.index') }}"
                                        class="nav-link text-white py-1 {{ request()->routeIs('permissions.*') ? 'active bg-primary' : '' }}">
                                        <i class="bi bi-key me-2"></i><span class="sidebar-label">Permissions</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item mb-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="nav-link text-white py-1 {{ request()->routeIs('profile.*') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-person-circle me-2"></i><span class="sidebar-label">Profile</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany

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
