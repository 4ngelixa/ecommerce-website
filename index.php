<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php 
        include "inc/head.inc.php";
    ?>
</head>
<body>
    <?php 
        include "inc/nav.inc.php";
    ?>
    
    <?php 
        include "inc/header.inc.php";
    ?>
    <main class ="container">
        <section id="dogs">
            <h2>All About Dogs!</h2>
            <div class="row">
                <article class = "col-sm">
                    <h3>Poodles</h3>
                    <figure>
                            <img src="images/poodle_small.jpg" alt="Poodle"
                                class="image-zoom" title="View larger image..."/>
                        <figcaption>Standard Poodle</figcaption>
                    </figure>
                    <p>
                        Poodles are a group of formal dog breeds, the Standard
                        Poodle, Miniature Poodle and Toy Poodle...
                    </p>
                </article>
                <article class="col-sm">
                    <h3>Chihuahua</h3>
                    <figure>
                            <img src="images/chihuahua_small.jpg" alt="chihuahua"
                                class="image-zoom" title="View larger image..."/>
                        <figcaption>Standard Chihuahua</figcaption>
                    </figure>
                    <p>
                        The Chihuahua is the smallest breed of dog, and is named
                        after the Mexican state of Chihuahua...
                    </p>
                </article>
            </div>
        </section>
        <section id="cats">
            <h2>All About Cats!</h2>
            <div class="row">
                <article class="col-sm">
                    <h3>Tabby</h3>
                    <figure>
                            <img src="images/tabby_small.jpg" alt="tabby"
                                class="image-zoom" title="View larger image..."/>
                        <figcaption>Standard Tabby</figcaption>
                    </figure>
                    <p>
                        Tabby is a coat pattern and not a breed of cat. The
                        tabby pattern is a naturally occurring feature that may
                        be the original coloration of the domestic cat's
                        ancestors...
                    </p>
                </article>
                <article class="col-sm">
                    <h3>Calico</h3>
                    <figure>
                            <img src="images/calico_small.jpg" alt="calico"
                                class="image-zoom" title="View larger image..."/>
                        <figcaption>Standard Calico</figcaption>
                    </figure>
                    <p>
                        Calico cats are domestic cats with a spotted or parti-
                        colored coat that is predominantly white, with patches
                        of two other colors...
                    </p>
                </article>
            </div>
        </section>
    </main>
    <?php 
    include "inc/footer.inc.php";
?>

<script defer
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous">
</body>
        

</html>