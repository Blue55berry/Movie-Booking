<?php
include 'db.php';

$movie_id = $_GET['id'] ?? null;
if (!$movie_id) die("<h1 style='color:white;text-align:center;margin-top:20%'>Movie not found</h1>");

$movie = $conn->query("SELECT * FROM movies WHERE id = $movie_id")->fetch_assoc();
if (!$movie) die("<h1 style='color:white;text-align:center;margin-top:20%'>Movie not found</h1>");

$price_per_ticket = !empty($movie['price']) ? (float)$movie['price'] : 15.00;

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
        $seatString = implode(", ", $bookedSeats);
        $total_price = count($bookedSeats) * $price_per_ticket;
        $conn->query("INSERT INTO bookings (movie_id, user_name, seats) VALUES ($movie_id, '$name', " . count($bookedSeats) . ")");
        $success = true;
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Seats - <?= htmlspecialchars($movie['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass-nav { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .glass-panel { background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); }
        
        .seat { transition: all 0.2s ease; }
        .seat:not(.booked):hover { transform: scale(1.1); box-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
        .seat.selected { background-color: #3b82f6; border-color: #60a5fa; box-shadow: 0 0 15px rgba(59, 130, 246, 0.6); }
        .seat.booked { background-color: #334155; border-color: #1e293b; color: #64748b; cursor: not-allowed; }
        
        /* Curved screen effect */
        .screen { 
            height: 60px; width: 100%; 
            background: linear-gradient(to bottom, rgba(255,255,255,0.2) 0%, transparent 100%); 
            border-top: 4px solid #fff; 
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
            box-shadow: 0 10px 40px -10px rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="min-h-screen relative pb-20">

<!-- Navigation -->
<nav class="glass-nav fixed w-full top-0 z-50">
  <div class="container mx-auto px-6 py-4 flex justify-between items-center">
    <a href="index.php" class="text-2xl font-bold flex items-center gap-2 tracking-tight">
        <span class="text-blue-500">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"></path></svg>
        </span>
        <span class="text-white hover:text-blue-400 transition">Back to Movies</span>
    </a>
  </div>
</nav>

<!-- Movie Backdrop -->
<div class="absolute top-0 left-0 w-full h-[50vh] z-[-1] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-[#0f172a]/80 via-[#0f172a]/90 to-[#0f172a] z-10"></div>
    <img src="uploads/<?= htmlspecialchars($movie['image']) ?>" class="w-full h-full object-cover opacity-30 filter blur-sm transform scale-110">
</div>

<div class="container mx-auto px-4 pt-32 lg:pt-40 flex flex-col lg:flex-row gap-10">
    
    <!-- Left: Movie Details & Checkout Form -->
    <div class="w-full lg:w-1/3 space-y-8">
        <?php if(isset($success)): ?>
            <div class="glass-panel p-8 rounded-2xl border-l-4 border-green-500 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-24 h-24 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-green-400 mb-2 relative z-10">Tickets Confirmed!</h3>
                <p class="text-gray-300 relative z-10">You've successfully booked <strong><?= count($bookedSeats) ?></strong> seat(s).</p>
                <div class="mt-4 p-4 bg-gray-900/50 rounded-lg relative z-10">
                    <div class="text-sm text-gray-400">Seats</div>
                    <div class="text-lg font-bold text-white"><?= htmlspecialchars($seatString) ?></div>
                </div>
                <div class="mt-6 flex justify-between items-end relative z-10">
                    <div>
                        <div class="text-sm text-gray-400">Total Paid</div>
                        <div class="text-2xl font-bold text-white">$<?= number_format($total_price, 2) ?></div>
                    </div>
                    <a href="index.php" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition">Home</a>
                </div>
            </div>
        <?php elseif(isset($error)): ?>
            <div class="glass-panel p-6 rounded-2xl border-l-4 border-red-500">
                <h3 class="text-xl font-bold text-red-400 mb-2">Booking Failed</h3>
                <p class="text-gray-300">Some of the selected seats were already booked or invalid. Please try again.</p>
            </div>
        <?php endif; ?>

        <?php if(!isset($success)): ?>
        <div class="glass-panel p-6 sm:p-8 rounded-2xl">
            <div class="flex gap-6 mb-8">
                <img src="uploads/<?= htmlspecialchars($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="w-24 h-36 object-cover rounded-xl shadow-lg border border-gray-700/50">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2 leading-tight"><?= htmlspecialchars($movie['title']) ?></h2>
                    <p class="text-blue-400 font-medium text-sm mb-4"><?= !empty($movie['genre']) ? htmlspecialchars($movie['genre']) : 'Action' ?> • <?= !empty($movie['duration']) ? htmlspecialchars($movie['duration']) . 'm' : '120m' ?></p>
                    <div class="flex items-center gap-2 text-sm text-gray-300 bg-gray-800/60 inline-flex px-3 py-1.5 rounded-lg border border-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?= date('M d, h:i A', strtotime($movie['show_time'])) ?>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700/50 pt-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-400">Ticket Price</span>
                    <span class="text-xl font-bold text-white">₹<span id="ticket-price"><?= number_format($price_per_ticket, 2) ?></span></span>
                </div>
                
                <form method="post" onsubmit="return confirmBooking();" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 bg-gray-800/80 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition placeholder-gray-500" placeholder="John Doe">
                    </div>

                    <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-400">Selected Seats (<span id="seat-count">0</span>)</span>
                            <span class="text-white font-medium" id="selected-seat-labels">-</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-700/50">
                            <span class="text-lg font-medium text-gray-300">Total</span>
                            <span class="text-2xl font-bold text-blue-400">₹<span id="total-price">0.00</span></span>
                        </div>
                    </div>

                    <input type="hidden" name="seats" id="selected-seats-input">
                    <button type="submit" id="book-btn" disabled class="w-full py-4 px-6 bg-blue-600 hover:bg-blue-500 disabled:bg-gray-700 disabled:text-gray-500 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition duration-300 flex justify-center items-center gap-2">
                        Checkout Securely
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Seat Map -->
    <div class="w-full lg:w-2/3">
        <div class="glass-panel p-8 sm:p-12 rounded-3xl flex flex-col items-center">
            
            <!-- Screen -->
            <div class="w-full max-w-xl mb-16 relative">
                <div class="text-center text-gray-500 text-sm font-medium tracking-[0.3em] uppercase mb-2">Cinema Screen</div>
                <div class="screen"></div>
            </div>

            <!-- Seat Legend -->
            <div class="flex gap-6 mb-12">
                <div class="flex items-center gap-2"><div class="w-5 h-5 rounded bg-gray-700 border border-gray-600"></div><span class="text-sm text-gray-400">Available</span></div>
                <div class="flex items-center gap-2"><div class="w-5 h-5 rounded bg-blue-500 border border-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div><span class="text-sm text-gray-400">Selected</span></div>
                <div class="flex items-center gap-2"><div class="w-5 h-5 rounded bg-gray-800 border border-gray-900 flex items-center justify-center"><svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></div><span class="text-sm text-gray-400">Booked</span></div>
            </div>

            <!-- Seat Grid -->
            <div class="grid grid-cols-8 gap-3 sm:gap-4 justify-center">
                <?php
                // Get all seats
                $result = $conn->query("SELECT * FROM seats ORDER BY seat_label ASC");
                while ($row = $result->fetch_assoc()) {
                    $label = $row['seat_label'];
                    $isBooked = $row['is_booked'];
                    
                    if ($isBooked) {
                        echo "<div class='seat booked w-10 h-10 sm:w-12 sm:h-12 rounded-t-xl rounded-b-md flex items-center justify-center text-xs font-bold border' title='$label (Booked)'>
                                <svg class='w-5 h-5 opacity-40' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z' clip-rule='evenodd'></path></svg>
                              </div>";
                    } else {
                        echo "<div class='seat w-10 h-10 sm:w-12 sm:h-12 rounded-t-xl rounded-b-md flex items-center justify-center text-xs font-bold text-gray-300 bg-gray-700 border border-gray-600 cursor-pointer hover:bg-gray-600' data-seat='$label' title='$label'>
                                $label
                              </div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    const selectedSeats = new Set();
    const pricePerTicket = <?= $price_per_ticket ?>;
    
    // DOM Elements
    const countEl = document.getElementById('seat-count');
    const labelsEl = document.getElementById('selected-seat-labels');
    const totalEl = document.getElementById('total-price');
    const inputEl = document.getElementById('selected-seats-input');
    const btnEl = document.getElementById('book-btn');

    document.querySelectorAll('.seat:not(.booked)').forEach(seat => {
        seat.addEventListener('click', () => {
            seat.classList.toggle('selected');
            const seatLabel = seat.getAttribute('data-seat');
            
            // Toggle classes for visual selected state
            if (seat.classList.contains('selected')) {
                seat.classList.replace('bg-gray-700', 'bg-blue-500');
                seat.classList.replace('border-gray-600', 'border-blue-400');
                seat.classList.replace('text-gray-300', 'text-white');
                selectedSeats.add(seatLabel);
            } else {
                seat.classList.replace('bg-blue-500', 'bg-gray-700');
                seat.classList.replace('border-blue-400', 'border-gray-600');
                seat.classList.replace('text-white', 'text-gray-300');
                selectedSeats.delete(seatLabel);
            }
            
            updateCart();
        });
    });

    function updateCart() {
        const count = selectedSeats.size;
        countEl.innerText = count;
        
        if (count > 0) {
            labelsEl.innerText = Array.from(selectedSeats).join(', ');
            totalEl.innerText = (count * pricePerTicket).toFixed(2);
            inputEl.value = Array.from(selectedSeats).join(',');
            btnEl.disabled = false;
        } else {
            labelsEl.innerText = '-';
            totalEl.innerText = '0.00';
            inputEl.value = '';
            btnEl.disabled = true;
        }
    }

    function confirmBooking() {
        if (selectedSeats.size === 0) {
            alert("Please select at least one seat.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>
