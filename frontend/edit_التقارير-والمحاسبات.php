**edit_التقارير-والمحاسبات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/التقارير-والمحاسبات.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل التقارير والمحاسبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">تعديل التقارير والمحاسبات</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">عنوان التقارير والمحاسبات</label>
                <input type="text" id="title" name="title" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $record['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف التقارير والمحاسبات</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border-gray-300 rounded-md"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/التقارير-والمحاسبات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $mod_slug ?>.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/التقارير-والمحاسبات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('database.json'), true);
$record = array_filter($record, function($item) use ($id) {
    return $item['id'] == $id;
});
$record = reset($record);

// Check if record exists
if (empty($record)) {
    echo json_encode(array('error' => 'Record not found'));
    exit;
}

// Update record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    $record['title'] = $data['title'];
    $record['description'] = $data['description'];
    file_put_contents('database.json', json_encode($record));
    echo json_encode(array('success' => true, 'message' => 'Record updated successfully'));
    exit;
}

// Output record details
echo json_encode($record);


**database.json**
json
[
    {
        "id": 1,
        "title": "Title 1",
        "description": "Description 1"
    },
    {
        "id": 2,
        "title": "Title 2",
        "description": "Description 2"
    }
]


Note: Replace `database.json` with your actual database file. This code assumes a simple JSON file for demonstration purposes. In a real-world application, you would use a database like MySQL or PostgreSQL.