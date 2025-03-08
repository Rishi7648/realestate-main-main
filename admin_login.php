<?php
include 'db.php';
session_start();
/* form is submitted using post method*/ 
/*retriving form data */ 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        // Handle admin signup
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               // This function is used to validate the email address
            echo "<p>Invalid email format!</p>";
            exit();
        }

        // Validate password strength
        if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{6,}$/', $password)) {
// !preg_match is used for Validating input (e.g., ensuring a string does not contain invalid characters).
    // preg_match  function is used to perform a regular expression

            echo "<p>Password is weak! Please use at least 8 characters with a mix of letters and numbers.</p>";
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if admin email already exists
        $sql = "SELECT * FROM admin WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingAdmin) {
            echo "<p>Email already registered!</p>";
        } else {
            // Insert new admin into the database
            $sql = "INSERT INTO admin (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword // Use the hashed password here
            ]);

            // Admin registration successful, but no message is displayed
        }
    } elseif (isset($_POST['login'])) {
        // Handle admin login
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];

            header("Location: admin.php");
            exit();
        } else {
            echo "<script>alert('Invalid admin credentials!')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login & Signup</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .form-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
        }
        .form-container p {
            font-size: 14px;
            color: #666;
            margin-top: 15px;
        }
        .form-container p a {
            color: #4e54c8;
            text-decoration: none;
        }
        .toggle-form {
            margin-top: 20px;
            cursor: pointer;
            color: #4e54c8;
        }
        .password-container {
            position: relative;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        /* Stylish Links for forget password and don't have account? signup and already have an account?login*/
.toggle-form {
    display: inline-block;
    margin-top: 15px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    color: #4e54c8;
    transition: all 0.3s ease-in-out;
    position: relative; /** its normal position. This means you can move the element without affecting other elements around it. */
}

.toggle-form:hover {
    color: #8f94fb;
    text-shadow: 0 0 10px rgba(79, 92, 238, 0.6);
}

/* Underline Effect */
.toggle-form::after {
    content: '';
    display: block;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #4e54c8, #8f94fb);
    transition: width 0.4s ease-in-out;
}

.toggle-form:hover::after {
    width: 100%;
}

/* Center Alignment */
p .toggle-form {
    text-align: center;
    display: block;
    margin-top: 10px;
}


/* Underline Effect on Hover */
.forgot-password::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #4e54c8, #8f94fb);
    transition: width 0.3s ease-in-out;
}

.forgot-password:hover::after {
    width: 100%;
}

    </style>
</head>
<body>

    <!-- Admin Login Form -->
<div class="form-container" id="login-form">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Admin Email" required>
        <div class="password-container">
            <input type="password" id="login-password" name="password" placeholder="Password" required>
            <span class="eye-icon" onclick="togglePassword('login-password')">&#128065;</span>
        </div>
        <button type="submit" name="login">Login as Admin</button>
    </form>
    <p>
        <span class="toggle-form" onclick="toggleForm('signup-form')">Don't have an account? Signup</span><br>
        
    </p>
</div>

<!-- Admin Signup Form -->
<div class="form-container" id="signup-form" style="display: none;">
    <h2>Admin Signup</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="phone no" placeholder="Phone no" required>
        <input type="email" name="email" placeholder="Admin Email" required>
        <div class="password-container">
            <input type="password" id="signup-password" name="password" placeholder="Password" required>
            <span class="eye-icon" onclick="togglePassword('signup-password')">&#128065;</span>
        </div>
        <button type="submit" name="signup">Signup as Admin</button>
    </form>
    <p>
        <span class="toggle-form" onclick="toggleForm('login-form')">Already have an account? Login</span><br>
        
</div>

<script>
    function toggleForm(formId) {
        document.getElementById('login-form').style.display = formId === 'login-form' ? 'block' : 'none';
        document.getElementById('signup-form').style.display = formId === 'signup-form' ? 'block' : 'none';
    }

    function togglePassword(inputId) {
        var passwordField = document.getElementById(inputId);
        var icon = passwordField.nextElementSibling;

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.innerHTML = "&#128064;"; // Eye open icon
        } else {
            passwordField.type = "password";
            icon.innerHTML = "&#128065;"; // Eye closed icon
        }
    }
    
</script>

</body>
</html>
