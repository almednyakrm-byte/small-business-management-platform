**list_فواتير.php**

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
    <title>فواتير</title>
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
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
        }
        .header .nav {
            float: right;
        }
        .header .nav a {
            color: #ffffff;
            text-decoration: none;
            margin-left: 1rem;
        }
        .header .nav a:hover {
            color: #ffffff;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1f2937;
            color: #ffffff;
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .btn {
            background-color: #1f2937;
            color: #ffffff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #1f2937;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="logo">فواتير</h1>
        <div class="nav">
            <a href="index.php">الصفحة الرئيسية</a>
            <a href="profile.php"><?= $_SESSION['username']; ?></a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">قائمة الفواتير</h2>
        <div class="flex justify-between mb-4">
            <button class="btn" onclick="location.href='create_فواتير.php'">إضافة فاتورة جديدة</button>
            <div class="search-bar">
                <input type="search" id="search" placeholder="بحث...">
                <button class="btn" onclick="searchRecords()">بحث</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>تاريخ الفاتورة</th>
                    <th>المبلغ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/فواتير.php'), true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['invoice_number']; ?></td>
                        <td><?= $record['invoice_date']; ?></td>
                        <td><?= $record['amount']; ?></td>
                        <td>
                            <a href="edit_فواتير.php?id=<?= $record['id']; ?>" class="btn">تعديل</a>
                            <button class="btn" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
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
            const search = document.getElementById('search').value;
            fetch('../backend/فواتير.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.invoice_number}</td>
                            <td>${record.invoice_date}</td>
                            <td>${record.amount}</td>
                            <td>
                                <a href="edit_فواتير.php?id=${record.id}" class="btn">تعديل</a>
                                <button class="btn" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف الفاتورة؟')) {
                fetch('../backend/فواتير.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الفاتورة بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الفاتورة');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/فواتير.php**

<?php
// Fetch records from database
$records = array();
// Simulating data for demonstration purposes
$records[] = array(
    'id' => 1,
    'invoice_number' => 'INV001',
    'invoice_date' => '2022-01-01',
    'amount' => 100.00
);
$records[] = array(
    'id' => 2,
    'invoice_number' => 'INV002',
    'invoice_date' => '2022-01-15',
    'amount' => 200.00
);
$records[] = array(
    'id' => 3,
    'invoice_number' => 'INV003',
    'invoice_date' => '2022-02-01',
    'amount' => 300.00
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['invoice_number'], $search) !== false || strpos($record['invoice_date'], $search) !== false || strpos($record['amount'], $search) !== false;
    });
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode($records);