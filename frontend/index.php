<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة إدارة الأعمال للشركات الصغيرة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-slate-900">منصة إدارة الأعمال للشركات الصغيرة</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">مرحباً</h2>
            <p class="text-gray-600">أهلاً بك في منصة إدارة الأعمال للشركات الصغيرة</p>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">إحصائيات</h2>
            <div class="stats-grid">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">عدد الموظفين</h3>
                    <p id="employee-count" class="text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">عدد المنتجات</h3>
                    <p id="product-count" class="text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">عدد الفواتير</h3>
                    <p id="invoice-count" class="text-gray-600"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">روابط سريعة</h2>
            <ul class="list-none mb-0">
                <li class="mb-2">
                    <a href="employees.php" class="text-gray-600 hover:text-gray-900">موظفين</a>
                </li>
                <li class="mb-2">
                    <a href="products.php" class="text-gray-600 hover:text-gray-900">منتجات</a>
                </li>
                <li class="mb-2">
                    <a href="invoices.php" class="text-gray-600 hover:text-gray-900">فواتير</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('employee-count').textContent = data.employeeCount;
                document.getElementById('product-count').textContent = data.productCount;
                document.getElementById('invoice-count').textContent = data.invoiceCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes you have a PHP backend with an API endpoint at `/api/stats` that returns a JSON object with the employee count, product count, and invoice count. You'll need to replace this with your actual API endpoint and data structure.

Also, this code uses the `fetch` API to make a GET request to the API endpoint. If you're using an older browser that doesn't support the `fetch` API, you may need to use a library like Axios or jQuery to make the request.

Finally, this code uses the `session_start()` function to start a session, but it doesn't check if the session is already started. If you're using a framework like Laravel, you may need to modify this code to use the framework's session management system.