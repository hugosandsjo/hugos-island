<?php

require __DIR__ . '/header.php';

?>
<?php


session_start();
echo "Your total cost is: " . $_SESSION['totalCost'];

?>


<?php
require __DIR__ . '/footer.php';
?>
