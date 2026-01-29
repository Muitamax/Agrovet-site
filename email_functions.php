<?php
// Email Configuration and Functions

// SMTP Configuration
define('SMTP_HOST', 'imap.gmail.com');
define('SMTP_PORT',993);
define('SMTP_USER', 'youremail@gmail.com');
define('SMTP_PASS', 'Aa@112233');
define('FROM_EMAIL', 'youremail@gmail.com');
define('FROM_NAME', 'Amboni Agrovet');

// Simple email function using mail()
function sendEmail($to, $subject, $message, $headers = []) {
    $default_headers = [
        'From' => FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To' => FROM_EMAIL,
        'Content-Type' => 'text/html; charset=UTF-8'
    ];
    
    $headers = array_merge($default_headers, $headers);
    
    $header_string = '';
    foreach ($headers as $key => $value) {
        $header_string .= $key . ': ' . $value . "\r\n";
    }
    
    return mail($to, $subject, $message, $header_string);
}

// Send order confirmation email
function sendOrderConfirmation($customer_email, $customer_name, $order_id, $order_total, $items) {
    $subject = "Order Confirmation - Amboni Agrovet #" . $order_id;
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .container { max-width: 600px; margin: 0 auto; }
            .header { background-color: #2c5f2d; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; border: 1px solid #ddd; }
            .items { margin: 20px 0; }
            .item { border-bottom: 1px solid #eee; padding: 10px 0; }
            .item:last-child { border-bottom: none; }
            .total { background-color: #f5f5f5; padding: 15px; text-align: right; font-size: 1.2em; font-weight: bold; }
            .footer { background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🌾 Amboni Agrovet</h1>
                <p>Thank you for your order!</p>
            </div>
            
            <div class='content'>
                <p>Hello " . htmlspecialchars($customer_name) . ",</p>
                
                <p>Your order has been received and is being processed. Here are your order details:</p>
                
                <p><strong>Order ID:</strong> #" . $order_id . "</p>
                <p><strong>Date:</strong> " . date('M d, Y') . "</p>
                
                <div class='items'>
                    <h3>Order Items:</h3>";
    
    while ($item = $items->fetch_assoc()) {
        $message .= "
                    <div class='item'>
                        <strong>" . htmlspecialchars($item['name']) . "</strong><br>
                        Quantity: " . $item['quantity'] . " x KES " . number_format($item['price'], 2) . " = KES " . number_format($item['quantity'] * $item['price'], 2) . "
                    </div>";
    }
    
    $message .= "
                </div>
                
                <div class='total'>
                    Total: KES " . number_format($order_total, 2) . "
                </div>
                
                <p style='margin-top: 20px;'>
                    <strong>Next Steps:</strong><br>
                    You will receive payment instructions shortly. You can also track your order status by logging into your account at www.amboniagrovet.com
                </p>
                
                <p>Thank you for shopping with Amboni Agrovet!</p>
            </div>
            
            <div class='footer'>
                <p>Amboni Agrovet | Mweiga Town, next to Siel Supermarket<br>
                📞 +254712345678 | 📧 youremail@gmail.com</p>
            </div>
        </div>
    </body>
    </html>";
    
    return sendEmail($customer_email, $subject, $message);
}

// Send admin notification
function sendAdminNotification($order_id, $customer_name, $order_total) {
    $subject = "New Order - Amboni Agrovet #" . $order_id;
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>New Order Received!</h2>
        <p><strong>Order ID:</strong> #" . $order_id . "</p>
        <p><strong>Customer:</strong> " . htmlspecialchars($customer_name) . "</p>
        <p><strong>Amount:</strong> KES " . number_format($order_total, 2) . "</p>
        <p><strong>Date:</strong> " . date('M d, Y H:i') . "</p>
        <p><a href='http://localhost/admin/order-details.php?id=" . $order_id . "' style='background-color: #2c5f2d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Order Details</a></p>
    </body>
    </html>";
    
    return sendEmail('youremail@gmail.com', $subject, $message);
}

// Send payment reminder
function sendPaymentReminder($customer_email, $customer_name, $order_id, $order_total) {
    $subject = "Payment Reminder - Order #" . $order_id;
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Payment Reminder</h2>
        <p>Hello " . htmlspecialchars($customer_name) . ",</p>
        <p>This is a friendly reminder to complete payment for your order #" . $order_id . "</p>
        <p><strong>Amount Due:</strong> KES " . number_format($order_total, 2) . "</p>
        <h3>Payment Methods Accepted:</h3>
        <ul>
            <li><strong>M-Pesa:</strong> Send to Till Number or Business Number (to be provided)</li>
            <li><strong>Bank Transfer:</strong> Bank details available in your account</li>
            <li><strong>Cash on Delivery:</strong> Pay when item is delivered</li>
        </ul>
        <p>Please reply to this email with proof of payment.</p>
    </body>
    </html>";
    
    return sendEmail($customer_email, $subject, $message);
}

// Send shipping notification
function sendShippingNotification($customer_email, $customer_name, $order_id, $tracking_number = '') {
    $subject = "Your Order is on the Way - #" . $order_id;
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Your Order is Shipped!</h2>
        <p>Hello " . htmlspecialchars($customer_name) . ",</p>
        <p>Your order #" . $order_id . " has been shipped and is on its way to you!</p>";
    
    if (!empty($tracking_number)) {
        $message .= "<p><strong>Tracking Number:</strong> " . htmlspecialchars($tracking_number) . "</p>";
    }
    
    $message .= "
        <p>You will receive your order within 2-3 business days.</p>
        <p>Thank you for shopping with Amboni Agrovet!</p>
    </body>
    </html>";
    
    return sendEmail($customer_email, $subject, $message);
}
?>
