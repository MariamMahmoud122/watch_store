<?php
session_start();
include "includes/db.php";

$error = "";
$name = ""; $email = "";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        $check = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already exists!";
        } else {

            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_pass', 'user')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = 'user';
                header("Location: index.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | Watch Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(175deg, #9e62ab70, #ad6e6e); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 15px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.2); color: #fff; width: 400px; padding: 30px; }
        .form-control { background: rgba(255,255,255,0.2); border: none; color: #fff; }
        .form-control::placeholder { color: #ddd; }
        .btn-custom { background: #864383; border: none; color: white; padding: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="glass-card">
        <h3 class="text-center mb-4">Create Account</h3>
        <?php if($error): ?>
            <div class="alert alert-danger py-2" style="font-size: 13px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <div class="mb-2">
                <label class="small">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($_POST['register']) ? htmlspecialchars($name) : ''; ?>" required>
            </div>
            <div class="mb-2">
                <label class="small">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['register']) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            <div class="mb-2">
                <label class="small">Password</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password" required>
            </div>
            <div class="mb-3">
                <label class="small">Confirm Password</label>
                <input type="password" name="confirm" class="form-control" required>
            </div>
            <button name="register" class="btn btn-custom w-100">Sign Up</button>
        </form>
        <p class="text-center mt-3 small">Already have an account? <a href="login.php" class="text-white fw-bold">Login</a></p>
    </div>
</body>
</html>