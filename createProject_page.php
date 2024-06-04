<?php
session_start();
include('db_connect.php');

$result2 = null;
if (isset($_SESSION['idMan'])) {
    $idMan = $_SESSION['idMan'];

    $sql = "SELECT * FROM permit WHERE man = $idMan";
    $result2 = mysqli_query($mysqli, $sql);

    $data = array();
}

$data = array();

$query = "SELECT * FROM project";
$result = mysqli_query($mysqli, $query);

$projects = array();
while ($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
}

mysqli_free_result($result);

$project_employees = array();

foreach ($projects as $project) {
    $project_id = $project['idProject'];

    // Fetch employee data excluding those already assigned to the specific project
    $employee_query = "
        SELECT e.* 
        FROM employee e 
        LEFT JOIN employeeProject ep ON e.idEmp = ep.idEmp AND ep.idProject = '$project_id' 
        WHERE ep.idEmp IS NULL
    ";
    $employee_result = mysqli_query($mysqli, $employee_query);

    $employees = array();
    while ($row = mysqli_fetch_assoc($employee_result)) {
        $employees[] = $row;
    }

    $project_employees[$project_id] = $employees;
    mysqli_free_result($employee_result);
}

// Pass the project and employee data to JavaScript
$projects_json = json_encode($projects);
$project_employees_json = json_encode($project_employees);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom UI</title>
    <link rel="stylesheet" href="create_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="create_script.js"></script>
    <style>
        .project-card {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .add-employee-btn, .add-task-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }
        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>DivRoom</h1>
        </div>
        <div class="header-right">
            <?php
            if (isset($_SESSION['idMan'])) {
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16" id="notif">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
            </svg>
            <?php
            }
            ?>
            <?php if (isset($_SESSION['name']) && $_SESSION['role'] == 'manager'): ?>
                <button id="createProjectBtn">Create Project</button>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <a style="text-decoration: none; color: inherit;" href="#"><li id="permitSide" class="active">My Projects</li></a>
                <a style="text-decoration: none; color: inherit;" href="createPermitReq.php"><li id="permitSide" >Permit Request</li></a>
                <a style="text-decoration: none; color: inherit;" href="progressBar.php"><li id="customSide">Custom Project Progress</li></a>
                <a style="text-decoration: none; color: inherit;" href="creditScore.php"><li id="creditSide">Credit score & awards</li></a>
            </ul>
        </aside>
        <main class="main-content">
            <!-- Project Cards will be added here -->
        </main>
    </div>

    <!-- Create Project Modal -->
    <div id="createProjectModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Create New Project</h2>
            <form id="createProjectForm" method="POST" action="create_project.php">
                <label for="projectName">Project Name:</label>
                <input type="text" id="projectName" name="projectName" required>
                
                <label for="projectDeadline">Project Deadline:</label>
                <input type="date" id="projectDeadline" name="projectDeadline" required>

                

                <button type="submit" name="submit" id="saveProjectButton">Create Project</button>
            </form>
        </div>
    </div>

    <!-- Create Notif Modal -->

    
    <div id="createProjectModal2" class="modal" style="display: none;">
        <div class="modal-content" id="modal-notif-content">
            <script>
                const createModalNotif = document.getElementById('modal-notif-content');
                <?php
                    $permitIndex = 0;
                    foreach ($result2 as $row) {
                        $data[$permitIndex] = array(
                            'title' => $row['permitTitle'],
                            'desc' => $row['description'],
                            'date' => $row['permitDate'],
                            'status' => $row['status']
                        );
                ?>
                createModalNotif.innerHTML += `
                    <h2>Permit title: <?php echo $data[$permitIndex]['title']?></h2>
                    <p>Description: <?php echo $data[$permitIndex]['desc']?></p>
                    <p>Permit date: <?php echo $data[$permitIndex]['date']?></p>
                    <button style="background-color:green;color: white;"><?php echo $data[$permitIndex]['status']?></button>
            `;
            <?php  $permitIndex +=1; };  
                ?>
            </script>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Add Employee to Project</h2>
            <form id="addEmployeeForm" method="POST" action="add_employee.php">
                <label for="employeeSelect">Select Employee:</label>
                <select id="employeeSelect" name="employeeId" required>
                    <!-- Employee options will be added here dynamically -->
                </select>
                <input type="hidden" id="projectId" name="projectId">
                <button type="submit" name="addEmployee" id="addEmployeeButton">Add Employee</button>
            </form>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Add Task to Project</h2>
            <form id="addTaskForm" method="POST" action="add_task.php">
                <label for="taskName">Task Name:</label>
                <input type="text" id="taskName" name="taskName" required>
                
                <label for="taskDescription">Task Description:</label>
                <input type="text" id="taskDescription" name="taskDescription" required>

                <label for="progressTask">Progress:</label>
                <input type="number" id="progressTask" name="progressTask" min="0" max="100" required>

                <input type="hidden" id="taskIdProject" name="projectId">
                <button type="submit" name="addTask" id="addTaskButton">Add Task</button>
            </form>
        </div>
    </div>

     <!-- View Details Modal -->
     <!-- <div id="viewDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Project Details</h2>
            <div id="projectDetailsContent">
                Project details will be added here dynamically
            </div>
        </div>
    </div> -->
    

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const createProjectBtn = document.getElementById('createProjectBtn');
    const cekNotif = document.getElementById('notif');
    const createProjectModal = document.getElementById('createProjectModal');
    const createProjectModal2 = document.getElementById('createProjectModal2');
    const closeBtns = document.querySelectorAll('.close-btn');
    const closeBtns2 = document.querySelectorAll('.close-btn2');
    const addEmployeeModal = document.getElementById('addEmployeeModal');
    const addTaskModal = document.getElementById('addTaskModal');
    const employeeSelect = document.getElementById('employeeSelect');
    const projectIdInput = document.getElementById('projectId');
    const taskIdProjectInput = document.getElementById('taskIdProject');
    const viewDetailsModal = document.getElementById('viewDetailsModal');

    $( "#permitSide" ).on( "click", function() {
        $( "#permitSide" ).toggleClass("active", true);
        $( "#projectSide" ).toggleClass("active", false);
        $( "#creditSide" ).toggleClass("active", false);
        $( "#customSide" ).toggleClass("active", false);
    } );

    $( "#projectSide" ).on( "click", function() {
        $( "#projectSide" ).toggleClass("active", true);
        $( "#permitSide" ).toggleClass("active", false);
        $( "#creditSide" ).toggleClass("active", false);
        $( "#customSide" ).toggleClass("active", false);
    } );

    $( "#creditSide" ).on( "click", function() {
        $( "#creditSide" ).toggleClass("active", true);
        $( "#projectSide" ).toggleClass("active", false);
        $( "#permitSide" ).toggleClass("active", false);
        $( "#customSide" ).toggleClass("active", false);
    } );

    $( "#customSide" ).on( "click", function() {
        $( "#customSide" ).toggleClass("active", true);
        $( "#projectSide" ).toggleClass("active", false);
        $( "#creditSide" ).toggleClass("active", false);
        $( "#permitSide" ).toggleClass("active", false);
    } );


    createProjectBtn.addEventListener('click', () => {
        createProjectModal.style.display = 'block';
    });

    cekNotif.addEventListener('click', () => {
        createProjectModal2.style.display = 'block';
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.modal').style.display = 'none';
        });
    });

    closeBtns2.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target == createProjectModal) {
            createProjectModal.style.display = 'none';
        }
        if (event.target == createProjectModal2) {
            createProjectModal2.style.display = 'none';
        }
        if (event.target == addEmployeeModal) {
            addEmployeeModal.style.display = 'none';
        }
        if (event.target == addTaskModal) {
            addTaskModal.style.display = 'none';
        }
    });

    const mainContent = document.querySelector('.main-content');

    // Fetch project data from PHP
    const projects = <?php echo $projects_json; ?>;
    const projectEmployees = <?php echo $project_employees_json; ?>;

    // Create project cards
    projects.forEach(project => {
        const projectCard = document.createElement('div');
        projectCard.classList.add('project-card');
        projectCard.innerHTML = `
            <button style="background-color:green;color: white;">${project.status}</button>
            <h2>Project Name: ${project.projectName}</h2>
            <p>Start Date: ${project.startDate}</p>
            <p>Deadline Date: ${project.Deadline}</p>
            <p>Progress: ${project.progressBar}%</p>
            <p>Manager: ${project.man}</p>
            <button class="add-employee-btn" data-project-id="${project.idProject}">Add Employee</button>
            <button class="add-task-btn" data-project-id="${project.idProject}">Add Task</button>
            <button class="view-details-btn" data-project-id="${project.idProject}">View Details</button>
        `;
        mainContent.appendChild(projectCard);
    });

    mainContent.addEventListener('click', (event) => {
        if (event.target.classList.contains('add-employee-btn')) {
            const projectId = event.target.dataset.projectId;
            projectIdInput.value = projectId;
            taskIdProjectInput.value = projectId;
            employeeSelect.innerHTML = '';

            const employees = projectEmployees[projectId];
            employees.forEach(employee => {
                const option = document.createElement('option');
                option.value = employee.idEmp;
                option.textContent = employee.empName;
                employeeSelect.appendChild(option);
            });

            addEmployeeModal.style.display = 'block';
        } else if (event.target.classList.contains('add-task-btn')) {
            const projectId = event.target.dataset.projectId;
            taskIdProjectInput.value = projectId;
            addTaskModal.style.display = 'block';
        } else if (event.target.classList.contains('view-details-btn')) {
            const projectId = event.target.dataset.projectId;

            // Fetch project details
            fetch(`fetch_project_details.php?projectId=${projectId}`)
                .then(response => response.json())
                .then(data => {
                    const projectDetailsContent = document.getElementById('projectDetailsContent');
                    projectDetailsContent.innerHTML = `
                        <p><strong>Project Name:</strong> ${data.projectName}</p>
                        <p><strong>Start Date:</strong> ${data.startDate}</p>
                        <p><strong>Deadline:</strong> ${data.deadline}</p>
                        <p><strong>Progress:</strong> ${data.progress}%</p>
                        <h3>Members</h3>
                        <ul>
                            ${data.members.map(member => `<li>${member.empName}</li>`).join('')}
                        </ul>
                        <h3>Tasks</h3>
                        <ul>
                            ${data.tasks.map(task => `<li>${task.taskName}: ${task.taskDescription} (${task.progress}%)</li>`).join('')}
                        </ul>
                    `;
                    viewDetailsModal.style.display = 'block';
                })
                .catch(error => console.error('Error fetching project details:', error));
        }
    });
});

    </script>
</body>
</html>