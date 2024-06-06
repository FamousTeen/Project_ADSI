<?php
session_start(); // Start session

include 'db_connect.php'; // Include database connection file
include 'creditClass.php';

// Check if the user is logged in and their role is manager
if (isset($_SESSION['name']) && $_SESSION['role'] == 'manager') {
    $createProjectButton = '';
} else {
    $createProjectButton = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head section -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom UI</title>
    <link rel="stylesheet" href="create_style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0073e6;
            padding: 10px 20px;
            color: #fff;
        }

        header .header-left h1 {
            margin: 0;
        }

        header .header-right button {
            padding: 5px 10px;
            background-color: white;
            color: #4285F4;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .container {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 250px;
            background-color: #3c4043;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .sidebar ul a {
            text-decoration: none;
            color: inherit;
        }

        .sidebar ul li {
            padding: 15px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li.active {
            background-color: #5f6368;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            margin: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 4px;
        }

        .employee-list {
            margin-top: 20px;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-table th,
        .employee-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .employee-table th {
            background-color: #f4f4f4;
        }

        /* Credit score styles */
        .excellent {
            background-color: #d4edda;
        }

        .good {
            background-color: #c3e6cb;
        }

        .average {
            background-color: #ffeeba;
        }

        .below-average {
            background-color: #f8d7da;
        }

        .poor {
            background-color: #f5c6cb;
        }

        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1000;
        }

        .popup input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .popup button {
            width: calc(50% - 10px);
            padding: 10px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
        }

        .popup .save-button {
            background-color: #28a745;
            color: #fff;
            border: none;
        }

        .popup .cancel-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .popup button:hover {
            opacity: 0.9;
        }

        /* Overlay styles */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>

<body>
    <header>
        <!-- Header section -->
        <div class="header-left">
            <h1>DivRoom</h1>
        </div>
        <div class="header-right">
            <?php echo $createProjectButton; ?>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <!-- Sidebar content -->
            <ul>
                <?php if (isset($_SESSION['idManager'])) { ?>
                    <a style="text-decoration: none; color: inherit;" href="createProject_page.php"><li id="permitSide" >My Projects</li></a>
                    <a style="text-decoration: none; color: inherit;" href="progressBar.php"><li id="customSide">Custom Project Progress</li></a>
                    <a style="text-decoration: none; color: inherit;" href="creditScore.php"><li id="creditSide" class="active">Credit score & awards</li></a>
                <?php } else { ?>
                    <a style="text-decoration: none; color: inherit;" href="createProject_page.php"><li id="permitSide" >My Projects</li></a>
                    <a style="text-decoration: none; color: inherit;" href="#"><li id="permitSide" class="active">Permit Request</li></a>           
                <?php }?>
            </ul>
        </aside>
        <main class="main-content">
            <div class="content-wrapper">
                <h2>Employee Credit Score & Performance Rewards</h2>
                <div id="result" class="result" style="display: none;"></div>
                <div class="employee-list">
                    <h3>Employee Reward Status</h3>
                    <table class="employee-table" id="employeeTable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Credit Score</th>
                                <th>Performance Rating</th>
                                <th>Bonus</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="overlay"></div>
    <div class="popup">
        <h3>Edit Credit Score</h3>
        <input type="text" id="newCreditScore" placeholder="Enter new credit score">
        <button class="save-button">Save</button>
        <button class="cancel-button">Cancel</button>
    </div>

    <script>
        // JavaScript section
        $(document).ready(function () {
            // Function to determine bonus and performance rating based on credit score
            function getBonusAndRating(creditScore) {
                let bonus, rating;
                if (creditScore >= 80) {
                    bonus = '20%';
                    rating = 'Excellent';
                } else if (creditScore >= 70) {
                    bonus = '15%';
                    rating = 'Good';
                } else if (creditScore >= 60) {
                    bonus = '10%';
                    rating = 'Average';
                } else if (creditScore >= 50) {
                    bonus = '5%';
                    rating = 'Below Average';
                } else {
                    bonus = '0%';
                    rating = 'Poor';
                }
                return { bonus: bonus, rating: rating };
            }

            // Function to fetch employees and populate the table
            function fetchEmployees() {
                $.ajax({
                    url: 'fetch_credit.php',
                    type: 'GET',
                    dataType: 'json', // Specify JSON data type
                    success: function (data) {
                        $.each(data, function (index, employee) {
                            // Determine bonus and rating based on credit score
                            var result = getBonusAndRating(employee.credit_score);

                            // Append employee data to table row
                            var row = '<tr>' +
                                '<td>' + employee.empName + '</td>' +
                                '<td class="credit-score">' + employee.credit_score + '</td>' +
                                '<td class="rating">' + result.rating + '</td>' +
                                '<td class="bonus">' + result.bonus + '</td>' +
                                '<td><button class="update-btn">Update</button></td>' +
                                '</tr>';
                            $('#employeeTable tbody').append(row);
                        });
                    },
                    error: function () {
                        $('#result').text('Failed to fetch employees').show();
                    }
                });
            }

            // Call fetchEmployees function on page load
            fetchEmployees();

            // Show popup to edit credit score
            $(document).on('click', '.update-btn', function () {
                var row = $(this).closest('tr');
                var empName = row.find('td:eq(0)').text();
                var currentScore = row.find('.credit-score').text();

                // Show popup with current score
                $('#newCreditScore').val(currentScore);
                $('.popup').data('empName', empName);
                $('.overlay, .popup').show();
            });

            // Save new credit score
            $('.save-button').click(function () {
                var newCreditScore = $('#newCreditScore').val();
                var empName = $('.popup').data('empName');

                // Validate credit score input
                if (isNaN(newCreditScore) || newCreditScore < 0 || newCreditScore > 100) {
                    $('#result').text('Invalid credit score. Please enter a number between 0 and 100.').show();
                    return;
                }

                // AJAX request to update credit score
                $.ajax({
                    url: 'update_credit_score.php',
                    type: 'POST',
                    data: { empName: empName, creditScore: newCreditScore },
                    success: function (response) {
                        var responseObj = JSON.parse(response);
                        if (responseObj.success) {
                            $('#result').text('Credit score updated successfully').show();

                            // Update the table row with the new credit score
                            var row = $('#employeeTable td:contains("' + empName + '")').closest('tr');
                            var result = getBonusAndRating(newCreditScore);
                            row.find('.credit-score').text(newCreditScore);
                            row.find('.rating').text(result.rating);
                            row.find('.bonus').text(result.bonus);

                            // Hide popup
                            $('.overlay, .popup').hide();
                        } else {
                            $('#result').text('Failed to update credit score').show();
                        }
                    },
                    error: function () {
                        $('#result').text('Failed to update credit score').show();
                    }
                });
            });

            // Cancel button to hide popup
            $('.cancel-button').click(function () {
                $('.overlay, .popup').hide();
            });
        });
    </script>
</body>

</html>
