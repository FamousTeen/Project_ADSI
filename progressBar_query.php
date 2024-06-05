<?php
include("db_connect.php");
session_start();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'getManagerDetails':
            $dept_name = $_SESSION['dept_name'];
            $sql = "SELECT * FROM manager WHERE departmentName = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $dept_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $_SESSION['manager_name'] = $row['managerName'];
                $response['manager_name'] = $row['managerName'];
            }
            break;

        case 'getPermitDetails':
            $idEmp = $_SESSION['idEmp'];
            $sql = "SELECT * FROM permit WHERE emp = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $idEmp);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();

            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'title' => $row['permitTitle'],
                    'desc' => $row['description'],
                    'date' => $row['permitDate'],
                    'status' => $row['status']
                );
            }
            $response['permits'] = $data;
            break;

        case 'getProjectDetails':
            $sql = "SELECT idProject, projectName FROM project";
            $result = $mysqli->query($sql);
            $projects = array();

            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }
            $response['projects'] = $projects;
            break;

        case 'getTasks':
            $idProject2 = $_POST['idProject'];
            $sql = "SELECT * FROM task WHERE idProject_task = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $idProject2);
            $stmt->execute();
            $result = $stmt->get_result();
            $tasks = array();

            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
            $response['tasks'] = $tasks;
            break;
    }
}

echo json_encode($response);

