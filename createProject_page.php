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
                <li id="permitSide">Permit</li>
                <li id="customSide">Custom Project Progress</li>
                <li id="creditSide">Credit score & awards</li>
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

                <!-- Add Team Members Section -->
                <label for="task">Add Task:</label>
                <div id="task">
                    <button type="button" id="addTaskFormButton">+ Add Task</button>
                </div>

                <div id="newTaskForm" class="hidden">
                    <label for="taskName">Name:</label>
                    <input type="text" id="taskNameId">
                    
                    <label for="taskDescription">Description:</label>
                    <input type="text" id="taskDescription">

                    <label for="taskProgress">Progress:</label>
                    <input type="number" id="taskProgress" min="0" max="100">
                    
                    <button type="button" id="addTaskButton">Add</button>
                </div>

                <table id="tasklist">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <label for="projectProgress">Project Progress:</label>
                <input type="number" id="projectProgress" name="projectProgress" min="0" max="100" required>
                
                <label for="teamMembers">Add Team Members:</label>
                <div id="teamMembers">
                    <button type="button" id="addMemberFormBtn">+ Add Member</button>
                </div>
                
                <div id="newMemberForm" class="hidden">
                <label for="employeeList">Select Employee:</label>
<select id="employeeList" name="employeeList[]" multiple>
    <?php
    // Assuming $employees is an array containing employee data fetched from the database
    foreach ($employees as $employee) {
        echo '<option value="' . $row['idEmp'] . '">' . $row['empName'] . ' - ' . $row['departmentName'] . '</option>';
    }
    ?>
</select>
                    <button type="button" id="addMemberBtn">Add</button>
                </div>
                
                <table id="memberList">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Department Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                
                <label for="fileTypes">File Submission Type:</label>
                <select id="fileTypes" name="fileTypes[]" required multiple>
                    <option value="pdf">PDF</option>
                    <option value="doc">DOC</option>
                    <option value="ppt">PPT</option>
                    <option value="xls">XLS</option>
                    <option value="jpg">JPG</option>
                    <option value="png">PNG</option>
                </select>

                <button type="submit" id="saveProjectButton">Create Project</button>
            </form>
        </div>
    </div>
</body>
</html>
