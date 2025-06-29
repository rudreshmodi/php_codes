<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Controllers/StudentController.php';
use Controllers\StudentController;

$controller = new StudentController();
$student = null;
$successMsg = $errorMsg = "";


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $viewID = $_GET['id'];
    $student = $controller->getStudent($viewID);
    if (!$student) {
        $_SESSION['error'] = "Student not found.";
        header("Location: index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'] ?? null;
    $date = $_POST['attendance_date'] ?? null;
    $status = $_POST['attendance_status'] ?? null;

    if ($studentId && $date && $status) {
        $success = $controller->markAttendance($studentId, $date, $status);
        if ($success) {
            $_SESSION['success'] = "Attendance recorded for student ID $studentId as '$status' on $date.";
            header("Location: index.php"); 
        } else {
            $_SESSION['error'] = "Failed to record attendance. Please try again.";
            header("attendance.php");
        }
        
        $student = $controller->getStudent($studentId);
    } else {
        $errorMsg = " Missing required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .info { color: red; font-size: 0.9rem; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Mark Attendance</h2>

    <form name="frmAdd" method="post" action="" id="frmAdd" onSubmit="return validate();">
        <div class="mb-3">
            <label for="attendance_date" class="form-label">Attendance Date</label>
            <input type="date" name="attendance_date" id="attendance_date" class="form-control">
            <span id="attendance_date-info" class="info"></span>
        </div>

        <div class="mb-4">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Student</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['id']) ?>">
                            <?= htmlspecialchars($student['firstname']) . ' ' . htmlspecialchars($student['lastname']) ?>
                        </td>
                        <td><input type="radio" name="attendance_status" value="present" checked class="form-check-input"></td>
                        <td><input type="radio" name="attendance_status" value="absent" class="form-check-input"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Add Attendance</button>
        </div>
    </form>
</div>

<!-- jQuery & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function validate() {
    var valid = true;
    $("#attendance_date").removeClass('is-invalid');
    $("#attendance_date-info").html('');

    if (!$("#attendance_date").val()) {
        $("#attendance_date-info").html("Date is required.");
        $("#attendance_date").addClass('is-invalid');
        valid = false;
    }
    return valid;
}
</script>

</body>
</html>
