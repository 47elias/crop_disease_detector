<aside class="app-sidebar shadow" data-bs-theme="dark">
    <div class="sidebar-brand text-decoration-none">
        <span class="brand-mark" aria-hidden="true">M</span><span class="brand-text fw-light">Maize Health</span>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Main navigation">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">⌂</span><p>Overview</p></a></li>
                <li class="nav-header">MAIZE DATA</li>
                <li class="nav-item"><a href="{{ route('diseases.index') }}" class="nav-link {{ request()->routeIs('diseases.*') ? 'active' : '' }}"><span class="nav-icon">✿</span><p>Disease library</p></a></li>
                <li class="nav-item"><a href="{{ route('diagnoses.index') }}" class="nav-link {{ request()->routeIs('diagnoses.*') ? 'active' : '' }}"><span class="nav-icon">⌕</span><p>Diagnoses</p></a></li>
                <li class="nav-item"><a href="{{ route('farmers.index') }}" class="nav-link {{ request()->routeIs('farmers.*') ? 'active' : '' }}"><span class="nav-icon">♙</span><p>Farmers</p></a></li>
                <li class="nav-item"><a href="{{ route('devices.index') }}" class="nav-link {{ request()->routeIs('devices.*') ? 'active' : '' }}"><span class="nav-icon">▣</span><p>Devices</p></a></li>
                <li class="nav-item"><a href="{{ route('treatments.index') }}" class="nav-link {{ request()->routeIs('treatments.*') ? 'active' : '' }}"><span class="nav-icon">＋</span><p>Treatment guidance</p></a></li>
                <li class="nav-header">FIELD OPERATIONS</li>
                <li class="nav-item"><a href="{{ route('reviews.index') }}" class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}"><span class="nav-icon">✓</span><p>Expert reviews</p></a></li>
                <li class="nav-item"><a href="{{ route('models.index') }}" class="nav-link {{ request()->routeIs('models.*') ? 'active' : '' }}"><span class="nav-icon">◇</span><p>Model versions</p></a></li>
                <li class="nav-item"><a href="{{ route('sms.index') }}" class="nav-link {{ request()->routeIs('sms.*') ? 'active' : '' }}"><span class="nav-icon">▤</span><p>SMS dispatches</p></a></li>
                <li class="nav-item"><a href="{{ route('field-trials.index') }}" class="nav-link {{ request()->routeIs('field-trials.*') ? 'active' : '' }}"><span class="nav-icon">⌖</span><p>Field trials</p></a></li>
                <li class="nav-item"><a href="{{ route('field-feedback.index') }}" class="nav-link {{ request()->routeIs('field-feedback.*') ? 'active' : '' }}"><span class="nav-icon">☷</span><p>Field feedback</p></a></li>
            </ul>
        </nav>
    </div>
</aside>
