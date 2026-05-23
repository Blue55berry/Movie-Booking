<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Add Movie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-40 -left-40 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-40 left-20 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="w-full max-w-2xl glass rounded-2xl shadow-2xl p-8 sm:p-10 relative z-10">
        <div class="flex justify-between items-center mb-8 border-b border-gray-700 pb-4">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">🎬 Add New Movie</h2>
            <a href="index.php" class="text-gray-400 hover:text-white transition">Back to Home</a>
        </div>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $genre = $conn->real_escape_string($_POST['genre']);
            $duration = (int)$_POST['duration'];
            $price = (float)$_POST['price'];
            $show_time = $_POST['show_time'];
            $total_seats = (int)$_POST['total_seats'];

            $image_name = time() . '_' . $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            move_uploaded_file($tmp_name, "uploads/" . $image_name);

            $sql = "INSERT INTO movies (title, description, genre, duration, price, image, show_time, total_seats) 
                    VALUES ('$title', '$description', '$genre', $duration, $price, '$image_name', '$show_time', $total_seats)";
                    
            if($conn->query($sql)) {
                echo "<div class='mb-6 p-4 rounded-lg bg-green-900/50 border border-green-500 text-green-300 flex items-center gap-3'>
                        <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>
                        <span>Movie <strong>$title</strong> added successfully!</span>
                      </div>";
            } else {
                echo "<div class='mb-6 p-4 rounded-lg bg-red-900/50 border border-red-500 text-red-300'>Error: " . $conn->error . "</div>";
            }
        }
        ?>

        <form method="post" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Movie Title</label>
                    <input type="text" name="title" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition placeholder-gray-500" placeholder="e.g. Inception">
                </div>
                
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition placeholder-gray-500" placeholder="Brief synopsis of the movie..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Genre</label>
                    <input type="text" name="genre" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition placeholder-gray-500" placeholder="e.g. Sci-Fi, Action">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Duration (mins)</label>
                    <input type="number" name="duration" min="1" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition" placeholder="120">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ticket Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition" placeholder="15.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Show Time</label>
                    <input type="datetime-local" name="show_time" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition" style="color-scheme: dark;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Total Seats</label>
                    <input type="number" name="total_seats" min="1" value="40" required class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Poster Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-lg hover:border-blue-500 transition cursor-pointer bg-gray-800/30" onclick="document.getElementById('file-upload').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-400 justify-center">
                                <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-400 hover:text-blue-300 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="image" type="file" class="sr-only" accept="image/*" required onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                </span>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name">PNG, JPG, GIF up to 5MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-gray-900 transition-all transform hover:scale-[1.02]">
                    Publish Movie
                </button>
            </div>
        </form>
    </div>
</body>
</html>
