<?php
header('Content-Type: application/json'); // Ensure proper content-type for JSON responses

$config = parse_ini_file('/var/www/private/db-config.ini');
$success = true; // Initialize success flag
$errorMsg = '';

if (!$config) {
    $errorMsg = "Failed to read database config file.";
    $success = false;
} else {
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    }
}

if (!$success) {
    echo json_encode(['error' => $errorMsg]);
    exit;
}

$date = $_GET['date'] ?? '';
$venueId = $_GET['venue_id'] ?? '';
$htmlContent = '';

if ($date && $venueId) {
    $bookedSlotsQuery = "SELECT timeslot_id FROM venue_bookings WHERE booking_date = ? AND venue_id = ?";
    $stmt = $conn->prepare($bookedSlotsQuery);
    $stmt->bind_param("si", $date, $venueId);
    $stmt->execute();
    $bookedResult = $stmt->get_result();
    $bookedSlots = [];
    while ($row = $bookedResult->fetch_assoc()) {
        $bookedSlots[] = $row['timeslot_id'];
    }
    $stmt->close();

    // Fetch all timeslots and filter out the booked ones
    $timeslotsQuery = "SELECT timeslot_id, start_time, end_time FROM venue_timeslots";
    $timeslotsResult = $conn->query($timeslotsQuery);

    while ($row = $timeslotsResult->fetch_assoc()) {
        if (!in_array($row['timeslot_id'], $bookedSlots)) {
            $htmlContent .= "<div>Time: " . $row['start_time'] . " - " . $row['end_time'] . " <button class='book-timeslot'>Book</button></div>";
        }
    }
}

// Check if HTML content is generated; if not, indicate no available timeslots
if (empty($htmlContent)) {
    echo json_encode(['error' => 'No available timeslots.']);
} else {
    // Respond with HTML content wrapped in a JSON object for consistency
    echo json_encode(['error' => null, 'html' => $htmlContent]);
}