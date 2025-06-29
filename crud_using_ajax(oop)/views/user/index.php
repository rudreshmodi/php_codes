<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/UserController.php';
use Controllers\UserController;

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    $deleted = $controller->deleteUser($_POST['delete_id']);
    echo json_encode([
        'status' => $deleted ? 'success' : 'error',
        'message' => $deleted ? 'User deleted successfully' : 'Failed to delete user'
    ]);
    exit();
}

$users = $controller->index();

include_once("../header.php");
include_once("../sidebar.php");
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0"></h1></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">User list</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <h2 class="col-md-9 m-auto pl-4"></h2>
                    <a href="create.php" class="btn btn-primary px-4 col-md-3 m-auto">Add New User</a>
                </div>
            </div>
            <div class="card-body">
                <table id="userTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Image</th>
                            <th>Gender</th><th>Contact No</th><th>Hobby</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-justify">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $User): ?>
                                <tr id="user-row-<?= $User['id'] ?>">
                                    <td><?= htmlspecialchars($User['id']) ?></td>
                                    <td><?= htmlspecialchars($User['first_name']) ?></td>
                                    <td><?= htmlspecialchars($User['last_name'] ?? 'NA') ?></td>
                                    <td><?= htmlspecialchars($User['email']) ?></td>
                                    <td style="text-align: center;">
                                        <img src="<?= (!empty($User['image_path']) && file_exists('../../' . $User['image_path'])) ? '../../' . htmlspecialchars($User['image_path']) : '../../uploads/default.png' ?>" class="img-thumbnail mt-1 shadow-lg" style="width: 60px;">
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        echo match ($User['gender']) {
                                            'Male' => "<span class='badge badge-primary'>Male</span>",
                                            'Female' => "<span class='badge' style='background-color:pink;'>Female</span>",
                                            'Other' => "<span class='badge badge-secondary'>Other</span>",
                                            default => "<span>N/A</span>",
                                        };
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($User['phone_no']) ?></td>
                                    <td>
                                        <?php
                                        if (!empty($User['hobby'])) {
                                            foreach (explode(',', $User['hobby']) as $hobby) {
                                                echo "<span class='badge badge-info w-100'>" . htmlspecialchars(trim($hobby)) . "</span><br>";
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-info viewUserBtn" data-id="<?= $User['id'] ?>"><i class="fa fa-eye"></i></a>
                                        <a href="edit.php?id=<?= $User['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <button class="btn btn-sm btn-danger ajaxDeleteBtn" data-id="<?= $User['id'] ?>"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="10" class="text-center">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="userModalLabel"><i class="fas fa-user mr-2"></i> User Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span style="font-size:22px;">&times;</span></button>
                </div>
                <div class="modal-body p-3" id="userDetailContent">
                    <div class="text-center text-muted">Loading...</div>
                </div>
                <div class="modal-footer justify-content-between bg-light">
                    <small class="text-muted">Last Updated: <?= !empty($User['updated_at']) ? date('d M Y, h:i A', strtotime($User['updated_at'])) : 'N/A' ?></small>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
</div>
<?php include_once("../footer.php"); ?>

<script>
$(document).on('click', '.viewUserBtn', function () {
    const userId = $(this).data('id');
    $('#userDetailContent').html('<div class="text-center text-muted py-5">Loading...</div>');
    $('#viewUserModal').modal('show');
    $.ajax({
        url: 'view.php',
        type: 'GET',
        data: { id: userId },
        success: function (html) {
            $('#userDetailContent').html(html);
        },
        error: function () {
            $('#userDetailContent').html('<div class="alert alert-danger">Failed to load user details.</div>');
        }
    });
});

$(document).on('click', '.ajaxDeleteBtn', function () {
    let userId = $(this).data('id');
    if (!confirm('Are you sure you want to delete this user?')) return;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: { delete_id: userId },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            if (res.status === 'success') {
                toastr.success(res.message);
                $('#user-row-' + userId).fadeOut(800, function () { $(this).remove(); });
            } else {
                toastr.error(res.message);
            }
        },
        error: function () {
            toastr.error('An error occurred');
        }
    });
});
</script>
