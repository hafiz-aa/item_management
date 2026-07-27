<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h6 d-lg-none">{{ config('app.name') }}</span>
        <div class="ms-auto d-flex align-items-center gap-3">
            @php
                $branchFilter = session('branch_filter');
                $selectedBranch = $branchFilter ? \App\Models\Branch::find($branchFilter) : null;
                $allBranches = \App\Models\Branch::orderBy('branch_code')->get();
            @endphp
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-building"></i>
                    <span>{{ $selectedBranch ? $selectedBranch->branch_name : 'Semua Branch' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 250px;">
                    <li><h6 class="dropdown-header">Filter Branch</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('branch-filter.clear') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item {{ !$selectedBranch ? 'active' : '' }}">
                                <i class="bi bi-grid me-2"></i> Semua Branch
                            </button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach($allBranches as $branch)
                        <li>
                            <form method="POST" action="{{ route('branch-filter.set') }}">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->branch_id }}">
                                <button type="submit" class="dropdown-item {{ $selectedBranch && $selectedBranch->branch_id == $branch->branch_id ? 'active' : '' }}">
                                    <i class="bi bi-building me-2"></i> {{ $branch->branch_code }} - {{ $branch->branch_name }}
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; font-size: 14px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
