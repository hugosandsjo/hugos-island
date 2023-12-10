// Adds class mask to selected dates in the calendar

// let clickCount = 0;

// document.querySelectorAll('.calendar td').forEach(function (cell) {
//   cell.addEventListener('click', function () {
//     if (clickCount === 0) {
//       // Remove 'mask-start' and 'mask-end' classes from all td elements
//       document
//         .querySelectorAll(
//           '.calendar td.date-selected-start, .calendar td.date-selected-end',
//         )
//         .forEach(function (cell) {
//           cell.classList.remove('date-selected-start', 'date-selected-end');
//         });
//     }

//     clickCount++;

//     if (clickCount === 1) {
//       this.classList.add('date-selected-start'); // Add 'mask-start' class to first clicked td
//     } else if (clickCount === 2) {
//       this.classList.add('date-selected-end'); // Add 'mask-end' class to second clicked td
//       clickCount = 0; // Reset click count
//     }
//   });
// });

let arrivalInput = document.getElementById('arrival');
let departureInput = document.getElementById('departure');
let clickCount = 0;

document.querySelectorAll('.cal-day-box').forEach(function (box) {
  let parentTd = box.parentElement; // Get the parent td of the .cal-day-box

  parentTd.addEventListener('click', function () {
    let selectedDay = box.textContent; // Get the day from the .cal-day-box
    let selectedDate = '2024-01-' + selectedDay.padStart(2, '0'); // Construct the date

    // Update the appropriate form input based on the click count
    if (clickCount === 0) {
      arrivalInput.value = selectedDate;
    } else if (clickCount === 1) {
      departureInput.value = selectedDate;
      clickCount = -1; // Reset click count
    }

    clickCount++;
  });
});
