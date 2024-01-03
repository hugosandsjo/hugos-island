// Select the "Budget" navigation item
let budgetNavItem = document.querySelector('#budgetNavItem');

// Change the ID of the "Budget" navigation item
budgetNavItem.id = 'selectedNavItem';

// Add the 'navItemSelected' class to the "Budget" navigation item
budgetNavItem.classList.add('navItemSelected');

// Select the "Budget" section
let budgetSection = document.querySelector('.booking.budget');

// Show the "Budget" section
budgetSection.style.display = 'flex';

// adds class mask to selected dates in the calendar
let clickCount = 0;

document.querySelectorAll('.calendar td').forEach(function (cell) {
  cell.addEventListener('click', function () {
    if (clickCount === 0) {
      // Remove 'mask-start' and 'mask-end' classes from all td elements
      document
        .querySelectorAll(
          '.calendar td.date-selected-start, .calendar td.date-selected-end',
        )
        .forEach(function (cell) {
          cell.classList.remove('date-selected-start', 'date-selected-end');
        });
    }

    clickCount++;

    if (clickCount === 1) {
      this.classList.add('date-selected-start'); // Add 'mask-start' class to first clicked td
    } else if (clickCount === 2) {
      this.classList.add('date-selected-end'); // Add 'mask-end' class to second clicked td
      clickCount = 0; // Reset click count
    }
  });
});

// this function handles the click event on the calendar day boxes
function handleCalendarClick(arrivalId, departureId, dayBoxClass) {
  let clickCount = 0; // Keeps track of the number of clicks
  let arrivalInput = document.getElementById(arrivalId); // Input field for arrival date
  let departureInput = document.getElementById(departureId); // Input field for departure date

  // loop through all the calendar day boxes
  document.querySelectorAll(dayBoxClass).forEach(function (box) {
    let parentTd = box.parentElement; // Get the parent td of the .cal-day-box

    // add click event listener to the parent td
    parentTd.addEventListener('click', function () {
      let selectedDay = box.textContent; // get the day from the .cal-day-box
      let selectedDate = '2024-01-' + selectedDay.padStart(2, '0'); // construct the date

      // update the appropriate form input based on the click count
      if (clickCount === 0) {
        arrivalInput.value = selectedDate; // set the arrival date
      } else if (clickCount === 1) {
        departureInput.value = selectedDate; // set the departure date
        clickCount = -1; // reset click count
      }
      clickCount++; // increment click count
    });
  });
}

// call the function with the appropriate parameters
handleCalendarClick('arrivalBudget', 'departureBudget', '.cal-day-box');
handleCalendarClick('arrivalStandard', 'departureStandard', '.cal-day-box');
handleCalendarClick('arrivalLuxury', 'departureLuxury', '.cal-day-box');

// toggle between the different rooms view
let navItems = document.querySelectorAll('nav div');
let sections = document.querySelectorAll('section.booking');

navItems.forEach((navItem) => {
  navItem.addEventListener('click', function () {
    // If the clicked navigation item is already selected, do nothing
    if (this.classList.contains('navItemSelected')) {
      return;
    }

    // hide all sections
    sections.forEach((section) => {
      section.style.display = 'none';
    });

    // show the section that matches the class of the clicked navigation item
    let sectionClass = this.className;
    let matchingSection = document.querySelector(`.booking.${sectionClass}`);
    if (matchingSection) {
      matchingSection.style.display = 'flex';
    }
  });
});

// make the first section visible when the page loads
window.onload = function () {
  document.querySelector('section.booking').style.display = 'flex';
};

let navSelected = document.querySelectorAll('#navItem');

navItems.forEach(function (navItem) {
  navItem.addEventListener('click', function () {
    // remove the class from all navigation items
    navItems.forEach(function (item) {
      item.classList.remove('navItemSelected');
    });

    // add the class to the clicked item
    this.classList.add('navItemSelected');
  });
});

// add click event listener to each navigation item
navItems.forEach(function (navItem) {
  navItem.addEventListener('click', function () {
    // remove #selectedNavItem id from all navigation items
    navItems.forEach(function (item) {
      item.removeAttribute('id');
    });

    // add #selectedNavItem id to the clicked item
    this.setAttribute('id', 'selectedNavItem');
  });
});
