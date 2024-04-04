// // $(document).ready(function () {
// //     // Function to fetch the calendar view based on user actions
// //     function fetchCalendar(month, year, venueId) {
// //         $.ajax({
// //             url: 'build_venue.php', // Adjust according to your actual script's location
// //             type: 'POST',
// //             data: { month: month, year: year, venue_id: venueId },
// //             success: function (response) {
// //                 $('#calendarContainer').html(response); // Ensure you have this container in your HTML
// //             },
// //             error: function (error) {
// //                 console.error("Error fetching calendar: ", error);
// //             }
// //         });
// //     }

// //     // Example of dynamically changing the month and fetching the updated calendar
// //     $('#prevMonthBtn').on('click', function () {
// //         // Calculate previous month and year here
// //         fetchCalendar(newMonth, newYear, venueId); // Ensure you define these variables accordingly
// //     });

// //     $('#nextMonthBtn').on('click', function () {
// //         // Calculate next month and year here
// //         fetchCalendar(newMonth, newYear, venueId);
// //     });

// //     // Initial fetch for the current month and year
// //     var currentMonth = $('#currentMonth').val();
// //     var currentYear = $('#currentYear').val();
// //     var venueId = 1; // Example venueId, adjust based on your application logic
// //     fetchCalendar(currentMonth, currentYear, venueId);
// // });

// function loadTimeslots(date) {
//     // Example: Populate modal with a message (replace with AJAX call to fetch timeslots)
//     var modalBody = document.querySelector('#timeslotModal .modal-body');
//     modalBody.innerHTML = 'Loading timeslots for ' + date + '...';

//     // Example AJAX call (uncomment and modify URL and success function as needed)
//     $.ajax({
//         url: 'venue_timeslots.php', // Your endpoint to fetch timeslots
//         type: 'GET',
//         data: { date: date },
//         success: function (data) {
//             // Populate modal with returned data
//             modalBody.innerHTML = data;
//         }
//     });
// };
