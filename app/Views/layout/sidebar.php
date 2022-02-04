  <?php
  $uri = service('uri');
  $segments = $uri->getSegments();
  if (empty($segments)) {
    $segments = array('', '');
  } elseif (count($segments) == 1) {
    $segments[1] = '';
  }
  ?>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url() . "/" ?>" class="brand-link mt-2">
      <img src="<?php echo base_url() ?>/dist/img/cd.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span style="font-size: 1.3rem;" class="px-2">VPF</span>
    </a>
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo base_url() ?>/dist/img/hen.jfif" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block text-white">welcome <?= session()->get('name') ?></a>
      </div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <h3 class="text-white pl-3 mb-3 font-weight-bold" style="font-size: 0.9rem;">Admin Menu</h3>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>/mainEntry" class="nav-link <?php echo ($segments[0] == 'mainEntry') ? 'active' : ''; ?> ">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Main Entry
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>/dailyEntry" class="nav-link <?php echo ($segments[0] == 'dailyEntry') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>
                Daily Entry
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>/transfer" class="nav-link <?php echo ($segments[0] == 'transfer') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-exchange"></i>
              <p>
                Transfer
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>/stock" class="nav-link <?php echo ($segments[0] == 'stock') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-inventory"></i>
              <p>
                Stock
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url() ?>/farm-report" class="nav-link <?php echo ($segments[0] == 'farm-report') ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-file"></i>
              <p>
                Farm Report
              </p>
            </a>
          </li>
          <li class="nav-item <?php echo ($segments[0] == 'settings') ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo ($segments[0] == 'settings') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/group" class="nav-link  <?php echo ($segments[1] == 'group') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Group</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/shed" class="nav-link  <?php echo ($segments[1] == 'shed') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Shed </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/poultryType" class="nav-link  <?php echo ($segments[1] == 'poultryType') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Poultry Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/feedType" class="nav-link  <?php echo ($segments[1] == 'feedType') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Feed Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/breed" class="nav-link  <?php echo ($segments[1] == 'breed') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Breed</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/unit" class="nav-link  <?php echo ($segments[1] == 'unit') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Unit</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/remarksType" class="nav-link  <?php echo ($segments[1] == 'remarksType') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Remarks Type</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/medicineVaccine" class="nav-link  <?php echo ($segments[1] == 'medicineVaccine') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicine/Vaccine</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/standardBreederPerformances" class="nav-link  <?php echo ($segments[1] == 'standardBreederPerformances') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Std. Breeder Performance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/standardBreederInformation" class="nav-link  <?php echo ($segments[1] == 'standardBreederInformation') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Std. Breeder Information</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/settings/standardHatcheryInformation" class="nav-link  <?php echo ($segments[1] == 'standardHatcheryInformation') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Std. Hatchery Information</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item <?php echo ($segments[0] == 'excel') ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo ($segments[0] == 'excel') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Excel
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url() ?>/excel/daily" class="nav-link  <?php echo ($segments[1] == 'daily') ? 'active' : ''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daily Entry</p>
                </a>
              </li>

            </ul>
          </li>
          <?php
          if (session()->get('role')) {
          ?> <li class="nav-item">
              <a href="<?php echo base_url() ?>/userlog" class="nav-link <?php echo ($segments[0] == 'dailyEntry') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>
                  User log
                </p>
              </a>
            </li>
          <?php  }
          ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>