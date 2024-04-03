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
if ($success) {
    $sql = "SELECT product_id, pname, price FROM product";
    $result = $conn->query($sql);
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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                            type="button" role="tab" aria-controls="all" aria-selected="true">All Venues</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="venue1-tab" data-bs-toggle="tab" data-bs-target="#venue1"
                            type="button" role="tab" aria-controls="venue1" aria-selected="false">Serangoon Chu Kang
                            Stadium</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="venue2-tab" data-bs-toggle="tab" data-bs-target="#venue2"
                            type="button" role="tab" aria-controls="venue2" aria-selected="false">Yio Hougang Sports
                            Hall</button>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content" id="bookingTabsContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <ul class="list-group list-group-flush">
                            <!-- Dynamically generate all bookings list -->
                            <?php
                            // Here, you would query your database and loop through all bookings
                            // For placeholder purposes, static entries are shown
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            Yio Hougang Sports Hall - 5th April 11:00-13:00
                            <a href='#' class='btn btn-danger btn-sm' role='button' aria-label='Cancel Booking'
                            onclick='return confirm(`Are you sure you want to cancel this booking?`);'>
                            <i class='fas fa-times'></i></a></li>";
                            // echo "<li class='list-group-item'>Booking ID: {$booking['booking_id']} - Date: {$booking['booking_date']}</li>";
                            echo "<li class='list-group-item'>All Venues - Placeholder Booking 2</li>";
                            ?>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="venue1" role="tabpanel" aria-labelledby="venue1-tab">
                        <ul class="list-group list-group-flush">
                            <!-- Dynamically generate bookings for Venue 1 -->
                            <?php
                            // Placeholder bookings for Venue 1
                            foreach ($bookings as $booking) {
                                if ($booking['venue_id'] == 1) { // Assuming $bookings is fetched with venue IDs
                                    echo "<li class='list-group-item'>Booking ID: {$booking['booking_id']} - Date: {$booking['booking_date']}</li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="venue2" role="tabpanel" aria-labelledby="venue2-tab">
                        <ul class="list-group list-group-flush">
                            <!-- Dynamically generate bookings for Venue 2 -->
                            <?php
                            // Placeholder bookings for Venue 2
                            foreach ($bookings as $booking) {
                                if ($booking['venue_id'] == 2) { // Adjust based on your data
                                    echo "<li class='list-group-item'>Booking ID: {$booking['booking_id']} - Date: {$booking['booking_date']}</li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            Serangoon Chu Kang Stadium
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <?php
                        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
                        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        echo build_calendar($month, $year);
                        ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                            aria-expanded="false" aria-controls="collapseTwo">
                            Yio Hougang Sports Hall
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <?php
                        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
                        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        echo build_calendar($month, $year);
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <?php
    include "inc/footer.inc.php";
    ?>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
        </script>
    <script defer src="js/main.js"></script>
    <?php
    // Close the database connection if it was successful
    if ($success) {
        $conn->close();
    }
    ?>
</body>

</html>

<?php
function build_calendar($month, $year)
{
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
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='venue_booking.php?date=$date' class='btn btn-success btn-xs'>Book Now</a></td>";
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