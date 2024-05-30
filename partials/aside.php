<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
      <img src="../../img/logo/logo.png" height="24">
      <span class="app-brand-text demo menu-text fw-bolder ms-2">Minvento</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
      <!-- Dashboard -->
      <li class="menu-item <?php echo ($current_page == 'kantor.php' || $current_page == 'distributor.php') ? 'active' : ''; ?>">
          <a href="<?php echo ($_SESSION['role'] === 'Inventaris Kantor') ? 'kantor.php' : 'distributor.php'; ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Dashboard</div>
          </a>
      </li>

      <!-- Components -->
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Components</span></li>
      
      <?php if ($_SESSION['role'] == 'Inventaris Kantor') { ?>
          <li class="menu-item <?php echo ($current_page == 'peminjaman.php' || $current_page == 'peminjaman_dtl.php') ? 'active' : ''; ?>">
              <a href="peminjaman.php" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-collection"></i>
                  <div data-i18n="Basic">Peminjaman</div>
              </a>
          </li>

          <li class="menu-item <?php echo ($current_page == 'maintenance.php') ? 'active' : ''; ?>">
              <a href="maintenance.php" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-collection"></i>
                  <div data-i18n="Basic">Maintenance</div>
              </a>
          </li>
      <?php } ?>
      
      <li class="menu-item <?php echo ($current_page == 'order_k.php' || $current_page == 'order_d.php') ? 'active' : ''; ?>">
          <a href="<?php echo ($_SESSION['role'] === 'Inventaris Kantor') ? 'order_k.php' : 'order_d.php'; ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Order</div>
          </a>
      </li>

      <li class="menu-item <?php echo ($current_page == 'riwayat_k.php' || $current_page == 'riwayat_d.php') ? 'active' : ''; ?>">
          <a href="<?php echo ($_SESSION['role'] === 'Inventaris Kantor') ? 'riwayat_k.php' : 'riwayat_d.php'; ?>" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Log Activity</div>
          </a>
      </li>
  </ul>
</aside>