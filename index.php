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

<header>
                    <h1>Florida inn</h1>
</header>
<main>
                    <section class="hero">
                                        <article class="budget">
                                                            <h2>Budget</h2>
                                                            <img src="assets/images/budget.png">
                                        </article>
                                        <article class="standard">
                                                            <h2>Standard</h2>
                                                            <img src="assets/images/standard.png">
                                        </article>
                                        <article class="luxury">
                                                            <h2>Luxury</h2>
                                                            <img src="assets/images/luxury.png">
                                        </article>
                    </section>

                    <section class="booking">

                                        <?php require __DIR__ . '/calendar.php'; ?>
                                        <form action="index.php" method="post">
                                                            <input type="text" name="firstname" placeholder="Name">
                                                            <input type="text" name="lastname" placeholder="Lastname">
                                                            <input type="text" name="email" placeholder="Email">
                                                            <input type="text" id="arrival" name="arrival" placeholder="Arrival">
                                                            <input type="text" id="departure" name="departure" placeholder="Departure">
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