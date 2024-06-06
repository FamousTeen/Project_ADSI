<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definisi class Project
class employeeProject {
    public $idProject;
    // Properties
    public $idEmp;
    

    // Constructor
    public function __construct($idEmp, $idProject){
        $this->$idEmp = $idEmp;
        $this->$idProject = $idProject;
       
    }

    // // Method untuk mengatur detail proyek
    // public function inputProjectDetail($projectName, $projectDeadline, $projectManager) {
    //     $idManager= null;
    //     // Fetching idManager from the session (assuming it's stored during login)
    // if (isset($_SESSION['idManager'])) {
    //     $idManager = (int)$_SESSION['idManager']; // Ensuring idManager is treated as an integer
    // } else {
    //     echo "<script>alert('Error: Manager ID not found in session.');</script>";
    //     exit();
    // }
    //     $this->projectName = $_POST['projectName'];
    //     $this->projectDeadline = $_POST['projectDeadline'];
    //     $this->projectManager = $idManager;
    // }

    // Method untuk membuat proyek
    
    // Method untuk menambahkan anggota tim ke proyek
    public function addEmployeeProject($idEmp,$idProject) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        session_start();
        include('db_connect.php');

        if (isset($_POST['addEmployee'])) {
            $employeeId = $_POST['employeeId'];
            $projectId = $_POST['projectId'];

            // Insert the employee into the project (assuming you have a project_employee table)
            $query = "INSERT INTO employeeProject (idEmp, idProject) VALUES ('$employeeId', '$projectId')";
            
            if (mysqli_query($mysqli, $query)) {
                echo "Employee added successfully.";
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
            }

            mysqli_close($mysqli);
            header("Location: createProject_page.php");
            exit();
        }

    }

}
?>
