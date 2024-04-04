<?php
// Create database connection
$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    $errorMsg = "Failed to read database config file.";
    $success = false;
} else {
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    }
}

if (!$success) {
    echo json_encode(['error' => $errorMsg]);
    exit;
}

// Get date and venue_id from the query string
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$venueId = isset($_GET['venue_id']) ? (int)$_GET['venue_id'] : 0;

// Define all possible timeslots (adjust according to your business logic)
$allTimeslots = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
$bookedTimeslots = [];

// Query to fetch booked timeslots for the given date and venue
$query = "SELECT timeslot_id FROM venue_bookings WHERE venue_id = ? AND booking_date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $venueId, $date);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $bookedTimeslots[] = $row['timeslot_id']; // Assuming timeslot_id is stored directly
}

// Determine available timeslots by filtering out booked times
$availableTimeslots = array_diff($allTimeslots, $bookedTimeslots);

// Close connection
$stmt->close();
$conn->close();

// Return available timeslots as JSON
echo json_encode(array_values($availableTimeslots));
