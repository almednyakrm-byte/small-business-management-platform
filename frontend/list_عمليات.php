**list_عمليات.php**

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
    <title>عمليات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1f2937;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">عمليات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_عمليات.php'">إضافة عنصر جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العنصر</th>
                    <th>الإجراء</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record[' العنصر']; ?></td>
                        <td>
                            <a href="edit_عمليات.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        </td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/عمليات.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record[' العنصر']}</td>
                            <td>
                                <a href="edit_عمليات.php?id=${record['id']}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            </td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            } else {
                fetchRecords();
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف العنصر؟')) {
                fetch('../backend/عمليات.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                });
            }
        }

        function fetchRecords() {
            fetch('../backend/عمليات.php', { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                const recordsTable = document.getElementById('records-table');
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record[' العنصر']}</td>
                        <td>
                            <a href="edit_عمليات.php?id=${record['id']}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        </td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            });
        }

        fetchRecords();
    </script>
</body>
</html>


**backend/عمليات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, ' العنصر' => 'العنصر الأول');
$records[] = array('id' => 2, ' العنصر' => 'العنصر الثاني');
$records[] = array('id' => 3, ' العنصر' => 'العنصر الثالث');

// Search records
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchQuery) {
        return strpos($record[' العنصر'], $searchQuery) !== false;
    });
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
}

// Output records
header('Content-Type: application/json');
echo json_encode($records);