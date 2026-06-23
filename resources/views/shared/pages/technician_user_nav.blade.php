<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="padding-top: 0px; padding-bottom: 0px;">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 0px;">
    <img src="{{ asset('public/images/pats-logo-cropped.PNG') }}"
         alt="CNPTS"
         class="brand-image"
         style="opacity: 1; margin-top: 5px;">
    <span class="brand-text font-weight-light" style="font-size: 25px;"><b>PATS - TS</b></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


        <li class="nav-header" style="color:#FFBF00; font-size: 18px;">TECHNICIAN MODULES</li>
        <li class="nav-item">
          <a href="{{ route('machine') }}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
            <p>
              Machine (EEDMS)
            </p>
          </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('prod_runcard_with_defect') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Runcard V2</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('defect_escalation_v2') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Defect Escalation V2</p>
            </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
