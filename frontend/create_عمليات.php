**create_عمليات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $status = trim($_POST['status']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($date) || empty($status)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO عمليات (name, description, date, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $description, $date, $status);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_عمليات.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New Record</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="text-slate-900 block mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="text-slate-900 block mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="text-slate-900 block mb-2">Date:</label>
            <input type="date" id="date" name="date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="status" class="text-slate-900 block mb-2">Status:</label>
            <select id="status" name="status" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                <option value="">Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Record</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/عمليات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_عمليات.php';
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/عمليات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['status'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $status = trim($_POST['status']);

    $sql = "INSERT INTO عمليات (name, description, date, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $description, $date, $status);
    $stmt->execute();
    $stmt->close();

    // Return success response
    echo json_encode(array('success' => true));
} else {
    // Return error response
    echo json_encode(array('error' => 'Invalid request'));
}