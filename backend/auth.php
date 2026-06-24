<?php

// Start the session to store user data
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            // Get the user data
            $user_data = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user_data['password'])) {
                // If the password is correct, log the user in
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];
                $response = array(
                    'status' => 'logged_in',
                    'user_id' => $user_data['id'],
                    'username' => $user_data['username']
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                // If the password is incorrect, send an error response
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid password'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            // If the user does not exist, send an error response
            $response = array(
                'status' => 'error',
                'message' => 'Invalid username or password'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    } else {
        // If the username or password is missing, send an error response
        $response = array(
            'status' => 'error',
            'message' => 'Missing username or password'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Handle the registration request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are already taken
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username and email are already taken
        if ($result->num_rows > 0) {
            // If the username or email is already taken, send an error response
            $response = array(
                'status' => 'error',
                'message' => 'Username or email already taken'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the user
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        // Send a JSON response with the user's details
        $user_id = $mysqli->insert_id;
        $response = array(
            'status' => 'registered',
            'user_id' => $user_id,
            'username' => $username
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // If the username, email, or password is missing, send an error response
        $response = array(
            'status' => 'error',
            'message' => 'Missing username, email, or password'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If no action is specified, send an error response
$response = array(
    'status' => 'error',
    'message' => 'Invalid action'
);
header('Content-Type: application/json');
echo json_encode($response);
exit;