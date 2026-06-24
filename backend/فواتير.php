<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to check if user is logged in
function isLoggedIn() {
    // Implement your own logic to check if user is logged in
    // For demonstration purposes, assume a logged-in user
    return true;
}

// Function to check if user is admin
function isAdmin() {
    // Implement your own logic to check if user is admin
    // For demonstration purposes, assume an admin user
    return true;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    // SQL query structure: Select all or by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM فواتير WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM فواتير');
        $stmt->execute();
        $result = $stmt->fetchAll();
    }

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    // Read input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $requiredFields = ['name', 'amount'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // SQL query structure: Insert
    $stmt = $pdo->prepare('INSERT INTO فواتير (name, amount) VALUES (:name, :amount)');
    $stmt->execute([
        ':name' => filter_var($input['name'], FILTER_SANITIZE_STRING),
        ':amount' => filter_var($input['amount'], FILTER_VALIDATE_FLOAT),
    ]);

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    // Read input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid id']);
        exit;
    }

    $requiredFields = ['name', 'amount'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // SQL query structure: Update
    $stmt = $pdo->prepare('UPDATE فواتير SET name = :name, amount = :amount WHERE id = :id');
    $stmt->execute([
        ':id' => $id,
        ':name' => filter_var($input['name'], FILTER_SANITIZE_STRING),
        ':amount' => filter_var($input['amount'], FILTER_VALIDATE_FLOAT),
    ]);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    // Read input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid id']);
        exit;
    }

    // SQL query structure: Delete
    $stmt = $pdo->prepare('DELETE FROM فواتير WHERE id = :id');
    $stmt->execute([':id' => $id]);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}

// Handle other requests
else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}