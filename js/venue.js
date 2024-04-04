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

document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll("button[data-date][data-venue]").forEach(button => {
        button.addEventListener('click', function () {
            loadTimeslots(this);
        });
    });
});

function loadTimeslots(buttonElement) {
    // Clear previous selections if necessary
    const selectedTimeslotsInput = document.getElementById('selectedTimeslots');
    if (selectedTimeslotsInput) {
        selectedTimeslotsInput.value = '';
    }
    const timeslotButtons = document.querySelectorAll('.timeslot-btn');
    timeslotButtons.forEach(button => {
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-primary');
    });

    const date = buttonElement.getAttribute('data-date');
    const venueId = buttonElement.getAttribute('data-venue');
    document.querySelector('#venueId').value = venueId; // Set the hidden input's value


    // Fetch new timeslots and update the modal content
    fetch(`venue_fetch_timeslots.php?date=${date}&venue_id=${venueId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                const modalBody = document.querySelector('#timeslotModal .modal-body');
                if (modalBody) {
                    modalBody.innerHTML = data.html;
                }
            }
        })
        .catch(error => console.error("Fetch error:", error));
}

function selectTimeslot(timeslotId, element) {
    element.classList.toggle('btn-outline-primary');
    element.classList.toggle('btn-success');

    // Update the hidden input value
    let selectedTimeslots = new Set(document.getElementById('selectedTimeslots').value.split(',').filter(Boolean));
    if (selectedTimeslots.has(timeslotId)) {
        selectedTimeslots.delete(timeslotId);
    } else {
        selectedTimeslots.add(timeslotId);
    }
    document.getElementById('selectedTimeslots').value = Array.from(selectedTimeslots).join(',');
}

// Function to handle form submission
function bookTimeslots(event) {
    event.preventDefault(); // Prevent the default form submission

    let selectedTimeslots = document.getElementById('selectedTimeslots').value;
    let venueId = document.getElementById('venueId').value; // Retrieve venueId from the hidden input

    console.log('Selected Timeslots:', selectedTimeslots);
    console.log('Venue ID:', venueId);

    // Here, implement your booking logic
    // For instance, an AJAX call to a PHP script that processes the booking
    // Make sure to replace the URL and adjust the data structure as needed
    fetch('process_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            venueId: venueId,
            selectedTimeslots: selectedTimeslots.split(',')
        }),
    })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            // Handle success response
            // For example, display a confirmation message or close the modal
            let timeslotModal = bootstrap.Modal.getInstance(document.getElementById('timeslotModal'));
            timeslotModal.hide();
        })
        .catch((error) => {
            console.error('Error:', error);
            // Handle error response
            // For example, display an error message to the user
        });
}

// Event listener for timeslot button clicks
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelector('#timeslotModal').addEventListener('click', function (e) {
        if (e.target.classList.contains('timeslot-btn')) {
            selectTimeslot(e.target.getAttribute('data-timeslot-id'), e.target);
        }
    });
});
