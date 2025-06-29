<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/StudentController.php';
use Controllers\StudentController;

$controller = new StudentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    header('Content-Type: application/json');

    $id = $_POST['id'] ?? '';
    if (empty($id) || !is_numeric($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid student ID.']);
        exit;
    }
    $id = $_POST['id'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_no = $_POST['phone_no'] ?? '';
    $address = $_POST['address'] ?? '';

    $result = $controller->editStudent($id, $first_name, $last_name, $email, $phone_no, $address);

    if (is_array($result) && !$result['success']) {
        echo json_encode(['status' => 'error', 'message' => $result['message']]);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Student updated successfully.']);
    }
    exit;
}

$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

$student = $controller->getStudent($id);
if (!$student) {
    header('Location: index.php');
    exit;
}


include_once("../header.php");
include_once("../sidebar.php");
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Edit Student</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Edit Student</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Edit Student</h3></div>
            <form id="userForm" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $student['id'] ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="<?= htmlspecialchars($student['first_name'] ?? '') ?>"
                                   placeholder="Enter first name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?= htmlspecialchars($student['last_name'] ?? '') ?>"
                                   placeholder="Enter last name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($student['email'] ?? '') ?>"
                                   placeholder="Enter email" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone_no">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no"
                                   value="<?= htmlspecialchars($student['phone_no'] ?? '') ?>"
                                   placeholder="Enter 10-digit number" autocomplete="off" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="4"
                                  placeholder="Enter your address"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning float-right">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</section>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
    $('#userForm').submit(function (e) {
      e.preventDefault();

      var form = this;

      if (!$(form).valid()) return;

      var formData = new FormData(form);
      var submitBtn = $(form).find('button[type="submit"]');

      submitBtn.prop('disabled', true).text('Updating...');

      $.ajax({
        url: 'edit.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
          if (res.status === 'success') {
            toastr.success(res.message);
            setTimeout(function () {
              window.location.href = 'index.php';
            }, 1500);
          } else {
            toastr.error(res.message);
          }
        },
        error: function () {
          toastr.error('Something went wrong.');
        },
        complete: function () {
          submitBtn.prop('disabled', false).text('Update Student');
        }
      });
    });
  });
</script>


<?php include_once("../footer.php"); ?>
