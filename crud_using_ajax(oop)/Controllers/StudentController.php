<?php
namespace Controllers;

include_once __DIR__ . '/../Models/Student.php'; 
use Models\Student;

class StudentController {

    private $student;

    public function __construct() {
        $this->student = new Student();
    }

    public function index() {
        return $this->student->read();
    }

    public function addStudent($first_name, $last_name, $email, $contactNo, $address) {
        return $this->student->create($first_name, $last_name, $email, $contactNo, $address); 
    }

    public function editStudent($id, $first_name, $last_name, $email, $phone_no, $address) {
        return $this->student->update($id, $first_name, $last_name, $email, $phone_no, $address);
    }

    public function deleteStudent($id) {
        return $this->student->delete($id);
    }

    public function getStudent($id) {
        return $this->student->readById($id);
    }

    public function markAttendance($student_id, $attendance_date, $status) {
        return $this->student->markAttendance($student_id, $attendance_date, $status);
    }

    public function emailExists($email) {
        return $this->student->emailExists($email);
    }

    public function emailExistsForOther($email, $id) {
        return $this->student->emailExistsForOther($email, $id);
    }
    
    
}