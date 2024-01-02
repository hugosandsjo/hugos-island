<?php

// declare(strict_types=1);
declare(strict_types=1);

// require autoload app
require 'app/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// echo '<pre>';
// echo $_ENV['API_KEY'];

require __DIR__ . '/header.php';
require __DIR__ . '/functions.php';
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
          <p class="introduction">“We have a history of serving the best food in combination with beautiful rooms for your stay. All in combination with beautiful nature and food”</p>
     </header>
     <section class="hero">
          <img src="assets/images/castle.png" class="apple">
     </section>

     <section class="hotelName">
          <h1>Harvest haven</h1>
     </section>

     <h2>Our rooms:</h2>
     <section class="roomDescription">

          <div class="featureDiv">
               <h3>Budget</h3>
               <img class="featureImg" src="assets/images/tent.png">
               <p>"Indulge in our exclusive experience with handpicked peanuts sourced from our historic orchard gardens. Each nut a testament to our rich heritage, these locally grown delights offer a taste of the land’s legacy. Roasted to perfection, they accompany your stay with a crunchy, flavorful essence that echoes the natural beauty surrounding our esteemed hotel."</p>
               <div class="featurePrice">
                    <h4>2kr</h4>
                    <h4>Choose</h4>
               </div>
          </div>
          <div class="featureDiv">
               <h3>Standard</h3>
               <img class="featureImg" src="assets/images/house.png">
               <p>"Savor the essence of our region with a selection of meticulously crafted wines, harvested from the sprawling vineyards embracing our historic estate. Each bottle holds the story of our land, bottled elegance that speaks of the sun-soaked hills and cool, crisp air. With every sip, immerse yourself in the flavors of our heritage, indulging in the luxurious taste of our exclusive, locally produced wines."</p>
               <div class="featurePrice">
                    <h4>2kr</h4>
                    <h4>Choose</h4>
               </div>
          </div>
          <div class="featureDiv">
               <h3>Luxury</h3>
               <img class="featureImg" src="assets/images/castle.png">
               <p>"Embark on a gastronomic voyage celebrating the bounty of our surroundings. Our three-course meal is a symphony of locally sourced ingredients, meticulously curated to showcase the vibrant flavors of our region. From the first bite to the last, each dish tells the tale of our land's fertile soil and the skill of our passionate chefs. Delight in this culinary masterpiece amid the serene backdrop of our historic hotel, where nature's abundance meets culinary excellence."</p>
               <div class="featurePrice">
                    <h4>2kr</h4>
                    <h4>Choose</h4>
               </div>
          </div>
          </div>

     </section>

     <h2>Our exclusive addons:</h2>

     <section class="featureDescription">

          <div class="featureDiv">
               <h3>Peanuts from the Orchard Gardens:</h3>
               <img class="featureImg" src="assets/images/cashew.png">
               <p>"Indulge in our exclusive experience with handpicked peanuts sourced from our historic orchard gardens. Each nut a testament to our rich heritage, these locally grown delights offer a taste of the land’s legacy. Roasted to perfection, they accompany your stay with a crunchy, flavorful essence that echoes the natural beauty surrounding our esteemed hotel."</p>
               <div class="featurePrice">
                    <h4>2kr</h4>
                    <h4>Choose</h4>
               </div>
          </div>

          <div class="featureDiv">
               <h3>Vintage Estate Wines:</h3>
               <img class="featureImg" src="assets/images/wine.png">
               <p>"Savor the essence of our region with a selection of meticulously crafted wines, harvested from the sprawling vineyards embracing our historic estate. Each bottle holds the story of our land, bottled elegance that speaks of the sun-soaked hills and cool, crisp air. With every sip, immerse yourself in the flavors of our heritage, indulging in the luxurious taste of our exclusive, locally produced wines."</p>
               <div class="featurePrice">
                    <h4>2kr</h4>
                    <h4>Choose</h4>
               </div>
          </div>

          <div class="featureDiv">
               <h3>Three-Course Culinary Journey:</h3>
               <img class="featureImg" src="assets/images/dinner.png">
               <p>"Embark on a gastronomic voyage celebrating the bounty of our surroundings. Our three-course meal is a symphony of locally sourced ingredients, meticulously curated to showcase the vibrant flavors of our region. From the first bite to the last, each dish tells the tale of our land's fertile soil and the skill of our passionate chefs. Delight in this culinary masterpiece amid the serene backdrop of our historic hotel, where nature's abundance meets culinary excellence."</p>
               <div class="featurePrice">
                    <h4>2kr</h4>
                    <h4>Choose</h4>
               </div>
          </div>
     </section>
     <section id="anchor" class="anchor"></section> <!-- Section to anchor the viewport after the form is submitted -->
     <section class="calendarColors">
          <div class="calendarColor">
               <div class="colorBox"></div>
               <p>Booked</p>
          </div>
          <div class="calendarColor">
               <div class="colorBox"></div>
               <p>Booked</p>
          </div>
          <div class="calendarColor">
               <div class="colorBox"></div>
               <p>Booked</p>
          </div>
     </section>
     <section class="booking budget">

          <!-- Displaying the budget calendar -->
          <?php $events = addBookingsToEvents('1');
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
                    <input class="checkbox" type="checkbox" id="peanuts" name="features[]" value="peanuts"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="peanuts">Peanuts 2kr</label>
                    <input class="checkbox" type="checkbox" id="vodka" name="features[]" value="wine"> <!-- value 2 = vodka feature -->
                    <label class="featureLabel" for="vodka">Vodka 3kr</label>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="dinner"> <!-- value 3 = dinner feature -->
                    <label class="featureLabel" for="vodka">Dinner 4kr</label>
               </div>
               <br><button type="submit">Book</button>

               <?php if (isset($errors)) {
                    foreach ($errors as $error) {
                         echo '<br>' . $error;
                    }
               } ?>
          </form>

     </section>

     <section class="booking standard">

          <!-- displaying the standard calendar -->
          <?php $events = addBookingsToEvents('2');
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
                    <input class="checkbox" type="checkbox" id="peanuts" name="features[]" value="1"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="peanuts">Peanuts 2kr</label><br>
                    <input class="checkbox" type="checkbox" id="vodka" name="features[]" value="2"> <!-- value 2 = vodka feature -->
                    <label class="featureLabel" for="vodka">Vodka 3kr</label><br>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="3"> <!-- value 3 = dinner feature -->
                    <label class="featureLabel" for="vodka">Dinner 4kr</label><br>
               </div>
               <br><button type="submit">Book</button>

               <?php if (isset($errors)) {
                    foreach ($errors as $error) {
                         echo '<br>' . $error;
                    }
               } ?>
          </form>
     </section>

     <section class="booking luxury">

          <!-- displaying the luxury calendar -->
          <?php $events = addBookingsToEvents('3');
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
                    <input class="checkbox" type="checkbox" id="peanuts" name="features[]" value="1"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="peanuts">Peanuts 2kr</label><br>
                    <input class="checkbox" type="checkbox" id="vodka" name="features[]" value="2"> <!-- value 2 = vodka feature -->
                    <label class="featureLabel" for="vodka">Vodka 3kr</label><br>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="3"> <!-- value 3 = dinner feature -->
                    <label class="featureLabel" for="vodka">Dinner 4kr</label><br>
               </div>
               <br> <button class="submitButton" type="submit">Book</button>

               <?php if (isset($errors)) {
                    foreach ($errors as $error) {
                         echo '<br>' . $error;
                    }
               } ?>
          </form>
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

<?php require __DIR__ . '/footer.php'; ?>