**create_منتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    if (!empty($name) && !empty($description) && !empty($price) && !empty($quantity)) {
        // Insert data into database
        $sql = "INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $price, $quantity);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_منتجات.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create product form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create Product</h2>
    <form id="create-product-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Product</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/منتجات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_منتجات.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>


**backend/منتجات.php**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['quantity'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $name, $description, $price, $quantity);
    $stmt->execute();

    // Return success message
    echo json_encode(array('success' => true));
} else {
    // Return error message
    echo json_encode(array('error' => 'Invalid request'));
}