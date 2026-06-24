**list_منتجات.php**

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
    <title>منتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="bg-indigo-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-white text-lg font-bold">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-white text-lg font-bold mr-2"><?= $_SESSION['username']; ?></span>
                    <a href="logout.php" class="text-white text-lg font-bold hover:text-indigo-400">تسجيل الخروج</a>
                </div>
            </nav>
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-2">قائمة المنتجات</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_منتجات.php'">إضافة منتج جديد</button>
            <div class="flex justify-between mb-4">
                <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="بحث..." id="search" onkeyup="filterList()">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_منتجات.php'">إضافة منتج جديد</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم المنتج</th>
                        <th class="px-4 py-2">وصف المنتج</th>
                        <th class="px-4 py-2">سعر المنتج</th>
                        <th class="px-4 py-2">إجراءات</th>
                    </tr>
                </thead>
                <tbody id="list">
                    <?php
                    // Fetch list records from backend
                    $response = file_get_contents('../backend/منتجات.php');
                    $data = json_decode($response, true);
                    foreach ($data as $item) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?= $item['name']; ?></td>
                            <td class="px-4 py-2"><?= $item['description']; ?></td>
                            <td class="px-4 py-2"><?= $item['price']; ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_منتجات.php?id=<?= $item['id']; ?>" class="text-indigo-500 hover:text-indigo-400">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteItem(<?= $item['id']; ?>)">حذف</button>
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
        function filterList() {
            const search = document.getElementById('search').value.toLowerCase();
            const list = document.getElementById('list');
            const rows = list.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(search)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف المنتج؟')) {
                fetch('../backend/منتجات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المنتج بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المنتج');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code uses the Tailwind CSS framework to style the layout and components. It also includes a search bar that filters the list of products in real-time. The delete button uses an AJAX request to delete the product from the backend.