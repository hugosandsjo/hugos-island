// Adds class mask to selected dates in the calendar
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

// This function handles the click event on the calendar day boxes
function handleCalendarClick(arrivalId, departureId, dayBoxClass) {
  let clickCount = 0; // Keeps track of the number of clicks
  let arrivalInput = document.getElementById(arrivalId); // Input field for arrival date
  let departureInput = document.getElementById(departureId); // Input field for departure date

  // Loop through all the calendar day boxes
  document.querySelectorAll(dayBoxClass).forEach(function (box) {
    let parentTd = box.parentElement; // Get the parent td of the .cal-day-box

    // Add click event listener to the parent td
    parentTd.addEventListener('click', function () {
      let selectedDay = box.textContent; // Get the day from the .cal-day-box
      let selectedDate = '2024-01-' + selectedDay.padStart(2, '0'); // Construct the date

      // Update the appropriate form input based on the click count
      if (clickCount === 0) {
        arrivalInput.value = selectedDate; // Set the arrival date
      } else if (clickCount === 1) {
        departureInput.value = selectedDate; // Set the departure date
        clickCount = -1; // Reset click count
      }

      clickCount++; // Increment click count
    });
  });
}

// Call the function with the appropriate parameters
handleCalendarClick('arrivalBudget', 'departureBudget', '.cal-day-box');
handleCalendarClick('arrivalStandard', 'departureStandard', '.cal-day-box');
handleCalendarClick('arrivalLuxury', 'departureLuxury', '.cal-day-box');

// toggle between the different rooms view
let navItems = document.querySelectorAll('nav div');
let sections = document.querySelectorAll('section.booking');

navItems.forEach((navItem) => {
  navItem.addEventListener('click', function () {
    // Hide all sections
    sections.forEach((section) => {
      section.style.display = 'none';
    });

    // Show the section that matches the class of the clicked navigation item
    let sectionClass = this.className;
    let matchingSection = document.querySelector(`.booking.${sectionClass}`);
    if (matchingSection) {
      matchingSection.style.display = 'flex';
    }
  });
});
// Make the first section visible when the page loads
window.onload = function () {
  document.querySelector('section.booking').style.display = 'flex';
};

let navSelected = document.querySelectorAll('#navItem');

navItems.forEach(function (navItem) {
  navItem.addEventListener('click', function () {
    // Remove the class from all navigation items
    navItems.forEach(function (item) {
      item.classList.remove('navItemSelected');
    });

    // Add the class to the clicked item
    this.classList.add('navItemSelected');
  });
});

// Select all navigation items
// let navItems = document.querySelectorAll('nav div');

// Add click event listener to each navigation item
navItems.forEach(function (navItem) {
  navItem.addEventListener('click', function () {
    // Remove #selectedNavItem id from all navigation items
    navItems.forEach(function (item) {
      item.removeAttribute('id');
    });

    // Add #selectedNavItem id to the clicked item
    this.setAttribute('id', 'selectedNavItem');
  });
});
