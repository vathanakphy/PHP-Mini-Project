<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Home</title>
    <link rel="stylesheet" href="style.css">
    <?php
        session_start();
        if (empty($_SESSION)) {
            header("Location: ./form.php?ref=" . $_SERVER["PHP_SELF"]);
            exit();
        }
    ?>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>MySocial</h1>
            <nav>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="./logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="#">Friends</a></li>
                <li><a href="#">Groups</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Help</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h2>Welcome <?php echo $_SESSION['name'];?></h2>
            <div class="post">
                <h3>John Doe</h3>
                <p>Had a great day at the park!</p>
            </div>
            <div class="post">
                <h3>Jane Smith</h3>
                <p>Check out this amazing recipe I found!</p>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2025 MySocial. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>