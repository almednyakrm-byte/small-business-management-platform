**create_التقارير-والمحاسبات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Include form script
include 'create_form_script.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة تقرير ومحاسبة جديدة</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                        عنوان التقرير والمحاسبة
                    </label>
                    <input class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="title" type="text" placeholder="عنوان التقرير والمحاسبة">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                        وصف التقرير والمحاسبة
                    </label>
                    <textarea class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" rows="4" placeholder="وصف التقرير والمحاسبة"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date">
                        تاريخ التقرير والمحاسبة
                    </label>
                    <input class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="date" type="date" placeholder="تاريخ التقرير والمحاسبة">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="file">
                        ملف التقرير والمحاسبة
                    </label>
                    <input class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="file" type="file" placeholder="ملف التقرير والمحاسبة">
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ التقرير والمحاسبة</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>


**create_form_script.php**

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/التقارير-والمحاسبات.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_التقارير-والمحاسبات.php';
                    } else {
                        alert('Error: ' + data);
                    }
                }
            });
        });
    });
</script>


**backend/التقارير-والمحاسبات.php**

<?php
// Include database connection
include 'db_connection.php';

// Check if form data is submitted
if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['file'])) {
    // Insert data into database
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $file = $_FILES['file'];

    $query = "INSERT INTO التقارير_المحاسبات (title, description, date, file) VALUES ('$title', '$description', '$date', '$file')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>