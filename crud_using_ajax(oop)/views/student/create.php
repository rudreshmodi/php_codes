<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/StudentController.php';
use Controllers\StudentController;

$controller = new StudentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    header('Content-Type: application/json');

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_no = trim($_POST['phone_no'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($controller->emailExists($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
        exit;
    }

    $phone_no = substr(trim($phone_no), 0, 10);

    $result = $controller->addStudent($first_name,$last_name,$email,$phone_no,$address);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Student added successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Student.']);
    }

    exit;
}


include_once("../header.php");
include_once("../sidebar.php");
?>

        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add New Student</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Add Student</li>
                    </ol>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                    <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title">Add Student</h3></div>

                    <form id="userForm">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?= htmlspecialchars($_SESSION['formData']['first_name'] ?? '') ?>" placeholder="Enter first name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?= htmlspecialchars($_SESSION['formData']['last_name'] ?? '') ?>" placeholder="Enter last name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($_SESSION['formData']['email'] ?? '') ?>" placeholder="Enter email" autocomplete="off">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone_no">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no"
                                        value="<?= htmlspecialchars($_SESSION['formData']['phone_no'] ?? '') ?>"
                                        placeholder="Enter 10-digit number" autocomplete="off" maxlength="10">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="4"
                                    placeholder="Enter your address"><?= htmlspecialchars($_SESSION['formData']['address'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary float-right">Create</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function () {
    $('#userForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        if (!$(form).valid()) return;
        var formData = new FormData(form);
        var submitBtn = $(form).find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('Submitting...');
        $.ajax({
            url: 'create.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status === 'success') {
                    toastr.success(res.message);
                    setTimeout(() => location.href = 'index.php', 1500);
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Create');
            }
        });
    });
});
</script>

<?php include_once("../footer.php"); ?>
