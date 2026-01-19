<?php
class Turf {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getTurfs($search = "") {
        $search = mysqli_real_escape_string($this->conn, $search);
        $sql = "SELECT * FROM turfs";
        if (!empty($search)) $sql .= " WHERE name LIKE '%$search%' OR location LIKE '%$search%'";
        return $this->conn->query($sql);
    }
}
?>