<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/UserController.php';
use Controllers\UserController;

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    header('Content-Type: application/json');

    $id = $_POST['id'] ?? '';
    if (!is_numeric($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user ID.']);
        exit;
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone_no   = substr(trim($_POST['phone_no'] ?? ''), 0, 10);
    $DOB        = trim($_POST['DOB'] ?? '');
    $gender     = trim($_POST['gender'] ?? '');
    $hobby      = isset($_POST['hobby']) ? implode(',', $_POST['hobby']) : '';
    $address    = trim($_POST['address'] ?? '');
    $country    = trim($_POST['country'] ?? '');
    $existing_image = $_POST['existing_image'] ?? '';
    $image_path = $existing_image;

    if (!empty($_FILES['image_path']['name']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExt = strtolower(pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowedExts)) {
            if ($_FILES['image_path']['size'] <= 2 * 1024 * 1024) {
                $uploadDir = __DIR__ . '/../../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = uniqid('usr_') . '.' . $fileExt;
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image_path']['tmp_name'], $uploadPath)) {
                    $image_path = 'uploads/' . $fileName;

                    $oldPath = __DIR__ . '/../../' . $existing_image;
                    if ($existing_image && file_exists($oldPath) && !str_contains($existing_image, 'profile.png')) {
                        unlink($oldPath);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Image upload failed.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Image must be under 2MB.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image type.']);
            exit;
        }
    }

    $result = $controller->edituser(
        $id, $first_name, $last_name, $email, $phone_no, $address, $DOB,
        $gender, $hobby, $country, $image_path
    );

    if (is_array($result) && !$result['success']) {
        echo json_encode(['status' => 'error', 'message' => $result['message']]);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);
    }
    exit;
}

$id = $_GET['id'] ?? '';
if (!is_numeric($id)) {
    header('Location: index.php');
    exit;
}

$user = $controller->getuser($id);
if (!$user) {
    header('Location: index.php');
    exit;
}

include_once("../header.php");
include_once("../sidebar.php");

$allHobbies = ["Reading","Singing","Yoga","Dancing","Swimming","Writing","Drawing","Painting","Blogging","Traveling","Cricket","Photography","Cooking","Coding","Gaming","Cycling","Skiing"]; 
$selectedHobbies = array_map('trim', explode(',', $user['hobby']));
$selectedHobbiesLower = array_map('strtolower', $selectedHobbies);
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Edit User</h1></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header"><h3 class="card-title">Edit User</h3></div>
            <form id="userForm" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="existing_image" value="<?= $user['image_path'] ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone_no" value="<?= htmlspecialchars($user['phone_no']) ?>" maxlength="10">
                        </div>
                    </div>
                    <div class="row">
                        <!-- Current Image -->
                        <div class="form-group col-md-2">
                            <label class="form-text text-muted d-block"><b>Current Image:</b></label>
                            <img 
                            src="<?= !empty($user['image_path']) && file_exists(__DIR__ . '/../../' . $user['image_path']) 
                                    ? '../../' . htmlspecialchars($user['image_path']) 
                                    : '../../uploads/default.png' ?>" 
                            alt="Profile" 
                            class="img-thumbnail mt-1 shadow" 
                            style="height: 80px; width: auto;">
                        </div>

                        <!-- Upload New Image -->
                        <div class="form-group col-md-4">
                            <label>Profile Image <span class="text-danger">*</span></label>
                            <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image_path" id="imageInput" accept="image/*">
                            <label class="custom-file-label" for="imageInput">Choose file</label>
                            </div>
                        </div>

                        <!-- Preview + File Name -->
                        <div class="form-group col-md-2">
                        <label class="text-muted d-block">Preview :</label>
                        <div class="d-flex flex-column align-items-center">
                                <img id="imagePreview" 
                                    src="../../uploads/default.png" 
                                    alt="Preview" 
                                    class="img-thumbnail shadow mb-1" 
                                    style="max-width: 100px; max-height: 80px;">
                                <span id="imageName" class="text-muted small" style="display: none;"></span>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="DOB">Date of Birth <span class="text-danger">*</span></label>
                            <div class="input-group date" id="dobPicker" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#dobPicker" value="<?= htmlspecialchars($user['DOB']) ?>" name="DOB" placeholder="YYYY-MM-DD" autocomplete="off" />
                                <div class="input-group-append" data-target="#dobPicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>                       
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Gender <span class="text-danger">*</span></label>
                            <div class="form-control bg-light" style="height:max-content;">
                                <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-control" type="radio" name="gender" value="<?= $g ?>" <?= $user['gender'] === $g ? 'checked' : '' ?>>
                                        <label class="form-check-label">&nbsp;<?= ucfirst($g) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hobbies <span class="text-danger">*</span></label>
                            <div class="form-control bg-light" style="height:max-content;">
                                <?php foreach ($allHobbies as $h): ?>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="hobby[]" value="<?= $h ?>" <?= in_array(strtolower($h), $selectedHobbiesLower) ? 'checked' : '' ?>>
                                        <label class="form-check-label">&nbsp; <?= $h ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address"><?= htmlspecialchars($user['address']) ?></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Country <span class="text-danger">*</span></label>
                            <select class="form-control" name="country">
                                <option value="">Select</option>
                                <option value="india" <?= $user['country'] === 'india' ? 'selected' : '' ?>>India</option>
                                <option value="UK" <?= $user['country'] === 'UK' ? 'selected' : '' ?>>UK</option>
                                <option value="usa" <?= $user['country'] === 'usa' ? 'selected' : '' ?>>USA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning float-right">Update User</button>
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
    $('#userForm').submit(function (e) {
      e.preventDefault();
      var form = this;

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
            setTimeout(() => window.location.href = 'index.php', 1500);
          } else {
            toastr.error(res.message);
          }
        },
        error: function () {
          toastr.error('Something went wrong.');
        },
        complete: function () {
          submitBtn.prop('disabled', false).text('Update User');
        }
      });
    });
  });
</script>
<script>
  $(document).ready(function () {
    // Initialize custom file input (AdminLTE support)
    if (typeof bsCustomFileInput !== 'undefined') {
      bsCustomFileInput.init();
    }

    $('#imageInput').on('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
          $('#imagePreview')
            .attr('src', e.target.result)
            .show();

          $('#imageName')
            .text(file.name)
            .show();
        };

        reader.readAsDataURL(file);

        // Update custom label manually (for extra safety)
        $(this).next('.custom-file-label').text(file.name);
      }
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>


<?php include_once("../footer.php"); ?>
