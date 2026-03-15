<?php
session_start();
include "includes/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total_price = 0;

// جلب منتجات السلة الخاصة باليوزر ده
$query = "SELECT * FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <h2 class="mb-4"><i class="fa-solid fa-cart-shopping" style="color: #864383;"></i> Shopping Cart</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th class="text-center">Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): 
                                    $subtotal = $row['price'] * $row['qty'];
                                    $total_price += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/images/<?php echo $row['image']; ?>" 
                                                 style="width: 65px; height: 65px; object-fit: cover;" 
                                                 class="me-3 rounded border shadow-sm">
                                            
                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['title']); ?></span>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border p-2 px-3"><?php echo $row['qty']; ?></span>
                                    </td>
                                    <td class="fw-bold" style="color: #864383;">$<?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <a href="remove_item.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger shadow-sm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fa-solid fa-basket-shopping fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Your cart is currently empty!</p>
                                        <a href="product.php" class="btn btn-outline-primary btn-sm">Start Shopping</a>
                                    </td>
                                </tr>
                                
                            <?php endif; ?>
                           
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4 bg-white">
                <h4 class="fw-bold mb-3">Order Summary</h4>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span>$<?php echo number_format($total_price, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Shipping:</span>
                    <span class="text-success fw-bold">FREE</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5">Total:</span>
                    <span class="h4 fw-bold" style="color: #864383;">$<?php echo number_format($total_price, 2); ?></span>
                </div>
                
                <a href="shipping.php" class="btn btn-primary w-100 py-3 fw-bold shadow-sm <?php echo ($total_price == 0) ? 'disabled' : ''; ?>" 
                   style="background: #864383; border:none; border-radius: 10px;">
                    Proceed to Shipping <i class="fa-solid fa-chevron-right ms-2 small"></i>
                </a>
                
                <a href="product.php" class="btn btn-link w-100 mt-2 text-decoration-none text-muted small text-center">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>