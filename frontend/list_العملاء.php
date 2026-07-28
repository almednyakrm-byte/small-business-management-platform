**list_العملاء.php**

<?php
// Session validation
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
    <title>العملاء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .bg-emerald-600 {
            background-color: #0d6efd;
        }
        .text-teal-500 {
            color: #0fc2c9;
        }
    </style>
</head>
<body class="bg-emerald-600">
    <div class="container mx-auto p-4 mt-4">
        <div class="flex justify-between items-center">
            <a href="index.php" class="text-teal-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-white mr-2">مرحباً, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-white hover:text-teal-500">تسجيل خروج</a>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-4">قائمة العملاء</h2>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_العملاء.php'">إضافة جديد</button>
            <div class="flex justify-between items-center mt-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم العميل</th>
                        <th class="px-4 py-2">تليفون</th>
                        <th class="px-4 py-2">إجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <?php
                    // Fetch records from backend
                    $response = file_get_contents('../backend/العملاء.php');
                    $records = json_decode($response, true);
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo $record['اسم العميل']; ?></td>
                            <td class="px-4 py-2"><?php echo $record['تليفون']; ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_العملاء.php?id=<?php echo $record['id']; ?>" class="text-teal-500 hover:text-white">تعديل</a>
                                <button class="text-red-500 hover:text-white" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/العملاء.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(records => {
                        const recordsElement = document.getElementById('records');
                        recordsElement.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${record['اسم العميل']}</td>
                                <td class="px-4 py-2">${record['تليفون']}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_العملاء.php?id=${record['id']}" class="text-teal-500 hover:text-white">تعديل</a>
                                    <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record['id']})">حذف</button>
                                </td>
                            `;
                            recordsElement.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/العملاء.php')
                    .then(response => response.json())
                    .then(records => {
                        const recordsElement = document.getElementById('records');
                        recordsElement.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${record['اسم العميل']}</td>
                                <td class="px-4 py-2">${record['تليفون']}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_العملاء.php?id=${record['id']}" class="text-teal-500 hover:text-white">تعديل</a>
                                    <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record['id']})">حذف</button>
                                </td>
                            `;
                            recordsElement.appendChild(row);
                        });
                    });
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/العملاء.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/العملاء.php`) that returns a JSON array of records. The `deleteRecord` function sends a DELETE request to the backend to delete the record with the specified ID.