<?php

require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get the request body
$body = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($method === 'GET') {
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare('SELECT * FROM عمليات');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rows);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if ($method === 'POST') {
    try {
        // Validate the input
        if (!isset($body['name']) || !isset($body['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize the input
        $name = htmlspecialchars($body['name']);
        $description = htmlspecialchars($body['description']);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('INSERT INTO عمليات (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return the result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Operation created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if ($method === 'PUT') {
    try {
        // Validate the input
        if (!isset($body['id']) || !isset($body['name']) || !isset($body['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize the input
        $id = intval($body['id']);
        $name = htmlspecialchars($body['name']);
        $description = htmlspecialchars($body['description']);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('UPDATE عمليات SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Operation updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    try {
        // Validate the input
        if (!isset($body['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize the input
        $id = intval($body['id']);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('DELETE FROM عمليات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Operation deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}