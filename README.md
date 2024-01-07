
# Terra Verde

Some text about your lovely island

# Harvest Haven

At Harvest Haven, our story unfolds through locally produced wines and culinary delights meticulously crafted from the island's bounty. We blend a passion for exceptional cuisine with a reverence for Terra Verde's stunning landscapes.

<img src="https://media.giphy.com/media/l0IsHpdbT4EX8wOgU/giphy.gif" width="700" height="350" />

# Instructions

If your project requires some installation or similar, please inform your user 'bout it. For instance, if you want a more decent indentation of your .php files, you could edit [.editorconfig]('/.editorconfig').

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
