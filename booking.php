<?php

declare(strict_types=1);

require __DIR__ . '/header.php';





session_start();
echo "Your total cost is: " . $_SESSION['totalCost'];





require __DIR__ . '/footer.php';
