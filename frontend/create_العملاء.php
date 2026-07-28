**create_العملاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة عميل جديد</h2>
        <form id="create-client-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">اسم العميل</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">العنوان</label>
                <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-client-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/العملاء.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_العملاء.php';
                    } else {
                        alert('Error adding client');
                    }
                }
            });
        });
    });
</script>

**Note:** This code assumes you have jQuery and a backend PHP script (`../backend/العملاء.php`) to handle the form submission. The backend script should return 'success' if the client is added successfully, and an error message otherwise.