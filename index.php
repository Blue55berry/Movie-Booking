<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cineplex - Now Showing</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
      body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
      .glass-nav { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.05); }
      .movie-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(8px); transition: all 0.3s ease; }
      .movie-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.3); border-color: rgba(59, 130, 246, 0.4); }
      .gradient-text { background: linear-gradient(to right, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
      .hero-gradient { background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.2), transparent 50%), radial-gradient(circle at bottom left, rgba(236, 72, 153, 0.15), transparent 50%); }
  </style>
</head>
<body class="min-h-screen relative overflow-x-hidden">

<!-- Ambient Background -->
<div class="fixed inset-0 z-[-1] hero-gradient"></div>

<!-- ✅ Top Navigation Bar -->
<nav class="glass-nav fixed w-full top-0 z-50">
  <div class="container mx-auto px-6 py-4 flex justify-between items-center">
    <a href="index.php" class="text-3xl font-bold flex items-center gap-2 tracking-tight">
        <span class="text-blue-500">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 2h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm2 0h1V9h-1v2zm1-4V5h-1v2h1zM5 5v2H4V5h1zm0 4H4v2h1V9zm-1 4h1v2H4v-2z" clip-rule="evenodd"></path></svg>
        </span>
        <span class="gradient-text">Cineplex</span>
    </a>
    <div class="space-x-8">
      <a href="index.php" class="text-sm font-medium text-blue-400 hover:text-white transition">Now Showing</a>
      <a href="add_movie.php" class="text-sm font-medium text-gray-400 hover:text-white transition px-4 py-2 rounded-full border border-gray-700 hover:border-gray-500">Admin Portal</a>
    </div>
  </div>
</nav>

<!-- ✅ Hero Section -->
<div class="relative pt-32 pb-16 lg:pt-48 lg:pb-24 overflow-hidden">
    <div class="container mx-auto px-6 text-center relative z-10">
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight leading-tight">
            Experience Cinema <br /> <span class="gradient-text">Like Never Before</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
            Book your tickets for the latest blockbuster movies. Immersive sound, crystal-clear projection, and the best seats in the house await you.
        </p>
    </div>
</div>

<!-- ✅ Movie Cards Section -->
<div class="container mx-auto px-6 py-12 pb-24">
  <div class="flex items-center justify-between mb-12">
      <h2 class="text-3xl font-bold flex items-center gap-3">
          <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
          Now Showing
      </h2>
      <div class="text-sm text-gray-400">Showing all premium movies</div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
    <?php
    $result = $conn->query("SELECT * FROM movies ORDER BY show_time ASC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date = date('M d', strtotime($row['show_time']));
            $time = date('h:i A', strtotime($row['show_time']));
            $genre = !empty($row['genre']) ? htmlspecialchars($row['genre']) : 'Action / Drama';
            $duration = !empty($row['duration']) ? htmlspecialchars($row['duration']) . 'm' : '120m';
            $price = !empty($row['price']) ? '$' . number_format($row['price'], 2) : '$15.00';
            
            echo '<div class="movie-card rounded-2xl overflow-hidden flex flex-col relative group">';
            
            // Image Wrapper
            echo '  <div class="relative w-full aspect-[2/3] overflow-hidden">';
            echo '      <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent z-10"></div>';
            echo '      <img src="uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">';
            echo '      <div class="absolute top-4 right-4 z-20 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-yellow-400 border border-yellow-500/30 flex items-center gap-1">';
            echo '          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
            echo '          4.8';
            echo '      </div>';
            echo '  </div>';

            // Content
            echo '  <div class="p-6 pt-0 relative z-20 flex-grow flex flex-col">';
            echo '      <div class="flex items-center gap-2 text-xs font-medium text-blue-400 mb-2 uppercase tracking-wider">';
            echo '          <span>' . $genre . '</span> <span class="w-1 h-1 rounded-full bg-gray-500"></span> <span>' . $duration . '</span>';
            echo '      </div>';
            echo '      <h3 class="text-2xl font-bold text-white mb-2 leading-tight">' . htmlspecialchars($row['title']) . '</h3>';
            
            echo '      <div class="mt-4 flex items-center justify-between text-sm text-gray-300 bg-gray-800/50 p-3 rounded-lg border border-gray-700/50 mb-6">';
            echo '          <div class="flex flex-col"><span class="text-xs text-gray-500">Date</span><span class="font-semibold text-white">' . $date . '</span></div>';
            echo '          <div class="w-px h-8 bg-gray-700/50"></div>';
            echo '          <div class="flex flex-col"><span class="text-xs text-gray-500">Time</span><span class="font-semibold text-white">' . $time . '</span></div>';
            echo '      </div>';
            
            // Footer (Price & Button)
            echo '      <div class="mt-auto flex items-center justify-between">';
            echo '          <div class="flex flex-col">';
            echo '              <span class="text-xs text-gray-400">Price</span>';
            echo '              <span class="text-xl font-bold text-white">' . $price . '</span>';
            echo '          </div>';
            echo '          <a href="booking.php?id=' . $row['id'] . '" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-500/30 transition duration-300 flex items-center gap-2">';
            echo '              Book <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>';
            echo '          </a>';
            echo '      </div>';
            
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-span-full py-20 text-center">';
        echo '  <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-800 mb-6">';
        echo '      <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>';
        echo '  </div>';
        echo '  <h3 class="text-2xl font-bold text-gray-300 mb-2">No Movies Showing</h3>';
        echo '  <p class="text-gray-500 max-w-md mx-auto">Check back later for exciting new releases or use the admin panel to add some movies.</p>';
        echo '</div>';
    }
    ?>
  </div>
</div>

</body>
</html>
