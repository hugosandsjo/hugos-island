<?php

declare(strict_types=1);

require 'vendor/autoload.php';
// start calendar
use benhall14\phpCalendar\Calendar as Calendar;

// create new calendars for each room standard
$calendarBudget = new Calendar();
$calendarStandard = new Calendar();
$calendarLuxury = new Calendar();
