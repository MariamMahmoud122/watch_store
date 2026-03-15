<?php
session_start();
include "includes/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['role'] == 'admin');


$order_query = mysqli_query($conn, "SELECT orders.*, users.name as user_display_name, users.email 
                                    FROM orders 
                                    JOIN users ON orders.user_id = users.id 
                                    WHERE orders.id = $order_id");
$order = mysqli_fetch_assoc($order_query);

if (!$is_admin && $order['user_id'] != $user_id) {
    header("Location: orders.php");
    exit;
}


$items_query = mysqli_query($conn, "SELECT order_items.*, products.title, products.image 
                                    FROM order_items 
                                    JOIN products ON order_items.product_id = products.id 
                                    WHERE order_id = $order_id");
?>

<div class="container mt-5 mb-5" dir="rtl">
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white p-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">تفاصيل الطلب #<?php echo $order['id']; ?></h4>
            <button class="btn btn-outline-light btn-sm" onclick="window.print()">
                <i class="fa-solid fa-print"></i> طباعة الفاتورة
            </button>
        </div>
        
        <div class="card-body p-4 text-end">
            <div class="row mb-4">
                <div class="col-md-6 border-start">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-truck"></i> بيانات الشحن</h5>
                    <p><strong>الاسم:</strong> <?php echo $order['user_display_name']; ?></p>
                    <p><strong>الهاتف:</strong> <?php echo $order['phone']; ?></p>
                    <p><strong>العنوان:</strong> <?php echo $order['address']; ?></p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-info-circle"></i> معلومات الطلب</h5>
                    <p><strong>تاريخ الطلب:</strong> <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></p>
                    <p><strong>الحالة:</strong> 
                        <span class="badge <?php echo ($order['status'] == 'pending') ? 'bg-warning text-dark' : (($order['status'] == 'completed') ? 'bg-success' : 'bg-danger'); ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($item = mysqli_fetch_assoc($items_query)): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/<?php echo $item['image']; ?>" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" 
                                         class="me-3 border shadow-sm">
                                    <span class="fw-bold"><?php echo $item['title']; ?></span>
                                </div>
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['qty']; ?></td>
                            <td class="fw-bold text-dark">$<?php echo number_format($item['price'] * $item['qty'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-start py-3">الإجمالي النهائي:</th>
                            <th class="text-danger h5 py-3">$<?php echo number_format($order['total_price'], 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="mt-4 text-center">
                <a href="orders.php" class="btn btn-secondary px-5 rounded-pill shadow-sm">العودة للطلبات</a>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>