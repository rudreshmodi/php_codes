<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      
      <?php            if (isset($_SESSION['user_name'])) {?>
        <div class="user-panel mt-3 mb-3 d-flex">
        <div class="image">
        <?php if (!empty($res['image_path']) && file_exists(__DIR__ . '/user/' . $res['image_path'])): ?>
          <img src="./user/<?= htmlspecialchars($res['image_path']) ?>" alt="Profile" style="width:25px; height:25px; border-radius:50%;">
        <?php else: ?>
          <img src="../assets/img/profile.png" alt="Profile" style="width:25px; height:auto; border-radius:50%;">
        <?php endif; ?>
        </div>
                <div class="info">
              <a href="#" class="d-block"><P>Hello, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></strong></p></a></div>
            </div>
              <?php }else{} ?>

      <!-- SidebarSearch Form -->
      <div class="form-inline mt-3">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <!-- Dashboard -->
    <li class="nav-item">
        <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                Dashboard
                <i class="right fas fa-angle-right"></i>
            </p>
        </a>
    </li>

    <!-- Student Administration -->
    <li class="nav-item <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'create.php']) && strpos($_SERVER['SCRIPT_NAME'], '/student/')) !== false ? 'menu-open' : '' ?>">
        <a href="#" class="nav-link <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'create.php', 'view.php']) && strpos($_SERVER['SCRIPT_NAME'], '/student/')) !== false ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
                Student Administration
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="../student/index.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['SCRIPT_NAME'], '/student/')) !== false ? 'active' : '' ?>">
                    <i class="fas fa-list-alt nav-icon"></i>
                    <p>Manage Students</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../student/create.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'create.php' && strpos($_SERVER['SCRIPT_NAME'], '/student/')) !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>Add New Student</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- User Administration -->
    <li class="nav-item <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'create.php']) && strpos($_SERVER['SCRIPT_NAME'], '/user/')) !== false ? 'menu-open' : '' ?>">
        <a href="#" class="nav-link <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'create.php', 'view.php']) && strpos($_SERVER['SCRIPT_NAME'], '/user/')) !== false ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
                User Administration
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="../user/index.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['SCRIPT_NAME'], '/user/')) !== false ? 'active' : '' ?>">
                    <i class="fas fa-list-alt nav-icon"></i>
                    <p>Manage Users</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../user/create.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'create.php' && strpos($_SERVER['SCRIPT_NAME'], '/user/')) !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>Add New User</p>
                </a>
            </li>
        </ul>
    </li>

</ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
    <div class="content-header">