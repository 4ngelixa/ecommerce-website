<?php
// Initialize error message and success flag
$errorMsg = '';
$success = true;

session_start(); // Ensure session start is at the top


// Retrieve member_id from the session
$memberId = $_SESSION['id'];

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

// Proceed with database operations if successful
session_start(); // Start the session at the very top of the script

// Fetch venues
$venuesQuery = "SELECT venue_id, venue_name FROM venue";
$venuesResult = $conn->query($venuesQuery);
$venues = [];
if ($venuesResult) {
    while ($row = $venuesResult->fetch_assoc()) {
        $venues[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bling Bling Venues</title>
    <?php include "inc/head.inc.php"; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <script src="js/venue.js"></script>
    <link rel="stylesheet" href="css/venue.css">
</head>

<body>
    <?php
    include "inc/nav.inc.php";
    ?>

    <main class="container">
        <h1 class="product-catalog-title">✧ Bling Bling Badminton Halls ✧</h1>
        <p>At Bling Bling Badminton, we offer free usage of our facilities and badminton halls. Please place a booking
            on the badminton hall to be used! Our courts are open from 10 AM to 6 PM daily.</p>
        <div class="card mb-3">
            <div class="card-header">
                Bookings Overview
            </div>
            <div class="card-body">
                <?php if (isset($memberId) && !empty($memberId)): ?>
                    <ul class="nav nav-tabs" id="bookingTabs" role="tablist">
                        <?php foreach ($venues as $index => $venue): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                                    id="venue<?= $venue['venue_id'] ?>-tab" data-bs-toggle="tab"
                                    data-bs-target="#venue<?= $venue['venue_id'] ?>" type="button" role="tab"
                                    aria-controls="venue<?= $venue['venue_id'] ?>"
                                    aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                                    <?= htmlspecialchars($venue['venue_name']) ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content" id="bookingTabsContent">
                        <?php
                        // Example timeslot mapping
                        $timeslotMapping = [
                            1 => "10AM to 11AM",
                            2 => "11AM to 12PM",
                            3 => "12PM to 1PM",
                            4 => "1PM to 2PM",
                            5 => "2PM to 3PM",
                            6 => "3PM to 4PM",
                            7 => "4PM to 5PM",
                            8 => "5PM to 6PM"
                        ];

                        foreach ($venues as $index => $venue): ?>
                            <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                                id="venue<?= $venue['venue_id'] ?>" role="tabpanel"
                                aria-labelledby="venue<?= $venue['venue_id'] ?>-tab">
                                <ul class="list-group list-group-flush scroll">
                                    <?php
                                    $bookingsQuery = "SELECT booking_id, booking_date, timeslot_id FROM venue_bookings WHERE venue_id = ? AND member_id = ? ORDER BY booking_date ASC, timeslot_id ASC";
                                    $stmt = $conn->prepare($bookingsQuery);
                                    $stmt->bind_param("ii", $venue['venue_id'], $memberId);
                                    $stmt->execute();
                                    $bookingsResult = $stmt->get_result();
                                    if ($bookingsResult->num_rows > 0) {
                                        while ($booking = $bookingsResult->fetch_assoc()) {
                                            // Format the booking date
                                            $bookingDate = new DateTime($booking['booking_date']);
                                            $formattedDate = $bookingDate->format('F j');
                                            // Get the timeslot text
                                            $timeslotText = $timeslotMapping[$booking['timeslot_id']] ?? "Unknown Time";
                                            // Construct the display text
                                            $displayText = "{$formattedDate} {$timeslotText} | Booking ID {$booking['booking_id']}";
                                            echo "<li class='list-group-item'>" . htmlspecialchars($displayText) . "</li>";
                                        }
                                    } else {
                                        echo "<li class='list-group-item'>No bookings available</li>";
                                    }
                                    $stmt->close();
                                    ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- User is not logged in, display a message instead -->
                    <p>Please Login to view your current bookings.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                Calendar View
            </div>
            <div class="card-body">
                <!-- Nav tabs for venues -->
                <ul class="nav nav-tabs" id="calendarTabs" role="tablist">
                    <?php foreach ($venues as $index => $venue): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                                id="calendarTab<?= $venue['venue_id'] ?>-tab" data-bs-toggle="tab"
                                data-bs-target="#calendarTab<?= $venue['venue_id'] ?>" type="button" role="tab"
                                aria-controls="calendarTab<?= $venue['venue_id'] ?>"
                                aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                                <?= htmlspecialchars($venue['venue_name']) ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Tab content for each venue's calendar -->
                <div class="tab-content" id="calendarTabsContent">
                    <?php foreach ($venues as $index => $venue): ?>
                        <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                            id="calendarTab<?= $venue['venue_id'] ?>" role="tabpanel"
                            aria-labelledby="calendarTab<?= $venue['venue_id'] ?>-tab">
                            <?php
                            // Assuming current month and year are set or retrieved from somewhere
                            $month = date('m');
                            $year = date('Y');
                            echo build_calendar($month, $year, $venue['venue_id'], $conn);
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Timeslot Modal -->
        <div class="modal fade" id="timeslotModal" tabindex="-1" role="dialog" aria-labelledby="timeslotModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="timeslotModalLabel">Available Timeslots</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div id="timeslotSelection" class="modal-body">
                        <!-- Timeslot details will be loaded here dynamically -->
                    </div>

                    <div class="modal-footer">
                        <form id="timeslotForm" onsubmit="bookTimeslots(event)">
                            <input type="hidden" id="selectedTimeslots" name="selectedTimeslots">
                            <input type="hidden" id="venueId" name="venueId">
                            <input type="hidden" id="selectedDate" name="selectedDate">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <?php if (isset($_SESSION['id']) && !empty($_SESSION['id'])): ?>
                                <button type="submit" class="btn btn-primary">Book</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary" disabled>Please log in to book</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <?php
    include "inc/footer.inc.php";
    ?>


    <?php
    // Close the database connection if it was successful
    if ($success) {
        $conn->close();
    }
    ?>
</body>

</html>

<?php
function build_calendar($month, $year, $venue_id, $conn)
{
    $totalTimeslots = 8; // Assuming there are 8 timeslots available per day.

    $startDate = "$year-$month-01";
    $endDate = date("Y-m-t", strtotime($startDate));
    $bookingsQuery = "SELECT booking_date, COUNT(*) as booking_count 
                      FROM venue_bookings 
                      WHERE venue_id = ? AND booking_date BETWEEN ? AND ? 
                      GROUP BY booking_date";

    $stmt = $conn->prepare($bookingsQuery);
    $stmt->bind_param("iss", $venue_id, $startDate, $endDate);
    $stmt->execute();
    $bookingsResult = $stmt->get_result();

    $bookingsByDate = [];
    while ($row = $bookingsResult->fetch_assoc()) {
        $bookingsByDate[$row['booking_date']] = $row['booking_count'];
    }

    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $datetoday = date('Y-m-d');

    // Starting the HTML table for the calendar
    $calendar = "<table class='table table-bordered'>";
    // Adding navigation for previous, current, and next months
    $calendar .= "<center><h2>$monthName $year</h2>";
    $calendar .= "<a class='btn btn-xs btn-success' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= " <a class='btn btn-xs btn-danger' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br>";

    // Days header
    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }
    $calendar .= "</tr><tr>";

    // Filling in the days of the week up to the first day of the month
    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $date = "$year-$month-" . str_pad($currentDay, 2, '0', STR_PAD_LEFT);
        $todayClass = ($date == $datetoday) ? "today" : "";

        $calendar .= "<td class='$todayClass'><h4>$currentDay</h4>";

        if (isset($bookingsByDate[$date]) && $bookingsByDate[$date] >= $totalTimeslots) {
            $calendar .= "<span class='btn btn-danger btn-xs'>Booked</span>";
        } else {
            $calendar .= "<button class='btn btn-success btn-xs' data-toggle='modal' data-target='#timeslotModal' data-date='$date' data-venue='$venue_id'>Book Now</button>";
        }

        $calendar .= "</td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr></table>";
    return $calendar;
}
?>