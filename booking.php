<?php

declare(strict_types=1);

require __DIR__ . '/header.php';
// require __DIR__ . '/app/autoload.php';
// require __DIR__ . '/vendor/autoload.php';

// require __DIR__ . '/functions.php';
// require __DIR__ . '/adminlogin.php';
// require __DIR__ . '/prices.php';

session_start();

?>
<section class="lastStepSection">

    <div class="lastStep">

        <h2>Summary:</h2>

        <div class="stepDiv">
            <h5>Total cost:</h5>
            <h5> <?= $_SESSION['totalCost']; ?> </h5>
        </div>

        <div class="stepDiv">
            <h5>Arrival:</h5>
            <h5><?= $_SESSION['arrival']; ?></h5>
        </div>

        <div class="stepDiv">
            <h5>Departure:</h5>
            <h5><?= $_SESSION['departure']; ?></h5>
        </div>


        <div class="stepDiv">
            <h5>Features:</h5>

            <h5> <?php foreach ($_SESSION['selectedFeatures'] as $feature) {
                        echo $feature . ', ';
                    }
                    ?></h5>
        </div>

        <form>
            <input type="text" name="transferCode" placeholder="Transfer code">
            <button type="submit">Book</button>
        </form>

    </div>
</section>

<?php

require __DIR__ . '/footer.php';
