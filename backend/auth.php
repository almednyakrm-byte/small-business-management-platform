<?php
// Start the session to handle user authentication
session_start();

// Import the database connection file
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user's session data
        $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
        echo json_encode($response);
    } else {
        // User is not logged in, return a not logged in status
        $response = array('status' => 'not_logged_in');
        echo json_encode($response);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST requests for login and registration
    if (isset($_POST['action'])) {
        // Check the action type (login or register)
        if ($_POST['action'] === 'login') {
            // Login action
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Prepare the username and password for the database query
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Securely check the input fields
                if (empty($username) || empty($password)) {
                    // Input fields are empty, return an error
                    $response = array('status' => 'error', 'message' => 'Username and password are required');
                    echo json_encode($response);
                } else {
                    // Prepare a statement to query the user's data
                    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the user exists
                    if ($result->num_rows > 0) {
                        // User exists, fetch the user's data
                        $user = $result->fetch_assoc();

                        // Verify the password using password_verify()
                        if (password_verify($password, $user['password'])) {
                            // Password is correct, start a new session
                            $_SESSION['user_id'] = $user['id'];
                            $response = array('status' => 'logged_in', 'user_id' => $user['id']);
                            echo json_encode($response);
                        } else {
                            // Password is incorrect, return an error
                            $response = array('status' => 'error', 'message' => 'Invalid username or password');
                            echo json_encode($response);
                        }
                    } else {
                        // User does not exist, return an error
                        $response = array('status' => 'error', 'message' => 'Invalid username or password');
                        echo json_encode($response);
                    }
                }
            } else {
                // Username or password field is missing, return an error
                $response = array('status' => 'error', 'message' => 'Username and password are required');
                echo json_encode($response);
            }
        } elseif ($_POST['action'] === 'register') {
            // Register action
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Prepare the username, email, and password for the database query
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Securely check the input fields
                if (empty($username) || empty($email) || empty($password)) {
                    // Input fields are empty, return an error
                    $response = array('status' => 'error', 'message' => 'Username, email, and password are required');
                    echo json_encode($response);
                } else {
                    // Prepare a statement to query if the username or email already exists
                    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
                    $stmt->bind_param('ss', $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the username or email already exists
                    if ($result->num_rows > 0) {
                        // Username or email already exists, return an error
                        $response = array('status' => 'error', 'message' => 'Username or email already exists');
                        echo json_encode($response);
                    } else {
                        // Username and email are available, prepare a statement to insert the new user
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                        $stmt->bind_param('sss', $username, $email, $hashed_password);
                        $stmt->execute();

                        // Start a new session for the newly registered user
                        $user_id = $conn->insert_id;
                        $_SESSION['user_id'] = $user_id;
                        $response = array('status' => 'registered', 'user_id' => $user_id);
                        echo json_encode($response);
                    }
                }
            } else {
                // Username, email, or password field is missing, return an error
                $response = array('status' => 'error', 'message' => 'Username, email, and password are required');
                echo json_encode($response);
            }
        } elseif ($_POST['action'] === 'logout') {
            // Logout action
            // Unset the user's session data
            unset($_SESSION['user_id']);
            $response = array('status' => 'logged_out');
            echo json_encode($response);
        }
    }
} else {
    // Invalid request method, return an error
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}