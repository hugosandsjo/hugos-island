<?php
// declare(strict_types=1);
declare(strict_types=1);

// require autoload app
require 'app/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// echo '<pre>';
// echo $_ENV['API_KEY'];

// header('Content-Type: application/json');

?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/calendar.php'; ?>
<?php require __DIR__ . '/form.php'; ?>

<nav>
     <div class="budget" id="budgetNavItem">Budget 3€</div>
     <div class="standard" id="standardNavItem">Standard 5€</div>
     <div class="luxury" id="luxuryNavItem">Luxury 10€</div>
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
     <section id="anchor" class="anchor"></section>
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
                    <input class="checkbox" type="checkbox" id="peanuts" name="features[]" value="1"> <!-- value 1 = peanuts feature -->
                    <label class="featureLabel" for="peanuts">Peanuts 2kr</label>
                    <input class="checkbox" type="checkbox" id="vodka" name="features[]" value="2"> <!-- value 2 = vodka feature -->
                    <label class="featureLabel" for="vodka">Vodka 3kr</label>
                    <input class="checkbox" type="checkbox" id="dinner" name="features[]" value="3"> <!-- value 3 = dinner feature -->
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
               <br> <button type="submit">Book</button>

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