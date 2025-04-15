<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$uploadDir = 'images/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = basename($_FILES['image']['name']);
    $destination = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        $newProduct = [
            "id" => intval($_POST['id']),
            "name" => $_POST['name'],
            "price" => intval($_POST['price']),
            "image" => $destination,
            "code" => $_POST['code'],
            "category" => $_POST['category'],
            "description" => $_POST['description'],
        ];

        // Optional size fields
        $sizeFields = ['shape', 'Width', 'height', 'side', 'side1', 'side2', 'Diameter'];
        foreach ($sizeFields as $field) {
            if (!empty($_POST[$field])) {
                $newProduct[$field] = $_POST[$field];
            }
        }

        // Load + update products.json
        $jsonFile = 'products.json';
        $products = [];

        if (file_exists($jsonFile)) {
            $products = json_decode(file_get_contents($jsonFile), true);
        }

        $products[] = $newProduct;
        file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));

        echo "<h3>✅ Product added successfully!</h3>";
        echo "<a href='admin.html'>Add another product</a>";
    } else {
        echo "❌ Failed to save image!";
    }
} else {
    echo "❌ No image uploaded or error occurred.";
}
?>
