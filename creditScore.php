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
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        header .header-right button:hover {
            background-color: #218838;
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
        }

        .popup .save-button {
            background-color: #28a745;
        }

        .popup .cancel-button {
            background-color: #dc3545;
        }

        .popup button:hover {
            opacity: 0.9;
        }

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
                <a style="text-decoration: none; color: inherit;" href="createProject_page"><li id="permitSide" >My Projects</li></a>
                <a style="text-decoration: none; color: inherit;" href="createPermitReq.php"><li id="permitSide" >Permit Request</li></a>
                <a style="text-decoration: none; color: inherit;" href="progressBar.php"><li id="customSide" >Custom Project Progress</li></a>
                <a style="text-decoration: none; color: inherit;" href="#"><li id="creditSide" class="active">Credit score & awards</li></a>
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
    <div class="header-right">
        <?php
        // Check if the user is logged in and their role is manager
        if (isset($_SESSION['name']) && $_SESSION['role'] == 'manager') {
            echo '<button id="createProjectBtn">Create Project</button>';
        }
        ?>
    </div>

    <script>
        $(document).ready(function () {
            $('#permitSide').on('click', function () {
                $('#permitSide').addClass('active').siblings().removeClass('active');
            });

            $('#projectSide').on('click', function () {
                $('#projectSide').addClass('active').siblings().removeClass('active');
            });

            $('#creditSide').on('click', function () {
                $('#creditSide').addClass('active').siblings().removeClass('active');
            });

            $('#customSide').on('click', function () {
                $('#customSide').addClass('active').siblings().removeClass('active');
            });

            const createProjectBtn = document.getElementById('createProjectBtn');
            const modal = document.getElementById('createProjectModal');
            const closeBtn = document.querySelector('.close-btn');
            const mainContent = document.querySelector('.main-content');

            createProjectBtn.onclick = () => {
                modal.style.display = 'block';
            };

            closeBtn.onclick = () => {
                modal.style.display = 'none';
            };

            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        });
    </script>
    <script src="creditScoreScript.js"></script>
</body>

</html>