<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/StudentController.php';
use Controllers\StudentController;

$controller = new StudentController();

$student = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $viewID = $_GET['id'];
    $student = $controller->getStudent($viewID);

    if (!$student) {
        $_SESSION['error'] = "Student not found.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: index.php");
    exit();
}

include_once("../header.php");
include_once("../sidebar.php");
?>


    <div class="container-fluid mb-2">
      <div class="row">
        <div class="col-sm-6">
          <h1>Student Details</h1>
        </div>
        <div class="col-sm-6 text-sm-right">
          <a href="index.php" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Profile Info</h3>
        </div>

        <div class="card-body">
          <div class="row">
            <!-- Optional Student Image -->
            <!--
            <div class="col-md-3 text-center m-auto">
              <img src="./<?= (!empty($student['image_path']) && file_exists($student['image_path']) ? $student['image_path'] : '../../assets/img/profile.png') ?>" 
                   alt="Profile Image" 
                   class="img-thumbnail mt-1 shadow-lg" 
                   style="height: 80px; width: auto;">
            </div>
            -->

            <div class="col-md-12">
              <table class="table table-sm">
                <tbody>
                  <tr>
                    <th>First Name</th>
                    <td>
                      <?= (!empty($student['first_name'])) 
                          ? htmlspecialchars(trim($student['first_name'])) 
                          : 'N/A' ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Last Name</th>
                    <td>
                      <?= (!empty($student['last_name'])) 
                          ? htmlspecialchars(trim($student['last_name'])) 
                          : 'N/A' ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td><?= !empty($student['email']) ? htmlspecialchars($student['email']) : 'N/A' ?></td>
                  </tr>
                  <tr>
                    <th>Phone</th>
                    <td><?= !empty($student['phone_no']) ? htmlspecialchars($student['phone_no']) : 'N/A' ?></td>
                  </tr>
                  <tr>
                    <th>Address</th>
                    <td><?= !empty($student['address']) ? nl2br(htmlspecialchars($student['address'])) : 'N/A' ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Card Footer -->
        <div class="card-footer text-left">
          <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit mr-1"></i> Edit
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<?php include_once("../footer.php"); ?>
