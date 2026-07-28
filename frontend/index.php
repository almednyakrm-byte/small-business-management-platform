<!-- index.php -->
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
    <title>منصة إدارة الأعمال التجارية الصغيرة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <div class="w-64 bg-emerald-600 text-white p-4">
            <div class="text-2xl font-bold mb-4">منصة إدارة الأعمال التجارية الصغيرة</div>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
        <div class="flex-1 p-4">
            <div class="glassmorphism mb-4">
                <div class="text-2xl font-bold mb-2">مرحباً <?php echo $_SESSION['username']; ?></div>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='customers.php'">العملاء</button>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mt-2" onclick="location.href='sales.php'">المبيعات</button>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mt-2" onclick="location.href='reports.php'">التقارير والمحاسبات</button>
            </div>
            <div class="glassmorphism mb-4">
                <div class="text-2xl font-bold mb-2">إحصائيات</div>
                <div id="stats-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Stats will be fetched dynamically via JavaScript API calls -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script>
        async function fetchStats() {
            try {
                const response = await axios.get('/api/stats');
                const stats = response.data;
                const statsGrid = document.getElementById('stats-grid');
                statsGrid.innerHTML = '';
                stats.forEach((stat) => {
                    const statCard = document.createElement('div');
                    statCard.classList.add('glassmorphism', 'p-4', 'mb-4');
                    statCard.innerHTML = `
                        <div class="text-lg font-bold mb-2">${stat.title}</div>
                        <div class="text-2xl font-bold">${stat.value}</div>
                    `;
                    statsGrid.appendChild(statCard);
                });
            } catch (error) {
                console.error(error);
            }
        }

        fetchStats();
    </script>
</body>
</html>



// backend/api/stats.php
<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch stats from database
$stats = [
    ['title' => 'العملاء', 'value' => 100],
    ['title' => 'المبيعات', 'value' => 5000],
    ['title' => 'التقارير والمحاسبات', 'value' => 20],
];

// Return stats as JSON
header('Content-Type: application/json');
echo json_encode($stats);
exit;
?>


Note: This code assumes that you have a `backend/api/stats.php` file that fetches stats from the database and returns them as JSON. You'll need to modify this file to match your actual database schema and API structure. Additionally, you'll need to create a `logout.php` file to handle user logout functionality.