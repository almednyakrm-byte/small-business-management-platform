<?php
// edit_فواتير.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_فواتير.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فواتير</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-white rounded shadow-md">
        <h2 class="text-3xl text-slate-900 font-bold mb-4">تعديل فواتير</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم الفاتورة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-slate-900 bg-gray-100 border border-slate-300 rounded">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-slate-900 text-sm font-bold mb-2">تاريخ الفاتورة</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-slate-900 bg-gray-100 border border-slate-300 rounded">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-slate-900 text-sm font-bold mb-2">مبلغ الفاتورة</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 pl-10 text-slate-900 bg-gray-100 border border-slate-300 rounded">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/فواتير.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#date').val(data.date);
                    $('#amount').val(data.amount);
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/فواتير.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_فواتير.php';
                    }
                });
            });
        });
    </script>
</body>
</html>