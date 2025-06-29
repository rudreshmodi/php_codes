<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/StudentController.php';
use Controllers\StudentController;

$controller = new StudentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    $deleteId = $_POST['delete_id'];
    $deleted = $controller->deleteStudent($deleteId);

    if ($deleted) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
    }
    exit();
}

$students = $controller->index();

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
                <li class="breadcrumb-item active">Student list</li>
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
                    <a href="create.php" class="btn btn-primary px-4 col-md-3 m-auto">Add New Student</a>
                </div>
            </div>
            <div class="card-body">
                <table id="studentTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-justify">
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr id="student-row-<?= $student['id'] ?>">
                                    <td><?= htmlspecialchars($student['id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['first_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['last_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['phone_no'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['address'] ?? '') ?></td>
                                    <td>
                                        <a href="view.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                        <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <button class="btn btn-sm btn-danger ajaxDeleteBtn" data-id="<?= $student['id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</section>
</div>
</div>

<?php include_once("../footer.php"); ?>
<script>
    $(document).ready(function () {
        $(document).on('click', '.ajaxDeleteBtn', function () {
            let studentId = $(this).data('id');
            if (!confirm('Are you sure you want to delete this user?')) return;

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: { delete_id: studentId },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                        $('#student-row-' + studentId).fadeOut(800, function () {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function () {
                    toastr.error('An error occurred');
                }
            });
        });
    });
</script>