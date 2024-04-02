
document.addEventListener("DOMContentLoaded", function(event) {
    console.log("DOM fully loaded and parsed");
    activateMenu();
});

//Testing the function

function activateMenu() {
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        if (link.href === location.href) {
            link.classList.add('active');
            const icon = link.querySelector('.fa-solid');
            if (icon) {
                icon.style.color = '#C8F023'; // Your desired highlight color
            }
        }
    });
}

function popupImage(src)
{
    var popup = document.createElement("div");
        popup.className = "popup-container";

    var popupImage = document.createElement("img");
        popupImage.className = "popup-content";
        popupImage.src = src;

    popup.appendChild(popupImage);

    // Add popup to the body
    document.body.appendChild(popup);

    // Add event listener to remove popup when clicked
    popup.onclick = function ()
    {
        document.body.removeChild(popup);
    }
}

// Get all elements with the class "image-zoom"
const imageZoomElements = document.querySelectorAll('.image-zoom');

// Add a click event listener to each element
imageZoomElements.forEach(element => {
    element.addEventListener('click', () => {
        //Getting the alt value
        var altValue = element.getAttribute('Alt');
        // Open the element as a popup
        popupImage("images/"+ altValue + "_large.jpg");
    });
});
