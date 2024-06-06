<?php
include('db_connect.php');
include('project_class.php');
include('task_class.php');
include('employeeProject_class.php');
include('addPermit.php');

if (isset($_POST['submit'])) {
    $project = new Project(null, $_POST['projectName'], null, $_POST['$projectDeadline'], $_POST['projectName'], null, $status);
    $project->createProject($startDate, $projectDeadline, $projectName, $progressBar);
    
}

if (isset($_POST['addEmployee'])) {
    $employeeproject = new employeeProject(null,null);
    $employeeproject->addEmployeeProject($idEmp,$idProject);
}

if (isset($_POST['addTask'])) {
    $task = new Task(null,$_POST['taskName'], $_POST['taskDescription'], $_POST['taskDeadline'], $_POST['progressTask'], null);
    $task->addTask($taskName, $taskDescription, $taskDeadline, $progressTask, $idProject_task);
}


$idMan = $_SESSION['idMan'];
$permit = new Permit(null, null, null, null, null, null, "Unapprove");

list($data, $result2) = $permit->sendNotif();

$query = "SELECT * FROM project";
$result = mysqli_query($mysqli, $query);

$projects = array();
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['progressBar'] == 100) {
        // Update project status to "Done" in the database
        $updateSql = "UPDATE project SET status = 'Done' WHERE idProject = " . $row['idProject'];
        mysqli_query($mysqli, $updateSql);
    }

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
                 <?php if (isset($_SESSION['idManager'])) { ?>
                    <a style="text-decoration: none; color: inherit;" href="createProject_page.php"><li id="permitSide" class="active">My Projects</li></a>
                    <a style="text-decoration: none; color: inherit;" href="progressBar.php"><li id="customSide">Custom Project Progress</li></a>
                    <a style="text-decoration: none; color: inherit;" href="creditScore.php"><li id="creditSide">Credit score & awards</li></a>
                <?php } else { ?>
                    <a style="text-decoration: none; color: inherit;" href="createProject_page.php"><li id="permitSide" class="active">My Projects</li></a>
                    <a style="text-decoration: none; color: inherit;" href="createPermitReq.php"><li id="permitSide">Permit Request</li></a>           
                <?php }?>
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
        <form id="createProjectForm" method="POST" action="createProject_page.php" onsubmit="return validateForm()">
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
            <form id="addEmployeeForm" method="POST" action="createProject_page.php">
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
            <form id="addTaskForm" method="POST" action="createProject_page.php" onsubmit="return validateTaskForm()" >
                <label for="taskName">Task Name:</label>
                <input type="text" id="taskName" name="taskName" required>
                
                <label for="taskDescription">Task Description:</label>
                <input type="text" id="taskDescription" name="taskDescription" required>

                <label for="taskDeadline">Task Deadline:</label>
<input type="date" id="taskDeadline" name="taskDeadline" required>

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
    
    
    function validateForm() {
            const projectName = document.getElementById('projectName').value;
            const projectDeadline = document.getElementById('projectDeadline').value;

            if (projectName.trim() === '') {
                alert('Please enter a project name.');
                return false;
            }

            if (projectDeadline === '') {
                alert('Please select a project deadline.');
                return false;
            }

            return true;
        }

        function validateTaskForm() {
            const taskName = document.getElementById('taskName').value;
            const taskDescription = document.getElementById('taskDescription').value;
            const taskDeadline = document.getElementById('taskDeadline').value;
            const projectDeadline = document.getElementById('projectDeadline').value;

            if (taskName.trim() === '') {
                alert('Please enter a task name.');
                return false;
            }

            if (taskDescription.trim() === '') {
                alert('Please enter a task description.');
                return false;
            }

            if (taskDeadline === '') {
                alert('Please select a task deadline.');
                return false;
            }

            if (new Date(taskDeadline) > new Date(projectDeadline)) {
                alert('Task deadline cannot be later than the project deadline.');
                return false;
            }

            return true;
        }

        document.getElementById('projectDeadline').addEventListener('change', function() {
            const projectDeadline = this.value;
            document.getElementById('taskDeadline').setAttribute('max', projectDeadline);
        });


    
    const createProjectForm = document.getElementById('createProjectForm');
    createProjectForm.addEventListener('submit', (event) => {
        const projectNameInput = document.getElementById('projectName');
        const projectName = projectNameInput.value;

        // Check if the project name already exists in the projects array
        const isDuplicate = projects.some(project => project.projectName === projectName);

        if (isDuplicate) {
            alert('Project name already exists. Please choose a different name.');
            event.preventDefault(); // Prevent form submission
        } else {
            // Ask for confirmation before submitting the form
            if (!confirm('Are you sure you want to create this project?')) {
                event.preventDefault(); // Prevent form submission if not confirmed
            }
        }
        
    });

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