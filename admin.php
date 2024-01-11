<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style/style.css">
</head>

<body>

    <button class="backButton" onclick="window.location.href = 'index.php'">Back</button>
    <section class="adminSection">
        <div class="adminDiv">
            <h5>Room prices</h5>
            <form action="admin.php" method="post" class="adminForm">
                <!-- <input type="text" name="" placeholder="username"> -->
                <label for="budget">Budget</label><br>
                <input type="number" default="3" min="1" max="30" name="budget"><br>

                <label for="standard">Standard</label><br>
                <input type="number" default="3" min="1" max="30" name="standard"><br>

                <label for="luxury">Luxury</label><br>
                <input type="number" default="3" min="1" max="30" name="luxury"><br>

                <br> <button type="submit">Update</button>
            </form>
        </div>
        <div class="adminDiv">
            <h5>Feature prices</h5>
            <form action="admin.php" method="post" class="adminForm">
                <!-- <input type="text" name="" placeholder="username"> -->
                <label for="cashews">Cashews</label><br>
                <input type="number" default="3" min="1" max="30" name="cashews"><br>

                <label for="wine">Wine</label><br>
                <input type="number" default="3" min="1" max="30" name="wine"><br>

                <label for="dinner">Dinner</label><br>
                <input type="number" default="3" min="1" max="30" name="dinner"><br>

                <br><button type="submit">Update</button>
            </form>
        </div>
    </section>


</body>

</html>

<?php

if (!empty($_POST)) {

    if (isset($_POST['budget']) && isset($_POST['standard']) && isset($_POST['luxury'])) {

        $budget = $_POST['budget'];
        $standard = $_POST['standard'];
        $luxury = $_POST['luxury'];

        $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');

        $statement = $database->prepare('UPDATE rooms SET price = :price WHERE id = 1');
        $statement->bindParam(':price', $budget, PDO::PARAM_INT);
        $statement->execute();

        $statement = $database->prepare('UPDATE rooms SET price = :price WHERE id = 2');
        $statement->bindParam(':price', $standard, PDO::PARAM_INT);
        $statement->execute();

        $statement = $database->prepare('UPDATE rooms SET price = :price WHERE id = 3');
        $statement->bindParam(':price', $luxury, PDO::PARAM_INT);
        $statement->execute();
    } elseif (isset($_POST['cashews']) && isset($_POST['wine']) && isset($_POST['dinner'])) {

        $cashews = $_POST['cashews'];
        $wine = $_POST['wine'];
        $dinner = $_POST['dinner'];

        $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');

        $statement = $database->prepare('UPDATE features SET cost = :cost WHERE id = 1');
        $statement->bindParam(':cost', $cashews, PDO::PARAM_INT);
        $statement->execute();

        $statement = $database->prepare('UPDATE features SET cost = :cost WHERE id = 2');
        $statement->bindParam(':cost', $wine, PDO::PARAM_INT);
        $statement->execute();

        $statement = $database->prepare('UPDATE features SET cost = :cost WHERE id = 3');
        $statement->bindParam(':cost', $dinner, PDO::PARAM_INT);
        $statement->execute();
    } else {
        echo 'Please fill in all fields';
    }
}

?>