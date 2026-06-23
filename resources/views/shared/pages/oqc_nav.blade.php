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
        <li class="nav-header" style="color:#FFBF00; font-size: 18px;">PRODUCTION MODULES</li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
              Pilot Run
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('pilotrun1') }}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
                <p>Pilot Run - A</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('pilotrun2') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pilot Run - B</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('pilotrun3') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pilot Run - C</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('pilotrun4') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pilot Run - D</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('pilotrun5') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pilot Run - E</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- <li class="nav-item">
          <a href="{{ route('scrapverificationruncard') }}" class="nav-link">
            <i class="fas fa-clipboard-check"></i>
            <p>
              Scrap Verification Runcard
            </p>
          </a>
        </li> -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
              Production
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            {{-- <li class="nav-item">
              <a href="{{ route('prod_runcard_new') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Runcard</p>
              </a>
            </li> --}}
            <li class="nav-item">
              <a href="{{ route('prod_runcard_with_defect') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Runcard V2</p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{ route('defect_escalation') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Defect Escalation</p>
              </a>
            </li> --}}
            {{-- <li class="nav-item">
              <a href="{{ route('defect_escalation_v2') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Defect Escalation V2</p>
              </a>
            </li> --}}
            <li class="nav-item">
              <a href="{{ route('defect_escalation_v2_for_testing') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Defect Escalation V3</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('visual_inspection_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Final Visual Inspection</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('oqclotapp_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>OQC Lot Application</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('overall_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Overall Inspection</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header" style="color:#FFBF00; font-size: 18px;">QC</li>
        <li class="nav-item">
          <a href="{{ route('oqc_insp_ppts') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
              OQC Inspection
            </p>
          </a>
        </li>

        <li class="nav-header" style="color:#FFBF00; font-size: 18px;">PACKING MODULES</li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
              Packing
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('pack_conf_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Packing Confirmation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('prel_pack_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Preliminary Packing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('supv_val_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Supervisor Validation</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
              Final Packing
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('final_opt_qc_insp_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Operator/QC Inspection</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('final_opt_qc_web_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Operator Inspection (WEB EDI)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('final_qc_web_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>QC Confirmation (WEB EDI)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('final_traffic_qc_insp_ppts') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Traffic/QC Inspection</p>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a href="{{ route('defect_escalation') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Shipment Summary</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('defect_escalation') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Final Packing QR Code Printing</p>
              </a>
            </li> -->
          </ul>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
