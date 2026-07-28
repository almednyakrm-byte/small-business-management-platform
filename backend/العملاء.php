<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate and sanitize input
    $limit = isset($input['limit']) ? (int)$input['limit'] : 10;
    $offset = isset($input['offset']) ? (int)$input['offset'] : 0;

    // SQL query to select all customers
    $sql = "SELECT * FROM العملاء LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return data
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json');
    echo json_encode($customers);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : '';
    $email = isset($input['email']) ? trim($input['email']) : '';
    $phone = isset($input['phone']) ? trim($input['phone']) : '';

    // Validate input
    if (empty($name) || empty($email) || empty($phone)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // SQL query to insert new customer
    $sql = "INSERT INTO العملاء (name, email, phone) VALUES (:name, :email, :phone)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();

    // Return inserted customer data
    $customer = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM العملاء WHERE id = :id");
    $stmt->bindParam(':id', $customer, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    header('HTTP/1.1 201 Created');
    header('Content-Type: application/json');
    echo json_encode($customer);
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate and sanitize input
    $id = isset($input['id']) ? (int)$input['id'] : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $email = isset($input['email']) ? trim($input['email']) : '';
    $phone = isset($input['phone']) ? trim($input['phone']) : '';

    // Validate input
    if (empty($name) || empty($email) || empty($phone)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // SQL query to update customer
    $sql = "UPDATE العملاء SET name = :name, email = :email, phone = :phone WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();

    // Return updated customer data
    $stmt = $pdo->prepare("SELECT * FROM العملاء WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json');
    echo json_encode($customer);
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate and sanitize input
    $id = isset($input['id']) ? (int)$input['id'] : 0;

    // Validate input
    if (empty($id)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // SQL query to delete customer
    $sql = "DELETE FROM العملاء WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return success message
    header('HTTP/1.1 204 No Content');
}