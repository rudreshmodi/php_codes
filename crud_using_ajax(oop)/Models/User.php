<?php
namespace Models;

include_once "../../Config/Database.php";

use Config\Database;
use PDO;

class User {
    private $connection;

    public function __construct() {
        $db = new Database();
        $this->connection = $db->connect();
    }

    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM `User` WHERE email = :email";
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        if ($excludeId !== null) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create($first_name, $last_name, $email, $image_path, $phone_no, $address, $password, $DOB, $gender, $hobby, $country) {
        if ($this->emailExists($email)) {
            return false;
        }

        if (is_array($hobby)) {
            $hobby = implode(', ', $hobby);
        }

        $formatted_dob = date('Y-m-d', strtotime($DOB));
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `User` (first_name, last_name, email, image_path, phone_no, address, password, DOB, gender, hobby, country) 
                VALUES (:first_name, :last_name, :email, :image_path, :phone_no, :address, :password, :DOB, :gender, :hobby, :country)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':phone_no', $phone_no);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':DOB', $formatted_dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':hobby', $hobby);
        $stmt->bindParam(':country', $country);

        return $stmt->execute();
    }

    public function update($id, $first_name, $last_name, $email, $phone_no, $address, $DOB, $gender, $hobby, $country, $image_path = null) {
        if ($this->emailExists($email, $id)) {
            return false;
        }

        if (is_array($hobby)) {
            $hobby = implode(', ', $hobby);
        }

        $formatted_dob = date('Y-m-d', strtotime($DOB));

        $sql = "UPDATE `User` SET 
                    first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    phone_no = :phone_no,
                    address = :address,
                    DOB = :DOB,
                    gender = :gender,
                    hobby = :hobby,
                    country = :country";

        if (!empty($image_path)) {
            $sql .= ", image_path = :image_path";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_no', $phone_no);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':DOB', $formatted_dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':hobby', $hobby);
        $stmt->bindParam(':country', $country);
        if (!empty($image_path)) {
            $stmt->bindParam(':image_path', $image_path);
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM `User` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function read() {
        $sql = "SELECT * FROM `User` ORDER BY created_at DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function readById($id) {
        $sql = "SELECT * FROM `User` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
