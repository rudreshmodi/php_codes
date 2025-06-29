<?php
namespace Models;

include_once "../../Config/Database.php";

use Config\Database;

class Student {
    private $connection;

    public function __construct() {
        $this->connection = new Database();
        $this->connection = $this->connection->connect();
    }

    public function create($first_name, $last_name, $email, $phone_no, $address) {
        $sql = "INSERT INTO student (first_name, last_name, email, phone_no, address) 
                VALUES (:first_name, :last_name, :email, :phone_no, :address)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_no', $phone_no);
        $stmt->bindParam(':address', $address);
        return $stmt->execute();
    }

    public function update($id, $first_name, $last_name, $email, $phone_no, $address) {
        $sql = "UPDATE student SET first_name = :first_name, last_name = :last_name, email = :email, phone_no = :phone_no, address = :address WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_no', $phone_no);
        $stmt->bindParam(':address', $address);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM student WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function read() {
        $sql = "SELECT * FROM student";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $sql = "SELECT * FROM student WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM student WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
   
    public function emailExistsForOther($email, $id) {
        $sql = "SELECT COUNT(*) FROM student WHERE email = :email AND id != :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    // public function markAttendance($student_id, $attendance_date, $status) {
    //     $sql = "INSERT INTO attendance (student_id, attendance_date, status)
    //             VALUES (:student_id, :attendance_date, :status)";
    //     $stmt = $this->connection->prepare($sql);
    //     $stmt->bindParam(':student_id', $student_id, \PDO::PARAM_INT);
    //     $stmt->bindParam(':attendance_date', $attendance_date);
    //     $stmt->bindParam(':status', $status);
    //     return $stmt->execute();
    // }
}