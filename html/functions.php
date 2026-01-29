<?php
include 'config.php';

// User login check
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Customer login check
function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

// Admin login check
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Sanitize input
function sanitize($input) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(trim($input)));
}

// Get products
function getProducts($limit = 12, $offset = 0, $category = null, $search = null) {
    global $conn;
    
    $query = "SELECT * FROM products WHERE status = 'active'";
    
    if ($category) {
        $category = sanitize($category);
        $query .= " AND category_id = '$category'";
    }
    
    if ($search) {
        $search = sanitize($search);
        $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
    }
    
    $query .= " LIMIT $limit OFFSET $offset";
    
    return $conn->query($query);
}

// Get product by ID
function getProductById($id) {
    global $conn;
    $id = sanitize($id);
    $result = $conn->query("SELECT * FROM products WHERE id = '$id'");
    return $result->fetch_assoc();
}

// Get categories
function getCategories() {
    global $conn;
    return $conn->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC");
}

// Get category by ID
function getCategoryById($id) {
    global $conn;
    $id = sanitize($id);
    $result = $conn->query("SELECT * FROM categories WHERE id = '$id'");
    return $result->fetch_assoc();
}

// Add to cart
function addToCart($product_id, $quantity, $customer_id) {
    global $conn;
    
    $product_id = sanitize($product_id);
    $quantity = sanitize($quantity);
    $customer_id = sanitize($customer_id);
    
    // Check if item exists in cart
    $check = $conn->query("SELECT * FROM carts WHERE customer_id = '$customer_id' AND product_id = '$product_id'");
    
    if ($check->num_rows > 0) {
        $query = "UPDATE carts SET quantity = quantity + $quantity WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
    } else {
        $query = "INSERT INTO carts (customer_id, product_id, quantity) VALUES ('$customer_id', '$product_id', '$quantity')";
    }
    
    return $conn->query($query);
}

// Get cart items
function getCartItems($customer_id) {
    global $conn;
    $customer_id = sanitize($customer_id);
    
    $query = "SELECT c.*, p.name, p.price, p.image FROM carts c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.customer_id = '$customer_id' AND c.quantity > 0";
    
    return $conn->query($query);
}

// Calculate cart total
function getCartTotal($customer_id) {
    global $conn;
    $customer_id = sanitize($customer_id);
    
    $result = $conn->query("SELECT SUM(c.quantity * p.price) as total FROM carts c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.customer_id = '$customer_id'");
    
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Remove from cart
function removeFromCart($cart_id, $customer_id) {
    global $conn;
    
    $cart_id = sanitize($cart_id);
    $customer_id = sanitize($customer_id);
    
    return $conn->query("DELETE FROM carts WHERE id = '$cart_id' AND customer_id = '$customer_id'");
}

// Update cart quantity
function updateCartQuantity($cart_id, $quantity, $customer_id) {
    global $conn;
    
    $cart_id = sanitize($cart_id);
    $quantity = sanitize($quantity);
    $customer_id = sanitize($customer_id);
    
    if ($quantity <= 0) {
        return removeFromCart($cart_id, $customer_id);
    }
    
    return $conn->query("UPDATE carts SET quantity = $quantity WHERE id = '$cart_id' AND customer_id = '$customer_id'");
}

// Get customer orders
function getCustomerOrders($customer_id, $limit = 10) {
    global $conn;
    $customer_id = sanitize($customer_id);
    
    return $conn->query("SELECT * FROM orders WHERE customer_id = '$customer_id' ORDER BY created_at DESC LIMIT $limit");
}

// Get order by ID
function getOrderById($order_id, $customer_id = null) {
    global $conn;
    
    $order_id = sanitize($order_id);
    
    $query = "SELECT * FROM orders WHERE id = '$order_id'";
    if ($customer_id) {
        $customer_id = sanitize($customer_id);
        $query .= " AND customer_id = '$customer_id'";
    }
    
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

// Get order items
function getOrderItems($order_id) {
    global $conn;
    $order_id = sanitize($order_id);
    
    return $conn->query("SELECT * FROM order_items WHERE order_id = '$order_id'");
}

// Create order from cart
function createOrder($customer_id, $total, $status = 'pending') {
    global $conn;
    
    $customer_id = sanitize($customer_id);
    $total = floatval($total);
    $status = sanitize($status);
    
    $conn->query("INSERT INTO orders (customer_id, total, status, created_at) 
                  VALUES ('$customer_id', '$total', '$status', NOW())");
    
    return $conn->insert_id;
}

// Add order items
function addOrderItems($order_id, $customer_id) {
    global $conn;
    
    $order_id = sanitize($order_id);
    $customer_id = sanitize($customer_id);
    
    $cartItems = getCartItems($customer_id);
    
    while ($item = $cartItems->fetch_assoc()) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES ('$order_id', '$product_id', '$quantity', '$price')");
    }
    
    // Clear cart
    $conn->query("DELETE FROM carts WHERE customer_id = '$customer_id'");
    
    return true;
}

// Format currency
function formatCurrency($amount) {
    return 'KES ' . number_format($amount, 2);
}

// Get customer info
function getCustomerInfo($customer_id) {
    global $conn;
    $customer_id = sanitize($customer_id);
    
    $result = $conn->query("SELECT * FROM customers WHERE id = '$customer_id'");
    return $result->fetch_assoc();
}
?>
