<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    $stmt = $pdo->prepare('SELECT * FROM موظفين');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Handle GET request by ID
if (isset($_GET['action']) && $_GET['action'] == 'get_by_id') {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    $stmt = $pdo->prepare('SELECT * FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
    
    // Insert data into database
    $stmt = $pdo->prepare('INSERT INTO موظفين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    
    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if ID exists in database
    $stmt = $pdo->prepare('SELECT * FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    
    // Update data in database
    $stmt = $pdo->prepare('UPDATE موظفين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    
    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if ID exists in database
    $stmt = $pdo->prepare('SELECT * FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    
    // Delete data from database
    $stmt = $pdo->prepare('DELETE FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
    exit;
}

http_response_code(400);
echo json_encode(array('error' => 'Invalid request'));
exit;

?>