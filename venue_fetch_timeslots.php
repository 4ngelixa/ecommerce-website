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

// Fetch the venue name and initialize an array to hold booked timeslots
$venueName = '';
$bookedTimeslots = [];
if ($venueId) {
    $venueQuery = "SELECT venue_name FROM venue WHERE venue_id = ?";
    $stmt = $conn->prepare($venueQuery);
    $stmt->bind_param("i", $venueId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $venueName = $row['venue_name'];
    }
    $stmt->close();

    // Fetch already booked timeslots for the venue and date
    $bookedQuery = "SELECT timeslot_id FROM venue_bookings WHERE venue_id = ? AND booking_date = ?";
    $stmt = $conn->prepare($bookedQuery);
    $stmt->bind_param("is", $venueId, $date);
    $stmt->execute();
    $bookedResult = $stmt->get_result();
    while ($bookedRow = $bookedResult->fetch_assoc()) {
        $bookedTimeslots[] = $bookedRow['timeslot_id'];
    }
    $stmt->close();
}

// Include venue name and date in the response
$htmlContent .= "<div><strong>Venue: " . htmlspecialchars($venueName) . "</strong></div>";
$htmlContent .= "<div><strong>Date: " . htmlspecialchars($date) . "</strong></div><br>";

// Start a flex container
$htmlContent .= "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";

$counter = 0;
$timeslotsQuery = "SELECT timeslot_id, start_time, end_time FROM venue_timeslots";
$timeslotsResult = $conn->query($timeslotsQuery);

while ($row = $timeslotsResult->fetch_assoc()) {
    $timeslotId = $row['timeslot_id'];
    // Check if the timeslot is booked
    $isBooked = in_array($timeslotId, $bookedTimeslots);

    // Conditionally disable the button or change its class if the timeslot is booked
    $buttonClass = $isBooked ? 'btn-danger' : 'btn-outline-primary';
    $disabledAttribute = $isBooked ? 'disabled' : '';

    // Add timeslot button
    $htmlContent .= "<button type='button' class='btn $buttonClass timeslot-btn' style='flex: 1 0 21%;' data-timeslot-id='{$timeslotId}' data-date='{$date}' data-venue-id='{$venueId}' $disabledAttribute>{$row['start_time']} to {$row['end_time']}</button>";
    $counter++;
}

$htmlContent .= "</div>"; // Close the flex container

if (empty($venueName) || $counter === 0) {
    echo json_encode(['error' => 'No available timeslots or venue not found.']);
} else {
    echo json_encode(['error' => null, 'html' => $htmlContent]);
}
