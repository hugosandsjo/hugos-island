
# Island: Terra Verde

Discover Terra Verde, the enchanting island cradling Harvest Haven in its embrace. Nestled like a jewel in the vast expanse of the ocean, Terra Verde captivates with its lush landscapes and untamed beauty. A haven for those seeking respite, this island paradise boasts a unique blend of tranquility and vibrancy.

Nature unfolds in a kaleidoscope of hues, from emerald-green hills to azure waters that kiss the shores. Terra Verde is a canvas painted with the strokes of diverse flora and fauna, creating a sanctuary for those who appreciate the untamed allure of the natural world.

<img src="https://media.giphy.com/media/Yr6XX2U8eT9kckJY5S/giphy.gif" width="700" height="350" />

# Hotel: Harvest Haven

Welcome to Harvest Haven, a prestigious sanctuary nestled within the heart of Terra Verde, an island blessed by nature's finest. With a legacy steeped in time, our exclusive retreat stands as a testament to opulence and heritage. Embracing the island's abundant beauty, our esteemed guests indulge in an experience tailored for those seeking luxury, tranquility, and a connection to the land.

At Harvest Haven, our story unfolds through locally produced wines and culinary delights meticulously crafted from the island's bounty. We blend a passion for exceptional cuisine with a reverence for Terra Verde's stunning landscapes. Every dish, every sip, carries the essence of our rich history and the vibrant spirit of this idyllic haven.



# Instructions

Just make your booking and enjoy your vacation

# Code review
Hello Hugo! First off, I loved your hotell. So stylefull. It was really hard doing the code review becouse IMO your code is really good. Not much for me to criticize really. So i really had to stretch to even find any small things. 

And please remember its nothing personal right? As i said I really had to nitpick to even find 10 things. So im really just pointing out trivial issues. 

1. script.js: 14 - Instead of declaring the variable "clickCount" globally you should contain it inside functions to avoid conflicts or other side effects
2. script.js:94-122-135 - Instead of attaching multiple event listeners to the same element (like navItem), you can combine them into a single listener.
3. script.js:1-144 - Try to maintain a consistent coding style. For example, you use arrow functions in some places and traditional function declarations in others. Choose one style and stick to it for consistency.
4. script.js:1-144 - Add error handling, especially when using methods like document.querySelector or getElementById where elements might not be found. Now in this case I get it that you know for certain that they are gonna be found, but in general its a good idea to always have error handling in place.
5. script.js:1-144 - The existing comments are helpful, but consider adding more comments for complex logic or to explain the purpose of certain sections.
6. script.js:115 - Instead of window.onload, consider using the DOMContentLoaded event for better performance. This way you dont have to wait for external resources.
7. booking.php - Consider separating concerns further. For instance, move the logic related to the Guzzle client and API calls into a separate file. 
8. booking.php:65-72 - If im reading the code correctly (wich im propably not doing )there seems to be some repetition in the error handling, specifically in the case where the transfer code is not valid. You might want to centralize error messages to avoid duplicating code. keep DRY.
9. booking.php:56 - The variable $statusCode is declared, but I cant seem to find any place that you actually use it? So it seems redundant.
10. form.php:19-29 - An old Hans favurite: Consider defining magic numbers (e.g., 1, 2, 3) as constants with meaningful names for better code readability.



# Database

```sql
CREATE TABLE bookings (
    id INTEGER PRIMARY KEY,
    arrival TEXT,
    departure TEXT,
    days INTEGER,
    guest_id INTEGER,
    hotel_id INTEGER,
    room_id INTEGER,
    FOREIGN KEY (guest_id) REFERENCES guests(id),
    FOREIGN KEY (hotel_id) REFERENCES hotel(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE guests (
    id INTEGER PRIMARY KEY,
    firstname VARCHAR,
    lastname VARCHAR,
    email VARCHAR
);

CREATE TABLE booking_features (
    booking_id INTEGER,
    feature_id INTEGER,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (feature_id) REFERENCES features(id)
);

CREATE TABLE rooms (
    id INTEGER PRIMARY KEY,
    room_class VARCHAR,
    price INTEGER
);

CREATE TABLE features (
    id INTEGER PRIMARY KEY,
    "name" VARCHAR,
    "cost" INTEGER
);

CREATE TABLE hotel (
    id INTEGER PRIMARY KEY,
    hotel VARCHAR,
    island VARCHAR,
    stars INTEGER
);

CREATE TABLE booking_rooms (
    booking_id INTEGER,
    room_id INTEGER,
    FOREIGN KEY (booking_id) REFERENCES booking(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

```
