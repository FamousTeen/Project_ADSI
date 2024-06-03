<?php
// include('create_project.php');
include('db_connect.php');
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
    
</head>
<body>
    <header>
        <div class="header-left">
            <h1>DivRoom</h1>
        </div>
        <div class="header-right">
            <button id="createProjectBtn">Create Project</button>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li id="projectSide" class="active">Projects</li>
                <a style="text-decoration: none; color: inherit;" href="createPermitReq.php"><li id="permitSide">Permit</li></a>
                <a style="text-decoration: none; color: inherit;" href="progressBar.php"><li id="customSide">Custom Project Progress</li></a>
                <li id="creditSide">Credit score & awards</li>
            </ul>
        </aside>
        <main class="main-content">
            <!-- Project Cards will be added here -->
        </main>
    </div>
    <div class="header-right">
        <?php
        // Check if the user is logged in and their role is manager
        if (isset($_SESSION['name']) && $_SESSION['role'] == 'manager') {
            echo '<button id="createProjectBtn">Create Project</button>';
        }
        ?>
    </div>

<!-- Create Project Modal -->
<div id="createProjectModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Create New Project</h2>
        <form id="createProjectForm" method="POST" action="create_project.php">
        <?php
    // Start the session to access session variables
    session_start();

    // Check if the user is logged in and their role is manager
    if (isset($_SESSION['name']) && $_SESSION['role'] == 'manager') {
        // Include idManager as a hidden input field in the form
        echo '<input type="hidden" id="idManager" name="idManager" value="' . $_SESSION['idManager'] . '">';
    }
    ?>
            <label for="projectName">Project Name:</label>
            <input type="text" id="projectName" name="projectName" required>
            
            <label for="projectDeadline">Project Deadline:</label>
            <input type="date" id="projectDeadline" name="projectDeadline" required>

            <label for="projectProgress">Project Progress:</label>
            <input type="number" id="projectProgress" name="projectProgress" min="0" max="100" required>

            <button type="submit" id="saveProjectButton">Create Project</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const createProjectBtn = document.getElementById('createProjectBtn');
        const modal = document.getElementById('createProjectModal');
        const closeBtn = document.querySelector('.close-btn');

        createProjectBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        const mainContent = document.querySelector('.main-content');

        document.getElementById('createProjectForm').addEventListener('submit', (e) => {
            e.preventDefault();

            const projectName = document.getElementById('projectName').value;
            const projectDeadline = document.getElementById('projectDeadline').value;
            const projectProgress = document.getElementById('projectProgress').value;

            if (projectName && projectDeadline && projectProgress) {
                const projectCard = document.createElement('div');
                projectCard.classList.add('project-card');
                projectCard.innerHTML = `
                    <h2>${projectName}</h2>
                    <p>Deadline: ${projectDeadline}</p>
                    <p>Progress: ${projectProgress}%</p>
                    <button class="add-member-btn">Add Member</button>
                    <button class="add-task-btn">Add Task</button>
                `;
                mainContent.appendChild(projectCard);

                modal.style.display = 'none';
            } else {
                alert('Please fill in all required fields');
            }
        });

        mainContent.addEventListener('click', (event) => {
            if (event.target.classList.contains('add-member-btn')) {
                // Logic for adding member to the project
                console.log('Add member button clicked');
            } else if (event.target.classList.contains('add-task-btn')) {
                // Logic for adding task to the project
                console.log('Add task button clicked');
            }
        });
    });
</script>
</body>
</html>
