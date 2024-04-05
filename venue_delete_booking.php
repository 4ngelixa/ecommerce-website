<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die('You must be logged in to perform this action.');
}

$memberId = $_SESSION['id'];
$bookingId = $_GET['booking_id'] ?? null;

$config = parse_ini_file('/var/www/private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($bookingId) {
    // Ensure the booking belongs to the session user to prevent unauthorized deletions
    $stmt = $conn->prepare("DELETE FROM venue_bookings WHERE booking_id = ? AND member_id = ?");
    $stmt->bind_param("ii", $bookingId, $memberId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Booking deleted successfully.'
        ];
        header("Location: venue.php?");
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Unable to delete booking or booking does not exist.'
        ];
        header("Location: venue.php?");
    }
} else {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'No booking ID provided..'
    ];
    header("Location: venue.php?");
}

$conn->close();
