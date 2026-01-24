<?php
class User {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function login($email, $password) {
        $email = mysqli_real_escape_string($this->conn, $email);
        $result = $this->conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) return $user;
        }
        return false;
    }
}
?>