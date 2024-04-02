<head>
    <?php
    include "inc/head.inc.php";
    ?>
</head>


<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <main class="container d-flex justify-content-center">
        <div>
        <h1>Member Login</h1>
        <p>
            Existing Members log in here. For new members please go to the members registration page.
            <a href="/register.php">Members Registration Page</a>.
        </p>
        <form action="process_login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input  maxlength="45" type="email" id="email" name="email" class="form-control" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input  type="password" id="pwd" name="pwd" class="form-control" placeholder="Enter password">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <hr>
        </div>

        
    </main>
    <?php
    include "inc/footer.inc.php";
    ?>
</body>