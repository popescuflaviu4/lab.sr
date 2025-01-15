<?php
// Database configuration
$db = new mysqli('db', 'user', 'password', 'opencart');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Update all products to use the HP demo image
$sql = "UPDATE oc_product SET image = 'catalog/demo/hp_1.jpg'";

if ($db->query($sql)) {
    echo "Successfully updated all product images!\n";
} else {
    echo "Error updating product images: " . $db->error . "\n";
}

$db->close(); 