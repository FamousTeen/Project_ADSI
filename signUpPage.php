<?php
include('db_connect.php');
include('sign_up.php')

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="container">
    <div class="signup-card">
      <div class="card-header">
        Signup
      </div>
      <form name="signup_form" method="POST" action="sign_up.php">
        <div class="card-body">
          <div class="input-box">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
          </div>
          <div class="input-box">
            <i class="fas fa-envelope"></i>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="input-box">
            <i class="fas fa-building"></i>
            <input type="text" id="departmentName" name="departmentName" placeholder="Enter department name" required>
          </div>
          <div class="input-box">
            <i class="fas fa-phone"></i>
            <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number" required>
          </div>
          <div class="input-box">
            <i class="fas fa-lock"></i>
            <input type="password" id="pass" name="pass" placeholder="Enter your password" required>
          </div>
          <div class="input-box">
            <i class="fas fa-lock"></i>
            <input type="password" id="cpass" name="cpass" placeholder="Confirm your password" required>
          </div>
          <div class="input-box">
            <i class="fas fa-user-friends"></i>
            <select id="role" name="role" required>
              <option value="">Select Role</option>
              <option value="manager">Manager</option>
              <option value="employee">Employee</option>
            </select>
          </div>
          <div class="button input-box">
            <input type="submit" id="btn" value="SignUp" name="signup">
          </div>
          <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
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
    
    .input-box input {
        width: calc(100% - 40px);
        padding: 10px;
        padding-left: 40px;
        border: 1px solid #999;
        outline: none;
        border-radius: 5px;
    }
    
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