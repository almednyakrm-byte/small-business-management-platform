**list_المبيعات.php**

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
    <title>المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
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
            background-color: #2c3e50;
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .btn {
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز البيانات</span>
        <span class="text-lg font-bold">المبيعات</span>
        <span class="text-lg font-bold">حسناً</span>
        <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold">المبيعات</h1>
            <button class="btn" onclick="location.href='create_المبيعات.php'">إضافة جديد</button>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="btn" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم المبيعات</th>
                    <th>تاريخ المبيعات</th>
                    <th>قيمة المبيعات</th>
                    <th>حالة المبيعات</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/المبيعات.php');
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.date}</td>
                    <td>${record.value}</td>
                    <td>${record.status}</td>
                    <td>
                        <a href="edit_المبيعات.php?id=${record.id}" class="text-lg font-bold">تعديل</a>
                        <button class="text-lg font-bold" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/المبيعات.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach((record) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.date}</td>
                                <td>${record.value}</td>
                                <td>${record.status}</td>
                                <td>
                                    <a href="edit_المبيعات.php?id=${record.id}" class="text-lg font-bold">تعديل</a>
                                    <button class="text-lg font-bold" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                const response = await fetch('../backend/المبيعات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>


**backend/المبيعات.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $query = "SELECT * FROM المبيعات WHERE CONCAT(id, date, value, status) LIKE '%$searchValue%'";
} else {
    $query = "SELECT * FROM المبيعات";
}

// Fetch records
$result = $conn->query($query);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output records
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM المبيعات WHERE id = '$id'";
    if ($conn->query($query)) {
        echo json_encode(array('message' => 'Record deleted successfully'));
    } else {
        echo json_encode(array('message' => 'Error deleting record'));
    }
}

// Close connection
$conn->close();
?>