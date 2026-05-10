<aside class="admin-sidebar d-none d-lg-block">
    <div class="glass-panel p-3 p-lg-4 shadow-sm border-0 bg-white rounded-4">
        <div class="small text-muted fw-bold mb-3 px-2">MENUS PRINCIPAUX</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="sidebar-link {{ Request::routeIs('admin.users') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
        </a>
        <a href="{{ route('admin.trips') }}" class="sidebar-link {{ Request::routeIs('admin.trips') ? 'active' : '' }}">
            <i class="bi bi-map"></i> Courses
        </a>
        <a href="{{ route('admin.rentals') }}" class="sidebar-link {{ Request::routeIs('admin.rentals') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> Locations
        </a>
    </div>
</aside>
