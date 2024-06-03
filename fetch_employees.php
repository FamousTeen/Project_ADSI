<?php
include("db_connect.php");

// Fetch employees from the database
$sql = "SELECT idEmp, empName, departmentName FROM Employee";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row["idEmp"] . '">' . $row["idEmp"] . ' - ' . $row["empName"] . ' - ' . $row["departmentName"] . '</option>';
    }
} else {
    echo '<option value="">No employees found</option>';
}

$mysqli->close();
?>
