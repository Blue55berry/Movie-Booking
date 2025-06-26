<?php
include 'db.php';
$id = $_GET['id'];
$movie = $conn->query("SELECT * FROM movies WHERE id=$id")->fetch_assoc();
$booked = $conn->query("SELECT SUM(seats) as total FROM bookings WHERE movie_id=$id")->fetch_assoc()['total'] ?? 0;
$available = $movie['total_seats'] - $booked;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $seats = (int)$_POST['seats'];
    if ($seats <= $available) {
        $conn->query("INSERT INTO bookings (movie_id, name, seats) VALUES ($id, '$name', $seats)");
        echo "Booking Successful!";
    } else {
        echo "Not enough seats available.";
    }
}
?>

<h2>Book for: <?= $movie['title'] ?></h2>
<p>Available Seats: <?= $available ?></p>
<form method="post">
    Your Name: <input type="text" name="name" required><br>
    Seats: <input type="number" name="seats" min="1" max="<?= $available ?>" required><br>
    <input type="submit" value="Book Now">
</form>
