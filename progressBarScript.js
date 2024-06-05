$(document).ready(function() {
    // Event handlers for sidebar navigation
    $("#permitSide").on("click", function() {
        activateSidebar(this);
    });

    $("#projectSide").on("click", function() {
        activateSidebar(this);
    });

    $("#creditSide").on("click", function() {
        activateSidebar(this);
    });

    $("#customSide").on("click", function() {
        activateSidebar(this);
    });

    // Function to activate the sidebar menu item
    function activateSidebar(element) {
        $(".sidebar ul li").removeClass("active");
        $(element).addClass("active");
    }

    // Event handler for project selection
    $("#project-select").change(function() {
        var selectedProjectId = $(this).val();
        $("#idProject_task").val(selectedProjectId);
        displayTasks(selectedProjectId);
    });

    // Function to display tasks for the selected project
    function displayTasks(projectId) {
        var tasks = window['tasks_' + projectId] || [];
        $("#task-container").empty();
        tasks.forEach(function(task) {
            var taskCard = `
                <div class="class-card">
                    <p><strong>Task Name:</strong> ${task.taskName}</p>
                    <p><strong>Task Description:</strong> ${task.taskDescription}</p>
                    <p><strong>Deadline:</strong> ${task.taskDeadline}</p>
                    <p><strong>Progress:</strong> ${task.progressTask}%</p>
                </div>`;
            $("#task-container").append(taskCard);
        });
    }

    // Function to handle adding a task
    window.addTask = function(event) {
        event.preventDefault();
        var task = $("#task").val();
        var description = $("#description").val();
        var taskDeadline = $("#taskDeadline").val();
        var progressTask = $("#progressTask").val();
        var idProject_task = $("#idProject_task").val();

        if (task && taskDeadline && progressTask && idProject_task) {
            var taskCard = `
                <div class="class-card">
                    <p><strong>Task Name:</strong> ${task}</p>
                    <p><strong>Task Description:</strong> ${description}</p>
                    <p><strong>Deadline:</strong> ${taskDeadline}</p>
                    <p><strong>Progress:</strong> ${progressTask}%</p>
                </div>`;
            $("#task-container").append(taskCard);
            $("#task-form form")[0].reset();
        } else {
            alert("Please fill in all required fields.");
        }
    };
});
