document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM content loaded"); // Check if this line appears in the console

    // Add event listeners for mouseover and mouseout events
    const emptyCartImage = document.querySelector('.empty-cart-image');
    emptyCartImage.addEventListener('mouseover', function() {
        emptyCartImage.style.backgroundImage = "url('images/addtocart.jpg')";
    });
    emptyCartImage.addEventListener('mouseout', function() {
        emptyCartImage.style.backgroundImage = "url('images/emptycart.jpg')";
    });

    // Get all remove buttons
    var removeButtons = document.querySelectorAll(".remove-button");

    // Add click event listener to each remove button
    removeButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            console.log("Remove button clicked"); // Check if this line appears in the console
            
            // Prevent the default action of the button
            event.preventDefault();

            // Get the product ID from the button's data attribute
            var productId = button.getAttribute("data-product-id");

            // Show a confirmation prompt
            var confirmation = confirm("Are you sure you want to remove this item from the cart?");

            // If the user confirms, proceed with removing the item
            if (confirmation) {
                // Set the product ID in a hidden input field
                document.querySelector('input[name="remove"]').value = productId;

                // Submit the form to remove the item from the cart
                button.closest('form').submit();
            }
        });
    });
});
