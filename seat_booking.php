<?php
// db connection
$conn = new mysqli("localhost", "root", "", "movie_booking");
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["seats"])) {
    $seats = explode(",", $_POST["seats"]);
    foreach ($seats as $seat) {
        $seat = $conn->real_escape_string($seat);
        $conn->query("UPDATE seats SET is_booked = 1 WHERE seat_label = '$seat' AND is_booked = 0");
    }
    echo "<p style='color:green'>Booking Successful!</p><a href='seat_booking.php'>Back to seat selection</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Seat Booking</title>
    <style>
        .seat {
            width: 40px; height: 40px;
            margin: 5px;
            background-color: green;
            color: white;
            display: inline-block;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
            border-radius: 5px;
        }
        .booked {
            background-color: red;
            cursor: not-allowed;
        }
        .selected {
            background-color: orange;
        }
    </style>
</head>
<body>
    <h2>Select Seats</h2>

    <form method="post" onsubmit="return confirmBooking();">
        <div id="seat-map">
            <?php
            $result = $conn->query("SELECT * FROM seats ORDER BY seat_label ASC");
            while ($row = $result->fetch_assoc()) {
                $label = $row['seat_label'];
                $class = $row['is_booked'] ? 'seat booked' : 'seat';
                echo "<div class='$class' data-seat='$label'>$label</div>";
            }
            ?>
        </div>
        <input type="hidden" name="seats" id="selected-seats">
        <br><br>
        <button type="submit">Book Selected Seats</button>
    </form>

    <script>
        const selectedSeats = new Set();
        document.querySelectorAll('.seat').forEach(seat => {
            if (!seat.classList.contains('booked')) {
                seat.addEventListener('click', () => {
                    seat.classList.toggle('selected');
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
