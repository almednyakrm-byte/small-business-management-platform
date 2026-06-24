**create_موظفين.php**

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
include 'create_موظفين_form.php';

// Include footer
include 'footer.php';
?>


**create_موظفين_form.php**

<?php
// Include form header
include 'form_header.php';
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">إضافة موظف جديد</h2>
    <form id="create-موظفين-form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div class="col-span-2">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-2">
                <label for="email" class="block text-sm font-medium text-slate-900">بريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-2">
                <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-2">
                <label for="position" class="block text-sm font-medium text-slate-900">الوظيفة</label>
                <input type="text" id="position" name="position" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
    </form>
</div>

<?php
// Include form footer
include 'form_footer.php';
?>


**form_header.php**

<div class="bg-slate-900 py-4">
    <h1 class="text-lg font-bold text-white">إضافة موظف جديد</h1>
</div>


**form_footer.php**

<script>
    $(document).ready(function() {
        $('#create-موظفين-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/موظفين.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_موظفين.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**header.php**, **navigation.php**, and **footer.php** are assumed to be existing files that include the necessary HTML structure and CSS styles for the page.