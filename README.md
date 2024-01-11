
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

1. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
2. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
3. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
4. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
5. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
6. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
7. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
8. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
9. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
10. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.


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
