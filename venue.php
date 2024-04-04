<?php
// Initialize error message and success flag
$errorMsg = '';
$success = true;

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
    <?php
    include "inc/head.inc.php";
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
        </script>
    <script src="js/venue.js"></script>
    <style>
        .card-header .btn-link {
            color: inherit;
            /* Or set to your original header text color */
            text-decoration: none;
            font-weight: bold;
            /* Optional: if your original headers are bold */
        }

        .card-header .btn-link:hover,
        .card-header .btn-link:focus {
            text-decoration: none;
            color: inherit;
            /* Or a color of your choice for hover state */
            background-color: transparent;
        }

        /* Base table styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
        }

        /* Styles for booked and available slots */
        .booked,
        .available {
            padding: 10px;
            cursor: pointer;
            margin: 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .booked {
            background-color: #ffcccc;
            /* Light red for booked slots */
        }

        .available {
            background-color: #ccffcc;
            /* Light green for available slots */
        }

        /* Hover effect to indicate interactivity */
        .booked:hover,
        .available:hover {
            background-color: #dddddd;
        }

        /* Adjusts the header */
        .table th {
            background-color: #f2f2f2;
        }

        @media only screen and (max-width: 760px),
        (min-device-width: 802px) and (max-device-width: 1020px) {

            /* Force table to not be like tables anymore */
            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;

            }



            .empty {
                display: none;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }



            /*
        Label the data
        */
            td:nth-of-type(1):before {
                content: "Sunday";
            }

            td:nth-of-type(2):before {
                content: "Monday";
            }

            td:nth-of-type(3):before {
                content: "Tuesday";
            }

            td:nth-of-type(4):before {
                content: "Wednesday";
            }

            td:nth-of-type(5):before {
                content: "Thursday";
            }

            td:nth-of-type(6):before {
                content: "Friday";
            }

            td:nth-of-type(7):before {
                content: "Saturday";
            }


        }

        /* Smartphones (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            body {
                padding: 0;
                margin: 0;
            }
        }

        /* iPads (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 802px) and (max-device-width: 1020px) {
            body {
                width: 495px;
            }
        }

        @media (min-width:641px) {
            table {
                table-layout: fixed;
            }

            td {
                width: 33%;
            }
        }

        .row {
            margin-top: 20px;
        }

        .today {
            background-color: #FFFF00;
            /* Yellow background for today */
            font-weight: bold;
        }
    </style>
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
                <!-- Nav tabs -->
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
                    <?php foreach ($venues as $index => $venue): ?>
                        <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                            id="venue<?= $venue['venue_id'] ?>" role="tabpanel"
                            aria-labelledby="venue<?= $venue['venue_id'] ?>-tab">
                            <ul class="list-group list-group-flush">
                                <?php
                                $bookingsQuery = "SELECT booking_id, booking_date, timeslot_id FROM venue_bookings WHERE venue_id = ?";
                                $stmt = $conn->prepare($bookingsQuery);
                                $stmt->bind_param("i", $venue['venue_id']);
                                $stmt->execute();
                                $bookingsResult = $stmt->get_result();
                                if ($bookingsResult->num_rows > 0) {
                                    while ($booking = $bookingsResult->fetch_assoc()) {
                                        // Assume resolveTimeslot is a function you've written to convert timeslot_id to readable time slots
                                        // list($startTime, $endTime) = resolveTimeslot($booking['timeslot_id']);
                                        echo "<li class='list-group-item'>Booking ID: " . htmlspecialchars($booking['booking_id']) . " - Date: " . htmlspecialchars($booking['booking_date']) . " Time: </li>";
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
            </div>
        </div>

        <div class="row">
            <?php foreach ($venues as $venue): ?>
                <div class="col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <?= htmlspecialchars($venue['venue_name']) ?>
                        </div>
                        <div class="card-body">
                            <?php
                            // Determine the month and year to display
                            $month = isset($_SESSION['current_month']) ? $_SESSION['current_month'] : date('m');
                            $year = isset($_SESSION['current_year']) ? $_SESSION['current_year'] : date('Y');
                            // Display the calendar for this venue
                            echo build_calendar($month, $year, $venue['venue_id'], $conn);
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
                    <div class="modal-body">
                        <!-- Timeslot details will be loaded here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    // Adjust the query to fetch bookings for the given venue and month/year
    $startDate = "$year-$month-01";
    $endDate = date("Y-m-t", strtotime($startDate));
    $sql = "SELECT booking_date FROM venue_bookings WHERE venue_id = ? AND booking_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $venue_id, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        // Assuming booking_date is in 'YYYY-MM-DD' format
        $bookings[] = $row['booking_date'];
    }

    // Simulated bookings array for testing
    $bookings = array(
        date('Y-m-') . '08',
        date('Y-m-') . '15',
        date('Y-m-') . '20',
    );

    // Array holding names of days of the week
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    // Getting the first day of the month
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    // Getting the number of days in the month
    $numberDays = date('t', $firstDayOfMonth);
    // Getting some information about the first day of the month
    $dateComponents = getdate($firstDayOfMonth);
    // Month name for display
    $monthName = $dateComponents['month'];
    // Finding out the day of the week of the first day of the month
    $dayOfWeek = $dateComponents['wday'];
    // Today's date
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
        // If Sunday, start a new row
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        // Current day in 'YYYY-MM-DD' format
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        // Class for today's date
        $today = $date == $datetoday ? "today" : "";
        if (in_array($date, $bookings)) {
            // Mark as booked
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <span class='btn btn-danger btn-xs'>Booked</span></td>";
        } else {
            // Mark as available
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <button class='btn btn-success btn-xs' data-toggle='modal' data-target='#timeslotModal' data-date='$date' data-venue='$venue_id'>Book Now</button></td>";
        }

        // Increment counters
        $currentDay++;
        $dayOfWeek++;
    }

    // Complete the row of the last week in month if necessary
    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    // Returning the calendar HTML to be echoed
    return $calendar;
}
?>