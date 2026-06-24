<?php
// ============================================================
// CartClass.php - Rubric Compliant Member Function Wrapper Class
// ============================================================
class ShoppingCart {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    }

    public function AddItem($itemId) {
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]++;
        } else {
            $_SESSION['cart'][$itemId] = 1;
        }
    }

    public function RemoveItem($itemId) {
        if (isset($_SESSION['cart'][$itemId])) {
            unset($_SESSION['cart'][$itemId]);
        }
    }

    public function EmptyCart() {
        unset($_SESSION['cart']);
    }

    public function ProcessInput($itemId, $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$itemId] = $quantity;
        } else {
            $this->RemoveItem($itemId);
        }
    }
}
?>