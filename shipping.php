<?php
session_start();
include "includes/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$errors = [
    'phone' => '',
    'address' => ''
];

// استقبال رسائل الخطأ لو راجعة من ملف checkout.php
if (isset($_SESSION['validation_errors'])) {
    $errors = array_merge($errors, $_SESSION['validation_errors']);
    unset($_SESSION['validation_errors']);
}
?>

<div class="container mt-5" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4 border-0 rounded-4">
                <h3 class="mb-4 text-center">تفاصيل الشحن <i class="fa-solid fa-truck me-2 text-info"></i></h3>
                
                <form action="checkout.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رقم الهاتف</label>
                        <input type="text" name="phone" 
                               class="form-control <?php echo !empty($errors['phone']) ? 'is-invalid' : ''; ?>" 
                               placeholder="01xxxxxxxxx"
                               value="<?php echo $_SESSION['old_post']['phone'] ?? ''; ?>">
                        <?php if (!empty($errors['phone'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان بالتفصيل</label>
                        <textarea name="address" 
                                  class="form-control <?php echo !empty($errors['address']) ? 'is-invalid' : ''; ?>" 
                                  rows="3" 
                                  placeholder="الشارع، الدور، رقم الشقة..."><?php echo $_SESSION['old_post']['address'] ?? ''; ?></textarea>
                        <?php if (!empty($errors['address'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['address']; ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="confirm_order" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm">
                        تأكيد وطلب الأوردر الآن
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 

unset($_SESSION['old_post']);
include "includes/footer.php"; 
?>