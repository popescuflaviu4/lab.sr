<?php
// Database configuration
$db = new mysqli('db', 'user', 'password', 'opencart');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Clean up existing products
$db->query("DELETE FROM oc_product");
$db->query("DELETE FROM oc_product_description");
$db->query("DELETE FROM oc_product_to_store");
$db->query("ALTER TABLE oc_product AUTO_INCREMENT = 1");

// Read CSV file
$file = fopen('Laptops.csv', 'r');
$headers = fgetcsv($file);

// Skip header row
while (($data = fgetcsv($file)) !== FALSE) {
    $product_id = (int)$data[0];
    $brand = $db->real_escape_string($data[1]);
    $model = $db->real_escape_string($data[2]);
    $price = (float)str_replace(['₹', ','], '', $data[9]);

    // Skip first product (product_id 0)
    if ($product_id === 0) {
        continue;
    }
    
    // Insert into oc_product
    $sql = "INSERT INTO oc_product SET 
            product_id = {$product_id},
            model = '{$brand} {$model}',
            master_id = 0,
            sku = '',
            upc = '',
            ean = '',
            jan = '',
            isbn = '',
            mpn = '',
            location = '',
            quantity = 100,
            minimum = 1,
            subtract = 1,
            stock_status_id = 6,
            date_added = NOW(),
            date_modified = NOW(),
            date_available = NOW(),
            status = 1,
            tax_class_id = 0,
            manufacturer_id = 0,
            shipping = 1,
            price = {$price},
            points = 0,
            weight = 0,
            weight_class_id = 0,
            length = 0,
            width = 0,
            height = 0,
            length_class_id = 0,
            sort_order = 0,
            rating = 0,
            image = 'catalog/no_image.png'";
    
    // Try to insert, skip if fails
    if (!$db->query($sql)) {
        continue;
    }
    
    // Insert into oc_product_description
    $name = $db->real_escape_string("{$brand} {$model}");
    $description = $db->real_escape_string(
        "Processor: {$data[3]}\n" .
        "Operating System: {$data[4]}\n" .
        "Storage: {$data[5]}\n" .
        "RAM: {$data[6]}\n" .
        "Screen Size: {$data[7]}\n" .
        "Touch Screen: {$data[8]}"
    );
    
    $sql = "INSERT INTO oc_product_description SET 
            product_id = {$product_id},
            language_id = 1,
            name = '{$name}',
            description = '{$description}',
            tag = '',
            meta_title = '{$name}',
            meta_description = '',
            meta_keyword = ''";
    
    $db->query($sql);
    
    // Link product to default store
    $sql = "INSERT INTO oc_product_to_store SET 
            product_id = {$product_id},
            store_id = 0";
    
    $db->query($sql);
}

fclose($file);
$db->close();

echo "Import completed successfully!\n"; 