**edit_منتجات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/منتجات.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#product_name').val(data.product_name);
                $('#product_description').val(data.product_description);
                $('#product_price').val(data.product_price);
            }
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

// Include Tailwind CSS and JavaScript
echo "<link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>";
echo "<script src='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js'></script>";

?>

<!-- Form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Product</h2>
    <form id="edit-product-form" method="POST" action="../backend/منتجات.php">
        <div class="mb-4">
            <label for="product_name" class="block text-sm font-medium text-slate-900">Product Name:</label>
            <input type="text" id="product_name" name="product_name" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="mb-4">
            <label for="product_description" class="block text-sm font-medium text-slate-900">Product Description:</label>
            <textarea id="product_description" name="product_description" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        </div>
        <div class="mb-4">
            <label for="product_price" class="block text-sm font-medium text-slate-900">Product Price:</label>
            <input type="number" id="product_price" name="product_price" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</div>

<!-- JavaScript code for form submission -->
<script>
    $(document).ready(function() {
        $('#edit-product-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/منتجات.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'list_'.concat('<?php echo $mod_slug; ?>').concat('.php');
                    } else {
                        alert('Error updating product');
                    }
                }
            });
        });
    });
</script>


**backend/منتجات.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    header('Location: edit_منتجات.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch product details
$product = $result->fetch_assoc();

// Update product details
if (isset($_POST['product_name']) && isset($_POST['product_description']) && isset($_POST['product_price'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_description = ?, product_price = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $product_name, $product_description, $product_price, $_GET['id']);
    $stmt->execute();

    // Return success message
    echo json_encode(array('success' => true));
} else {
    // Return product details
    echo json_encode($product);
}

// Close database connection
$conn->close();
?>