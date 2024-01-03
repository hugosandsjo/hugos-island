<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
</head>

<body>
     <h1>Feature prices</h1>
     <form action="admin.php" method="post">
          <!-- <input type="text" name="" placeholder="username"> -->
          <label for="cashews">Cashews</label>
          <input type="number" default="3" min="1" max="30" name="cashews">

          <label for="wine">Wine</label>
          <input type="number" default="3" min="1" max="30" name="wine">

          <label for="dinner">Dinner</label>
          <input type="number" default="3" min="1" max="30" name="dinner">

          <br><button type="submit">Submit</button>
     </form>

     <h1>Room prices</h1>
     <form action="admin.php" method="post">
          <!-- <input type="text" name="" placeholder="username"> -->
          <label for="budget">Budget</label>
          <input type="number" default="3" min="1" max="30" name="budget">

          <label for="standard">Standard</label>
          <input type="number" default="3" min="1" max="30" name="standard">

          <label for="luxury">Luxury</label>
          <input type="number" default="3" min="1" max="30" name="luxury">

          <br> <button type="submit">Submit</button>
     </form>
</body>

</html>

<?php
if (isset($_POST['cashews']) && isset($_POST['wine']) && isset($_POST['dinner'])) {
     $cashews = $_POST['cashews'];
     $wine = $_POST['wine'];
     $dinner = $_POST['dinner'];

     $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');  // connect the database

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

?>