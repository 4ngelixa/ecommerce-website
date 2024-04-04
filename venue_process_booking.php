<?php
header('Content-Type: application/json');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Member not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (is_null($data)) {
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

$memberId = $_SESSION['id'];
$config = parse_ini_file('/var/www/private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$venueId = $data['venueId'];
$timeslots = $data['selectedTimeslots'];
$bookingDate = $data['bookingDate']; // This should be a date in 'YYYY-MM-DD' format.

$errors = []; // To hold any booking errors

foreach ($timeslots as $timeslotId) {
    // Correct the parameter order and types for the checkQuery
    $checkQuery = "SELECT COUNT(*) AS count FROM venue_bookings WHERE venue_id = ? AND timeslot_id = ? AND booking_date = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("iis", $venueId, $timeslotId, $bookingDate); // Correct order and types
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result()->fetch_assoc();
    
    if ($checkResult['count'] > 0) {
        // Timeslot is already booked
        $errors[] = "Timeslot $timeslotId on $bookingDate is already booked.";
        continue;
    }

    // Proceed to insert booking if the timeslot is available
    $stmt = $conn->prepare("INSERT INTO venue_bookings (venue_id, booking_date, timeslot_id, member_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $venueId, $bookingDate, $timeslotId, $memberId); // Ensure correct data types
    
    if (!$stmt->execute()) {
        $errors[] = "Booking failed for timeslot $timeslotId on $bookingDate: " . $stmt->error;
    }
}

if (!empty($errors)) {
    echo json_encode(['error' => $errors]);
} else {
    echo json_encode(['success' => 'Booking successful']);
}
