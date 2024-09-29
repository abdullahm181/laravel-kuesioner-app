<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url("home") }}">
        {{-- <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div> --}}
        <div class="sidebar-brand-text mx-3">
            {{-- SB Admin <sup>2</sup> --}}
            Kuesioners <sup>v {{env('APP_VERSION', '0.0')}}</sup>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <?php
    echo html_entity_decode(Session::get('sidebarSession'));
    ?>
    
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
</ul>
<!-- End of Sidebar -->
