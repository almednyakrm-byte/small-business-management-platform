**create_فواتير.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_فواتير_form.php';

// Include footer
include 'footer.php';
?>


**create_فواتير_form.php**

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة فاتورة جديدة</h2>
        <form id="create-فواتير-form" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="اسم_العميل" class="text-slate-900 text-sm font-bold">اسم العميل</label>
                    <input type="text" id="اسم_العميل" name="اسم_العميل" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="تاريخ_الاستلام" class="text-slate-900 text-sm font-bold">تاريخ الاستلام</label>
                    <input type="date" id="تاريخ_الاستلام" name="تاريخ_الاستلام" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="مبلغ_الاستلام" class="text-slate-900 text-sm font-bold">مبلغ الاستلام</label>
                    <input type="number" id="مبلغ_الاستلام" name="مبلغ_الاستلام" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="ملاحظات" class="text-slate-900 text-sm font-bold">ملاحظات</label>
                    <textarea id="ملاحظات" name="ملاحظات" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-فواتير-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/فواتير.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_فواتير.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فواتير</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    <?php include 'content.php'; ?>
    <?php include 'footer.php'; ?>
</body>
</html>


**navigation.php**

<nav class="bg-white shadow-md p-4">
    <ul class="flex justify-between items-center">
        <li><a href="#" class="text-slate-900 text-lg font-bold">فواتير</a></li>
        <li><a href="#" class="text-slate-900 text-lg font-bold">إضافة فاتورة جديدة</a></li>
        <li><a href="#" class="text-slate-900 text-lg font-bold">قائمة الفواتير</a></li>
    </ul>
</nav>


**footer.php**

<footer class="bg-white shadow-md p-4">
    <p class="text-slate-900 text-sm font-bold">&copy; 2023</p>
</footer>


**content.php**

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <?php include 'create_فواتير_form.php'; ?>
</div>