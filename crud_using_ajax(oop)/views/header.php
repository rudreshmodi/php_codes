<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Panel</title>
  <link rel="shortcut icon" href="../../dist/img/AdminLTELogo.png" type="image/x-icon" />

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />

  <!-- Bootstrap 4.6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />

  <!-- AdminLTE Core & Plugins -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css" />

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

  <!-- Plugin Styles -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css" />
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css" />
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css" />
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css" />

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />

  <!-- Custom Style -->
  <style>
    * {
      box-sizing: border-box;
      font-size: 14px;
    }
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background-color: #f4f6f9;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Fullscreen -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

      <!-- Profile Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <img src="../../uploads/profile.png" alt="Profile" style="width:25px; height:auto; border-radius:50%;">
          <i class="fas fa-angle-down ml-1"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 220px;">
          <span class="dropdown-item-text text-sm text-muted">Hello, Student</span>
          <div class="dropdown-divider"></div>
          <a href="change_password.php" class="dropdown-item">
            <i class="fas fa-key mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  
  <!-- Toastr Notification -->
  <?php if (!empty($message)): ?>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/toastr/toastr.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        toastr.options = {
          closeButton: true,
          progressBar: true,
          positionClass: "toast-top-right",
          timeOut: 2000,
        };
        toastr["<?= $status === 'success' ? 'success' : 'error' ?>"]("<?= addslashes($message) ?>", "<?= ucfirst($status) ?>");
      });
    </script>
  <?php endif; unset($_SESSION['errors'], $_SESSION['status'], $_SESSION['message']); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Sidebar content -->
  </aside>
