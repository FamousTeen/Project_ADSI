<?php
// Include database connection file
include("db_connect.php");

// Check if form is submitted with POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $taskName = mysqli_real_escape_string($mysqli, $_POST["task"]); // Escape user input
  $taskDescription = mysqli_real_escape_string($mysqli, $_POST["description"]); // Escape user input
  $taskDeadline = mysqli_real_escape_string($mysqli, $_POST["deadline"]); // Escape user input
  $progressTask = $_POST["percentage"]; // Assuming percentage is the progress value
  $idProject = $_POST["idProject"]; // Assuming this comes from your project selection

  // Create insert SQL statement with escaped values
  $sql = "INSERT INTO task (taskName, taskDescription, taskDeadline, progressTask, idProject_task)
          VALUES ('" . $taskName . "', '" . $taskDescription . "', '" . $taskDeadline . "', " . $progressTask . ", " . $idProject . ")";

  // Execute the statement
  if (mysqli_query($mysqli, $sql)) {
    echo "Task added successfully!";
  } else {
    echo "Error adding task: " . mysqli_error($mysqli);
  }

  // Close connection
  mysqli_close($mysqli);
} else {
  // If form is not submitted, show an error message
  echo "Error: Please submit the form to add a task.";
}
