<?php
session_start();
include "includes/db.php";

$error = "";
$email = ""; 

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
           
            if (password_verify($password, $user['password']) || $password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid Email or Password!";
            }
        } else {
            $error = "Account not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | Watch Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(175deg, #9e62ab70, #ad6e6e); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 15px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.2); color: #fff; width: 380px; padding: 30px; }
        .form-control { background: rgba(255,255,255,0.2); border: none; color: #fff; padding: 12px; }
        .form-control::placeholder { color: #ddd; }
        .form-control:focus { background: rgba(255,255,255,0.3); color: #fff; box-shadow: none; border: 1px solid #fff; }
        .btn-custom { background: #864383; border: none; color: white; padding: 12px; font-weight: bold; }
        .btn-custom:hover { background: #ad6e6e; color: #fff; }
    </style>
</head>
<body>
    <div class="glass-card">
        <h3 class="text-center mb-4">Login</h3>
        <?php if($error): ?>
            <div class="alert alert-danger py-2" style="font-size: 14px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" autocomplete="off">
            <input type="text" style="display:none">
            <input type="password" style="display:none">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" 
                       value="<?php echo isset($_POST['login']) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" 
                       autocomplete="new-password" required>
            </div>
            <button name="login" class="btn btn-custom w-100 mt-2">Sign In</button>
        </form>
        <p class="text-center mt-4 mb-0 small">Don't have an account? <a href="register.php" class="text-white fw-bold">Register</a></p>
    </div>
</body>
</html>