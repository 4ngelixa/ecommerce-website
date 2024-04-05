<?php
// Initialize error message and success flag
$errorMsg = '';
$success = true;

session_start(); // Ensure session start is at the top

// Check if the month/year is being changed through navigation
if (isset($_GET['month']) && isset($_GET['year'])) {
    $_SESSION['calendar_month'] = $_GET['month'];
    $_SESSION['calendar_year'] = $_GET['year'];
} else {
    // Initialize to current month/year if not already set
    if (!isset($_SESSION['calendar_month'])) {
        $_SESSION['calendar_month'] = date('m');
    }
    if (!isset($_SESSION['calendar_year'])) {
        $_SESSION['calendar_year'] = date('Y');
    }
}

$month = $_SESSION['calendar_month'];
$year = $_SESSION['calendar_year'];

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div aria-live="polite" aria-atomic="true" style="position: relative; z-index: 1050; min-height: 0px;">
        <div id="toastContainer" style="position: fixed; top: 1rem; right: 1rem;"></div>
    </div>

    <!-- Background image container with overlay text -->
    <header>
        <div class="header-image">
            <h1 class="overlay-text">✧ Bling Bling Badminton Halls ✧</h1>
        </div>
    </header>
    <main class="container">
        <div class="intro-field">
            <div class="intro-text">
                <p>At Bling Bling Badminton, we offer free usage of our facilities and badminton halls.</p>
                <p>Badminton is meant to be enjoyed!</p>
                <p>Our courts are open from 10 AM to 6 PM daily. Please book a badminton hall in advance!</p>
                <p>Edit your bookings or book a venue from here!</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                Your Bookings
            </div>
            <div class="card-body">
                <?php if (isset($memberId) && !empty($memberId)): ?>
                    <ul class="nav nav-tabs justify-content-center" id="bookingTabs" role="tablist">
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
                                    $todayDate = date('Y-m-d');

                                    // Adjust your bookings query
                                    $bookingsQuery = "SELECT booking_id, booking_date, timeslot_id FROM venue_bookings 
                                    WHERE venue_id = ? AND member_id = ? AND booking_date >= ? 
                                    ORDER BY booking_date ASC, timeslot_id ASC";
                                    // Prepare the statement
                                    $stmt = $conn->prepare($bookingsQuery);

                                    // Bind parameters, including today's date to filter out past bookings
                                    $stmt->bind_param("iis", $venue['venue_id'], $memberId, $todayDate);
                                    $stmt->execute();
                                    $bookingsResult = $stmt->get_result();
                                    if ($bookingsResult->num_rows > 0) {
                                        while ($booking = $bookingsResult->fetch_assoc()) {
                                            $bookingDate = new DateTime($booking['booking_date']);
                                            $formattedDate = $bookingDate->format('F j');
                                            $timeslotText = $timeslotMapping[$booking['timeslot_id']] ?? "Unknown Time";
                                            $displayText = "{$formattedDate} {$timeslotText} | Booking ID {$booking['booking_id']}";

                                            // Wrap in a div with d-flex and justify-content-between classes for alignment
                                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" .
                                                htmlspecialchars($displayText) .
                                                "<button class='btn btn-danger btn-sm delete-booking' data-booking-id='{$booking['booking_id']}'><i class='fas fa-times' aria-hidden='true'></i></button>" .
                                                "</li>";
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
            <div class="card-img-top"></div>
            <div class="card-header">
                Venues
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
                            <i class="fas fa-times" aria-hidden="true"></i>
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

    <?php if (isset($_SESSION['toast'])): ?>
        <script>         $(document).ready(function () {
                var toastHTML = '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">' + '<div class="toast-header">' + '<strong class="me-auto">Notification</strong>' + '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' + '</div>' + '<div class="toast-body">' + '<?php echo $_SESSION['toast']['message']; ?>' + '</div>' + '</div>';
                $('#toastContainer').append(toastHTML); $('.toast').toast('show');
            });

        </script>
        <?php
        // Clear the session variable after use
        unset($_SESSION['toast']);
    endif;
    ?>
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
    // Retrieve the month and year from the session, or use the current month and year as defaults
    $month = isset($_SESSION['calendar_month']) ? $_SESSION['calendar_month'] : date('m');
    $year = isset($_SESSION['calendar_year']) ? $_SESSION['calendar_year'] : date('Y');

    $totalTimeslots = 8; // Assuming there are 8 timeslots available per day.
    $today = date('Y-m-d');
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


    // Starting the HTML table for the calendar
    // $calendar = "<table class='table table-bordered'>";
    // Adding navigation for previous, current, and next months

    $prev_month = date('m', mktime(0, 0, 0, $month - 1, 1, $year));
    $prev_year = date('Y', mktime(0, 0, 0, $month - 1, 1, $year));
    $next_month = date('m', mktime(0, 0, 0, $month + 1, 1, $year));
    $next_year = date('Y', mktime(0, 0, 0, $month + 1, 1, $year));


    $calendar = "<div class='calendar-navigation' style='display: flex; justify-content: center; align-items: center; gap: 10px; margin: 10px 0;'>";
    $calendar .= "<a class='btn btn-xs btn-primary' title='Previous Month' href='?month={$prev_month}&year={$prev_year}'><i class='fa fa-arrow-left'></i></a>";

    // Month and year in the center, bigger and bold
    $calendar .= "<span class='current-month-year' style='font-size: 20px; font-weight: bold;'>{$monthName} {$year}</span>";

    $calendar .= "<a class='btn btn-xs btn-primary' title='Next Month' href='?month={$next_month}&year={$next_year}'><i class='fa fa-arrow-right'></i></a>";
    $calendar .= "</div>";

    $calendar .= "<table class='table table-bordered'>";
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
        $todayClass = ($date == $today) ? "today" : "";
        $isPast = ($date < $today) ? true : false; // Check if the date is before today

        $calendar .= "<td class='$todayClass'><h2>$currentDay</h2>";

        if ($isPast) {
            // For dates in the past, you can either not display a button, or display a disabled button
            $calendar .= "<button class='btn btn-secondary btn-xs' disabled>Past Date</button>";
        } else {
            // For future dates (including today), check if they are fully booked or not
            if (isset($bookingsByDate[$date]) && $bookingsByDate[$date] >= $totalTimeslots) {
                $calendar .= "<span class='btn btn-danger btn-xs'>Booked Out</span>";
            } else {
                $calendar .= "<button class='btn btn-success btn-xs' data-toggle='modal' data-target='#timeslotModal' data-date='$date' data-venue='$venue_id'>Book Now</button>";
            }
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