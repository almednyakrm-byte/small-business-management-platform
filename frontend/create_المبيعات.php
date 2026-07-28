<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'المبيعات';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<main class="md:flex flex-wrap justify-center p-4">
    <div class="md:w-1/2 xl:w-1/3 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-emerald-600 text-2xl font-bold mb-4">Create <?php echo $mod_slug; ?></h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="date" class="block text-gray-700 font-bold mb-2">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-emerald-600">
            </div>
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 font-bold mb-2">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-emerald-600">
            </div>
            <div class="mb-4">
                <label for="product" class="block text-gray-700 font-bold mb-2">Product</label>
                <input type="text" id="product" name="product" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-emerald-600">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 font-bold mb-2">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-emerald-600">
            </div>
            <div class="mb-4">
                <label for="total" class="block text-gray-700 font-bold mb-2">Total</label>
                <input type="number" id="total" name="total" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-emerald-600">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?php echo $mod_slug; ?>.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>