document.addEventListener("DOMContentLoaded", function() {
    // Attach click event listener to menu icon
    document.getElementById("menu-icon").addEventListener("click", openNav);

    // Attach click event listener to close button
    document.getElementById("close-btn").addEventListener("click", closeNav);
});

function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
