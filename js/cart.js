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

    // Add event listener to remove buttons
    var removeButtons = document.querySelectorAll('.remove-button');
    removeButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            var confirmRemove = confirm("Are you sure you want to remove the item?");
            if (confirmRemove) {
                // Proceed with removing the item from the cart
                var productId = this.getAttribute('data-product-id');
                var form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                var inputProductId = document.createElement('input');
                inputProductId.type = 'hidden';
                inputProductId.name = 'remove';
                inputProductId.value = productId;
                form.appendChild(inputProductId);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
