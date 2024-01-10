<?php

require __DIR__ . '/calendar.php';
require __DIR__ . '/form.php';

?>

<nav>
    <div class="budget" id="budgetNavItem">Budget</div>
    <div class="standard" id="standardNavItem">Standard</div>
    <div class="luxury" id="luxuryNavItem">Luxury</div>
</nav>

<main>
    <header>
        <p class="introduction">“At Harvest Haven, our story unfolds through locally produced wines and culinary delights meticulously crafted from the island's bounty. We blend a passion for exceptional cuisine with a reverence for Terra Verde's stunning landscapes.”</p>
    </header>
    <section class="hero">
        <img src="assets/images/castle.png" class="apple">
    </section>

    <section class="hotelName">
        <h1>Harvest Haven</h1>
    </section>

    <h2>Our rooms:</h2>
    <section class="roomDescription">

        <div class="featureDiv">
            <h3>The Cozy Tent:</h3>
            <img class="featureImg" src="assets/images/tent.png">
            <p>Experience rustic charm in our cozy tent accommodations. Embrace the great outdoors while enjoying the comforts of a snug haven. Nestled amidst nature's embrace, our tents offer a unique blend of adventure and comfort. Relax under the stars, surrounded by the serene beauty of our estate, and create unforgettable memories in this intimate, nature-infused setting.</p>
            <div class="featurePrice">
                <h4><?= $roomPrices['0']['price'] ?> kr</h4> <!-- get the updated price from database -->
                <h4>Budget</h4>
            </div>
        </div>
        <div class="featureDiv">
            <h3>The Charming Cabin:</h3>
            <img class="featureImg" src="assets/images/house.png">
            <p>Step into a world of understated elegance within our charming cabin rooms. Thoughtfully designed for comfort and tranquility, these cozy yet stylish accommodations offer a retreat from the everyday hustle. Embrace the snug ambiance of our cabins, nestled within our historic estate, where every detail invites you to unwind and immerse yourself in the serenity of our surroundings.</p>
            <div class="featurePrice">
                <h4><?= $roomPrices['1']['price'] ?> kr</h4> <!-- get the updated price from database -->
                <h4>Standard</h4>
            </div>
        </div>
        <div class="featureDiv">
            <h3>The Grand Manor:</h3>
            <img class="featureImg" src="assets/images/castle.png">
            <p>Indulge in the epitome of luxury within our grand manor rooms. Opulence meets heritage in these lavishly appointed accommodations, offering an unparalleled experience of comfort and sophistication. Revel in the grandeur of a smaller mansion, where every corner exudes elegance, complemented by sweeping views of our picturesque estate. Immerse yourself in a world of refined indulgence, where luxury meets history in seamless harmony.</p>
            <div class="featurePrice">
                <h4><?= $roomPrices['2']['price'] ?> kr</h4> <!-- get the updated price from database -->
                <h4>Luxury</h4>
            </div>
        </div>
        </div>

    </section>

    <h2>Our exclusive addons:</h2>

    <section class="featureDescription">

        <div class="featureDiv">
            <h3>Cashews from the Orchard Gardens:</h3>
            <img class="featureImg" src="assets/images/cashew.png">
            <p>Indulge in our exclusive experience with handpicked cashews sourced from our historic orchard gardens. Each nut a testament to our rich heritage, these locally grown delights offer a taste of the land’s legacy. Roasted to perfection, they accompany your stay with a crunchy, flavorful essence that echoes the natural beauty surrounding our esteemed hotel.</p>
            <div class="featurePrice">
                <h4><?= $featureCosts['0']['cost'] ?> kr</h4> <!-- get the updated price from database -->
                <h4>Cashews</h4>
            </div>
        </div>

        <div class="featureDiv">
            <h3>Vintage Estate Wines:</h3>
            <img class="featureImg" src="assets/images/wine.png">
            <p>Savor the essence of our region with a selection of meticulously crafted wines, harvested from the sprawling vineyards embracing our historic estate. Each bottle holds the story of our land, bottled elegance that speaks of the sun-soaked hills and cool, crisp air. With every sip, immerse yourself in the flavors of our heritage, indulging in the luxurious taste of our exclusive, locally produced wines.</p>
            <div class="featurePrice">
                <h4><?= $featureCosts['1']['cost'] ?> kr</h4> <!-- get the updated price from database -->
                <h4>Wine</h4>
            </div>
        </div>

        <div class="featureDiv">
            <h3>Three-Course Culinary Journey:</h3>
            <img class="featureImg" src="assets/images/dinner.png">
            <p>Embark on a gastronomic voyage celebrating the bounty of our surroundings. Our three-course meal is a symphony of locally sourced ingredients, meticulously curated to showcase the vibrant flavors of our region. From the first bite to the last, each dish tells the tale of our land's fertile soil and the skill of our passionate chefs. Delight in this culinary masterpiece amid the serene backdrop of our historic hotel, where nature's abundance meets culinary excellence.</p>
            <div class="featurePrice">
                <h4><?= $featureCosts['2']['cost'] ?> kr</h4> <!-- get the updated price from database -->
                <h4>Dinner</h4>
            </div>
        </div>
    </section>
    <section class="offer">
        <h2>Book three days or more and get 30% off your stay!</h2>
    </section>

    <section id="anchor" class="anchor"></section> <!-- section to anchor the viewport after the form is submitted -->

    <section class="booking budget">
        <div class="calendarCategory">
            <h3>Budget</h3>
        </div>
        <div class="formContainer">
            <!-- displaying the budget calendar -->
            <?php $events = addBookingsToEvents(1);
            $calendarBudget->addEvents($events['eventsBudget']);
            $calendarBudget->display(date('2024-01-01')); ?>

            <form action="index.php#anchor" method="post">
                <input type="text" name="firstname" placeholder="Name">
                <input type="text" name="lastname" placeholder="Lastname">
                <input type="text" name="email" placeholder="Email" class="emailInput">
                <input type="text" id="arrivalBudget" name="arrival" placeholder="Arrival">
                <input type="text" id="departureBudget" name="departure" placeholder="Departure">
                <input type="text" id="transferCode" name="transferCode" placeholder="Transfer code" class="transferCode"><br>
                <select id="room" name="roomType">
                    <option value="budget">Budget</option>
                </select>
                <div class="features">
                    <input class="checkbox" type="checkbox" id="cashews" name="features[]" value="1"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="cashews">Cashews <?= $featureCosts['0']['cost'] ?> kr</label>
                    <input class="checkbox" type="checkbox" id="wine" name="features[]" value="2"> <!-- value 2 = wine feature -->
                    <label class="featureLabel" for="wine">Wine <?= $featureCosts['1']['cost'] ?> kr</label>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="3"> <!-- value 3 = dinner feature -->
                    <label class="featureLabel" for="dinner">Dinner <?= $featureCosts['2']['cost'] ?> kr</label>
                </div>
                <br><button type="submit">Proceed</button>

                <?php if (isset($errors)) {
                    foreach ($errors as $error) {
                        echo '<br>' . $error;
                    }
                } ?>

            </form>
        </div>
    </section>

    <section class="booking standard">
        <div class="calendarCategory">
            <h3>Standard</h3>
        </div>
        <div class="formContainer">
            <!-- displaying the standard calendar -->
            <?php $events = addBookingsToEvents(2);
            $calendarStandard->addEvents($events['eventsStandard']);
            $calendarStandard->display(date('2024-01-01')); ?>

            <form action="index.php#anchor" method="post">
                <input type="text" name="firstname" placeholder="Name">
                <input type="text" name="lastname" placeholder="Lastname">
                <input type="text" name="email" placeholder="Email" class="emailInput">
                <input type="text" id="arrivalStandard" name="arrival" placeholder="Arrival">
                <input type="text" id="departureStandard" name="departure" placeholder="Departure">
                <input type="text" id="transferCode" name="transferCode" placeholder="Transfer code" class="transferCode"><br>
                <select id="room" name="roomType">
                    <option value="standard">Standard</option>
                </select>
                <div class="features">
                    <input class="checkbox" type="checkbox" id="cashews" name="features[]" value="1"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="cashews">Cashews <?= $featureCosts['0']['cost'] ?> kr</label><br>
                    <input class="checkbox" type="checkbox" id="wine" name="features[]" value="2"> <!-- value 2 = vodka feature -->
                    <label class="featureLabel" for="wine">Wine <?= $featureCosts['1']['cost'] ?> kr</label><br>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="3"> <!-- value 3 = dinner feature -->
                    <label class="featureLabel" for="dinner">Dinner <?= $featureCosts['2']['cost'] ?> kr</label><br>
                </div>
                <br><button type="submit">Proceed</button>

                <?php if (isset($errors)) {
                    foreach ($errors as $error) {
                        echo '<br>' . $error;
                    }
                } ?>

            </form>
        </div>
    </section>

    <section class="booking luxury">
        <div class="calendarCategory">
            <h3>Luxury</h3>
        </div>
        <div class="formContainer">
            <!-- displaying the luxury calendar -->
            <?php $events = addBookingsToEvents(3);
            $calendarLuxury->addEvents($events['eventsLuxury']);
            $calendarLuxury->display(date('2024-01-01')); ?>

            <form action="index.php#anchor" method="post">
                <input type="text" name="firstname" placeholder="Name">
                <input type="text" name="lastname" placeholder="Lastname">
                <input type="text" name="email" placeholder="Email" class="emailInput">
                <input type="text" id="arrivalLuxury" name="arrival" placeholder="Arrival">
                <input type="text" id="departureLuxury" name="departure" placeholder="Departure">
                <input type="text" id="transferCode" name="transferCode" placeholder="Transfer code" class="transferCode"><br>
                <select id="room" name="roomType">
                    <option value="luxury">Luxury</option>
                </select>
                <div class="features">
                    <input class="checkbox" type="checkbox" id="cashews" name="features[]" value="1"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="cashews">Cashews <?= $featureCosts['0']['cost'] ?> kr</label><br>
                    <input class="checkbox" type="checkbox" id="wine" name="features[]" value="2"> <!-- value 2 = vodka feature -->
                    <label class="featureLabel" for="wine">Wine <?= $featureCosts['1']['cost'] ?> kr</label><br>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="3"> <!-- value 3 = dinner feature -->
                    <label class="featureLabel" for="dinner">Dinner <?= $featureCosts['2']['cost'] ?> kr</label><br>
                </div>
                <br> <button class="submitButton" type="submit">Proceed</button>

                <?php if (isset($errors)) {
                    foreach ($errors as $error) {
                        echo '<br>' . $error;
                    }
                } ?>

            </form>
        </div>
    </section>

    <section class="calendarColors">
        <div class="calendarColor">
            <div class="colorBox grayBox"></div>
            <p>Today</p>
        </div>
        <div class="calendarColor">
            <div class="colorBox orangeBox"></div>
            <p>Booked</p>
        </div>
        <div class="calendarColor">
            <div class="colorBox blueBox"></div>
            <p>Your choice</p>
        </div>
    </section>

    <section class="succesfullBooking">
        <?php
        if (isset($message)) { ?><p>
                <?php echo $message; ?></p>
            <form class="showJSON" action="displayjson.php" method="post" target="_blank">
                <input class="JSONbutton" type="submit" value="Show JSON">
            </form>
        <?php } ?>
    </section>

</main>