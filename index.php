<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Now Showing</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- ✅ Top Navigation Bar -->
<nav class="bg-white shadow-md">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <a href="index.php" class="text-2xl font-bold text-blue-600">🎬 MovieTickets</a>
    <div class="space-x-6">
      <a href="index.php" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
      
      <a href="add_movie.php" class="text-gray-700 hover:text-blue-600 font-medium">Admin</a>
    </div>
  </div>
</nav>

<!-- ✅ Movie Cards Section -->
<div class="container mx-auto py-10">
  <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">🎬 Now Showing</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-center">
    <?php
    $result = $conn->query("SELECT * FROM movies");
    while ($row = $result->fetch_assoc()) {
        echo '<div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col items-center p-6 min-h-[400px]">';
        echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '" class="w-full h-56 object-cover rounded-xl mb-6 mt-2">';
        echo '<h3 class="text-xl font-bold text-gray-800 mb-2 w-full text-left">' . htmlspecialchars($row['title']) . '</h3>';
        echo '<p class="text-gray-600 mb-6 w-full text-left">Show Time: <span class="font-medium text-gray-900">' . date('d M Y, h:i A', strtotime($row['show_time'])) . '</span></p>';
        echo '<a href="book.php?id=' . $row['id'] . '" class="mt-auto w-full inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200 text-center">Book Tickets</a>';
        echo '</div>';
    }
    ?>
  </div>
</div>

</body>
</html>
