<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Add Movie</h2>
        <form method="post" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Title</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Show Time</label>
                <input type="datetime-local" name="show_time" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Total Seats</label>
                <input type="number" name="total_seats" min="1" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Image</label>
                <input type="file" name="image" accept="image/*" required class="w-full text-gray-700">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition font-semibold">Add Movie</button>
        </form>
        <button onclick="window.history.back();" class="mt-4 w-full bg-gray-300 text-gray-800 py-2 rounded hover:bg-gray-400 transition font-semibold">Back</button>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'];
            $show_time = $_POST['show_time'];
            $total_seats = $_POST['total_seats'];

            $image_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmp_name, "uploads/" . $image_name);

            $conn->query("INSERT INTO movies (title, image, show_time, total_seats) 
                          VALUES ('$title', '$image_name', '$show_time', $total_seats)");
            echo "<p class='mt-4 text-green-600 font-semibold text-center'>✅ Movie added successfully!</p>";
        }
        ?>
    </div>
</body>
</html>
