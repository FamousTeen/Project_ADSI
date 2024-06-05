<?php
include("db_connect.php");

class EmployeeManager {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function fetchEmployees() {
        $sql = "SELECT empName, credit_score FROM Employee";
        $result = $this->mysqli->query($sql);

        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'empName' => $row['empName'],
                    'credit_score' => $row['credit_score']
                );
            }
        }

        return $data;
    }

    public function updateCreditScore($empName, $creditScore) {
        $empName = $this->mysqli->real_escape_string($empName);
        $creditScore = $this->mysqli->real_escape_string($creditScore);

        $sql = "UPDATE Employee SET credit_score = '$creditScore' WHERE empName = '$empName'";
        if ($this->mysqli->query($sql) === TRUE) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
    }
}
?>
