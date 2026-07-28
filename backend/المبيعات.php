<?php
require_once 'db.php';

// Get user role and authentication
$userRole = $_SESSION['userRole'];
if (!$userRole) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = isset($inputData['limit']) ? intval($inputData['limit']) : 10;
    $offset = isset($inputData['offset']) ? intval($inputData['offset']) : 0;

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM المبيعات ORDER BY id LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $description = isset($inputData['description']) ? trim($inputData['description']) : '';
    $price = isset($inputData['price']) ? floatval($inputData['price']) : 0;

    // Check admin role for insert
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO المبيعات (name, description, price) VALUES (:name, :description, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price, PDO::PARAM_FLOAT);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $id = isset($inputData['id']) ? intval($inputData['id']) : 0;
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $description = isset($inputData['description']) ? trim($inputData['description']) : '';
    $price = isset($inputData['price']) ? floatval($inputData['price']) : 0;

    // Check admin role for update
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE المبيعات SET name = :name, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price, PDO::PARAM_FLOAT);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = isset($inputData['id']) ? intval($inputData['id']) : 0;

    // Check admin role for delete
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM المبيعات WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}