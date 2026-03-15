<?php
session_start();
include "includes/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($query);


if (isset($_POST['update_profile'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

   
    $update_query = "UPDATE users SET user_name = '$name', email = '$email' WHERE id = $user_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['user_name'] = $name;
        $success = "تم تحديث بياناتك بنجاح!";

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password = '$hashed_password' WHERE id = $user_id");
        }
        
      
        $user['user_name'] = $name;
        $user['email'] = $email;
    } else {
        $error = "حدث خطأ: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-5" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 text-center">
                    <h5 class="mb-0 text-primary"><i class="fa-solid fa-user-gear"></i> تعديل الملف الشخصي</h5>
                </div>
                <div class="card-body p-4 text-end">
                    
                    <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" name="user_name" class="form-control text-end" 
                                   value="<?php echo isset($user['user_name']) ? htmlspecialchars($user['user_name']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control text-end" 
                                   value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
                        </div>

                        <div class="mb-4">
    <label class="form-label">كلمة مرور جديدة (اختياري)</label>
    <div class="input-group">
        <span class="input-group-text" id="togglePassword" style="cursor: pointer; background: white;">
            <i class="fa-solid fa-eye-slash" id="eyeIcon"></i>
        </span>
        <input type="password" name="password" id="passwordInput" class="form-control text-end" placeholder="******">
    </div>
    <small class="text-muted">اتركها فارغة إذا لم ترد التغيير</small>
</div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_profile" class="btn btn-primary py-2" style="background: #864383; border:none;">
                                حفظ التغييرات
                            </button>
                            <a href="profile.php" class="btn btn-light py-2 border">العودة للبروفايل</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#passwordInput');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function (e) {
       
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>
<?php include "includes/footer.php"; ?>