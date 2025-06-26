<?php
include 'db.php';

$movie_id = $_GET['id'] ?? null;
if (!$movie_id) die("Movie not found");

$movie = $conn->query("SELECT * FROM movies WHERE id = $movie_id")->fetch_assoc();
if (!$movie) die("Movie not found");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["seats"]) && !empty($_POST["name"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $seats = explode(",", $_POST["seats"]);
    $bookedSeats = [];

    foreach ($seats as $seat) {
        $seat = $conn->real_escape_string($seat);
        $result = $conn->query("SELECT * FROM seats WHERE seat_label = '$seat' AND is_booked = 0");
        if ($result->num_rows > 0) {
            $conn->query("UPDATE seats SET is_booked = 1 WHERE seat_label = '$seat'");
            $bookedSeats[] = $seat;
        }
    }

    if (!empty($bookedSeats)) {
        $seatString = implode(",", $bookedSeats);
        $conn->query("INSERT INTO bookings (movie_id, user_name, seats) VALUES ($movie_id, '$name', " . count($bookedSeats) . ")");
        echo "<div class='max-w-lg mx-auto mt-10 p-6 bg-green-100 text-green-800 rounded shadow'>
                <p class='mb-2 font-semibold'>✅ Booking Successful for seats: $seatString</p>
                <a href='index.php' class='text-blue-600 underline'>Back to Home</a>
              </div>";
        exit;
    } else {
        echo "<div class='max-w-lg mx-auto mt-10 p-6 bg-red-100 text-red-800 rounded shadow'>
                <p class='font-semibold'>⚠ Seats already booked or invalid selection.</p>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Seats - <?= htmlspecialchars($movie['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">🎬 <?= htmlspecialchars($movie['title']) ?></h2>
        <p class="text-gray-600 mb-6">🕒 Show Time: <?= date('d M Y, h:i A', strtotime($movie['show_time'])) ?></p>

        <form method="post" onsubmit="return confirmBooking();" class="space-y-6">
            <label class="block">
                <span class="text-gray-700 font-medium">Your Name:</span>
                <input type="text" name="name" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" autocomplete="off">
            </label>

            <div id="seat-map" class="grid grid-cols-8 gap-3 justify-center my-6">
                <?php
                $result = $conn->query("SELECT * FROM seats ORDER BY seat_label ASC");
                while ($row = $result->fetch_assoc()) {
                    $label = $row['seat_label'];
                    $class = $row['is_booked']
                        ? 'bg-red-400 text-white cursor-not-allowed opacity-60'
                        : 'bg-green-500 hover:bg-orange-400 text-white cursor-pointer';
                    echo "<div class='seat flex items-center justify-center w-10 h-10 rounded font-semibold shadow $class transition' data-seat='$label'>$label</div>";
                }
                ?>
            </div>

            <input type="hidden" name="seats" id="selected-seats">
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow transition">🎟 Book Selected Seats</button>
        </form>
    </div>

    <script>
        const selectedSeats = new Set();
        document.querySelectorAll('.seat').forEach(seat => {
            if (!seat.classList.contains('bg-red-400')) {
                seat.addEventListener('click', () => {
                    seat.classList.toggle('bg-orange-400');
                    seat.classList.toggle('bg-green-500');
                    const seatLabel = seat.getAttribute('data-seat');
                    if (selectedSeats.has(seatLabel)) {
                        selectedSeats.delete(seatLabel);
                    } else {
                        selectedSeats.add(seatLabel);
                    }
                });
            }
        });

        function confirmBooking() {
            if (selectedSeats.size === 0) {
                alert("Please select at least one seat.");
                return false;
            }
            document.getElementById("selected-seats").value = Array.from(selectedSeats).join(',');
            return true;
        }
    </script>
</body>
</html>
