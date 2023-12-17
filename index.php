<?php
// declare(strict_types=1);
declare(strict_types=1);

// require autoload app
require 'app/autoload.php';


// new Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);



// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// echo '<pre>';
// echo $_ENV['API_KEY'];

// header('Content-Type: application/json');

?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/form.php'; ?>

<nav>
    <p>Budget</p>
    <p>Standard
    </p>
    <p>Luxury
    </p>
</nav>
<main>
    <header>

        <p class="introduction">“We have a history of serving the best food in combination with beautiful rooms for your stay. All in combination with beautiful nature and food”</p>
    </header>
    <section class="hero">
        <img src="assets/images/apple.png" class="apple">
    </section>
    <section class="hotelName">
        <h1>Florida inn</h1>
    </section>
    <section class="booking">

        <?php require __DIR__ . '/calendar.php'; ?>
        <form action="index.php" method="post">
            <input type="text" name="firstname" placeholder="Name">
            <input type="text" name="lastname" placeholder="Lastname">
            <input type="text" name="email" placeholder="Email" class="emailInput">
            <input type="text" id="arrival" name="arrival" placeholder="Arrival">
            <input type="text" id="departure" name="departure" placeholder="Departure">
            <input type="text" id="transferCode" name="transferCode" placeholder="Transfer code" class="transferCode"><br>
            <select id="room" name="roomType">
                <option value="budget">Budget</option>
                <option value="standard">Standard</option>
                <option value="luxury">Luxury</option>
            </select><br>
            <input type="checkbox" id="peanuts" name="peanuts" value="peanuts">
            <label for="peanuts">Peanuts 2kr</label><br>
            <input type="checkbox" id="peanuts" name="peanuts" value="peanuts">
            <label for="vodka">Vodka 3kr</label><br>

            <button type="submit">Book</button>

            <?php if (isset($errors)) {
                foreach ($errors as $error) {
                    echo '<br>' . $error;
                }
            } ?>



        </form>


    </section>

</main>

<footer>

</footer>

<?php require __DIR__ . '/footer.php'; ?>