<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .signup-card {
            width: 400px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px; /* Adjusted margin for better centering */
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 20px;
            color: #999;
        }

        .input-box input,
        .input-box select {
            width: calc(100% - 40px);
            padding: 10px;
            padding-left: 40px;
            border: 1px solid #999;
            outline: none;
            border-radius: 5px;
        }

        .button {
            text-align: center;
        }

        .button input {
            width: 100%;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .sign-up-text {
            text-align: center;
            margin-top: 20px;
        }

        .sign-up-text label {
            color: #007bff;
            cursor: pointer;
        }

        .sign-up-text label:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-card">
            <div class="card-header">
                <h2>Sign Up</h2>
            </div>
            <div class="card-body">
                <form action="sign_up.php" method="POST" onsubmit="return validateForm()">
                    <div class="input-box">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-building"></i>
                        <input type="text" id="department" name="department" placeholder="Department Name" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="manager">Manager</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div class="button">
                        <input type="submit" value="Sign Up">
                    </div>
                </form>
                <div class="sign-up-text">
                    <label for="login">Already have an account? <a href="loginPage.php">Log in</a></label>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var cpassword = document.getElementById("cpassword").value;
            
            if (password !== cpassword) {
                alert("Error: Passwords do not match.");
                document.getElementById("password").focus();
                return false;
            }
            return true;
        }
    </script>
</body>
</html>

