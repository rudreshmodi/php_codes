<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/UserController.php';
use Controllers\UserController;

$controller = new UserController();

// Handle AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    header('Content-Type: application/json');

    // Collect and sanitize form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone_no   = substr(trim($_POST['phone_no'] ?? ''), 0, 10);
    $password   = trim($_POST['password'] ?? '');
    $gender     = trim($_POST['gender'] ?? '');
    $DOB        = trim($_POST['DOB'] ?? '');
    $hobby      = $_POST['hobby'] ?? [];
    $address    = trim($_POST['address'] ?? '');
    $country    = trim($_POST['country'] ?? '');
    $image_path = '';

    // Check for duplicate email
    if ($controller->checkEmailExists($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
        exit;
    }

    // Image upload
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileTmp  = $_FILES['image_path']['tmp_name'];
        $fileExt  = strtolower(pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('usr_') . '.' . $fileExt;
        $uploadPath = $uploadDir . $fileName;

        if (!move_uploaded_file($fileTmp, $uploadPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
            exit;
        }

        $image_path = 'uploads/' . $fileName;
    }

    // Call controller to add user
    $result = $controller->adduser(
        $first_name, $last_name, $email, $phone_no, $address,
        $password, $gender, $DOB, $hobby, $country, $image_path
    );

    echo json_encode([
        'status' => $result ? 'success' : 'error',
        'message' => $result ? 'User added successfully!' : 'Failed to add user.'
    ]);
    exit;
}

// Load frontend
include_once("../header.php");
include_once("../sidebar.php");
?>


<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Add New User</h1></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Add User</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Add User</h3></div>
            <form id="userForm" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" placeholder="Enter first name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" placeholder="Enter last name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone_no">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone_no" maxlength="10" placeholder="Enter phone number">
                        </div>
                    </div>
                    
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control password" id="password" name="password" placeholder="Enter password" autocomplete="new-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                    <span class="fa fa-eye toggle" style="cursor: pointer;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                   placeholder="Re-enter password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="image_path">Profile Image <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image_path" name="image_path"
                                       accept="image/*">
                                <label class="custom-file-label" for="image_path">Choose file</label>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="DOB">Date of Birth <span class="text-danger">*</span></label>
                            <div class="input-group date" id="dobPicker" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#dobPicker" name="DOB" placeholder="YYYY-MM-DD" autocomplete="off" />
                                <div class="input-group-append" data-target="#dobPicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Gender <span class="text-danger">*</span></label><br>
                            <div class="bg-light form-control rounded shadow-sm" style="height:max-content;">
                                <div class="form-check form-check-inline" style="font-weight:normal; margin:2px;">
                                    <input class="form-control" type="radio" name="gender" value="Male">
                                    <label class="form-check-label">&nbsp;Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-control" type="radio" name="gender" value="Female">
                                    <label class="form-check-label">&nbsp;Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-control" type="radio" name="gender" value="Other">
                                    <label class="form-check-label">&nbsp;Other</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hobbies <span class="text-danger">*</span></label><br>
                            <div class="bg-light form-control rounded shadow-sm" style="height:max-content;">
                                <?php
                                $hobbies = ["Reading","Singing","Yoga","Dancing","Swimming","Writing","Drawing","Painting","Blogging","Traveling","Cricket","Photography","Cooking","Coding","Gaming","Cycling","Skiing"]; 
                                    foreach ($hobbies as $hobby) {
                                    echo "<div class='form-check form-check-inline'>
                                            <input type='checkbox' name='hobby[]' value='$hobby'>
                                            <label style='font-weight:normal; margin:2px;'>&nbsp;$hobby</label>
                                          </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" rows="2" placeholder="Enter your address here...."></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country">Country <span class="text-danger">*</span></label>
                            <select class="form-control" name="country">
                                <option value="">Select</option>
                                <option value="india">India</option>
                                <option value="UK">UK</option>
                                <option value="usa">USA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary float-right">Add User</button>
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
    $(document).ready(function () {
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
                    submitBtn.prop('disabled', false).text('Add User');
                }
            });
        });
    });
</script>

<?php include_once("../footer.php"); ?>
