<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$user_role = $_SESSION['role'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    // SQL query to select all products
    $sql = 'SELECT * FROM products ORDER BY id LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($products);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    if (!isset($data['name']) || !isset($data['price']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query to insert new product
    $sql = 'INSERT INTO products (name, price, description) VALUES (:name, :price, :description)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $stmt->bindParam(':price', $data['price'], PDO::PARAM_INT);
    $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert product']);
        exit;
    }

    // Return inserted product
    $product = $pdo->query('SELECT * FROM products WHERE id = LAST_INSERT_ID()')->fetch(PDO::FETCH_ASSOC);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($product);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['price']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query to update product
    $sql = 'UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $stmt->bindParam(':price', $data['price'], PDO::PARAM_INT);
    $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update product']);
        exit;
    }

    // Return updated product
    $product = $pdo->query('SELECT * FROM products WHERE id = :id', array(':id' => $data['id']))->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($product);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query to delete product
    $sql = 'DELETE FROM products WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete product']);
        exit;
    }

    // Return success message
    http_response_code(204);
    echo json_encode(['message' => 'Product deleted successfully']);
    exit;
}