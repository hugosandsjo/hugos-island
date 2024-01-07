// make budget be selected at page load
// select the "Budget" navigation item
let budgetNavItem = document.querySelector('#budgetNavItem');
// change the ID of the "Budget" navigation item
budgetNavItem.id = 'selectedNavItem';
// add the 'navItemSelected' class to the "Budget" navigation item
budgetNavItem.classList.add('navItemSelected');
// select the "Budget" section
let budgetSection = document.querySelector('.booking.budget');
// show the "Budget" section
budgetSection.style.display = 'flex';

// adds class mask to selected dates in the calendar
let clickCount = 0;

document.querySelectorAll('.calendar td').forEach(function (cell) {
  cell.addEventListener('click', function () {
    if (clickCount === 0) {
      // remove 'mask-start' and 'mask-end' classes from all td elements
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
      this.classList.add('date-selected-start'); // add 'mask-start' class to first clicked td
    } else if (clickCount === 2) {
      this.classList.add('date-selected-end'); // add 'mask-end' class to second clicked td
      clickCount = 0; // reset click count
    }
  });
});

function handleCalendarClick(arrivalId, departureId, dayBoxClass) {
  let clickCount = 0;
  let arrivalInput = document.getElementById(arrivalId);
  let departureInput = document.getElementById(departureId);
  let selectedBoxes = [];

  document.querySelectorAll(dayBoxClass).forEach(function (box) {
    let parentTd = box.parentElement;

    parentTd.addEventListener('click', function () {
      let selectedDay = box.textContent;
      let selectedDate = '2024-01-' + selectedDay.padStart(2, '0');

      if (clickCount === 0) {
        // remove the .date-selected class from all elements
        document.querySelectorAll('.date-selected').forEach((element) => {
          element.classList.remove('date-selected');
        });
        arrivalInput.value = selectedDate;
        selectedBoxes.push(parentTd);
      } else if (clickCount === 1) {
        departureInput.value = selectedDate;
        selectedBoxes.push(parentTd);

        let startIndex = Array.from(document.querySelectorAll(dayBoxClass))
          .map((box) => box.parentElement)
          .indexOf(selectedBoxes[0]);
        let endIndex = Array.from(document.querySelectorAll(dayBoxClass))
          .map((box) => box.parentElement)
          .indexOf(selectedBoxes[1]);

        document.querySelectorAll(dayBoxClass).forEach((box, index) => {
          if (index >= startIndex && index <= endIndex) {
            box.parentElement.classList.add('date-selected');
          }
        });

        clickCount = -1;
        selectedBoxes = [];
      }

      clickCount++;
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
    // if the clicked navigation item is already selected, do nothing
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
