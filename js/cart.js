// Wait for the DOM content to be loaded
document.addEventListener("DOMContentLoaded", function() {
    // Get the element with the empty-cart-image class
    const emptyCartImage = document.querySelector('.empty-cart-image');

    // Add event listener for mouseover event
    emptyCartImage.addEventListener('mouseover', function() {
        // Add the hover class on mouseover
        emptyCartImage.classList.add('hover');
    });

    // Add event listener for mouseout event
    emptyCartImage.addEventListener('mouseout', function() {
        // Remove the hover class on mouseout
        emptyCartImage.classList.remove('hover');
    });
});
