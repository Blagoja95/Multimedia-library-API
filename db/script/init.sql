CREATE DATABASE IF NOT EXISTS libdb;

USE libdb;

-- *
-- Books
-- * *

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    creator_id INT,
    title VARCHAR(255),
    genre VARCHAR(90),
    author_id INT,
    realese_date DATETIME,
    description TEXT
);

INSERT INTO books (id, creator_id, title, genre, author_id, realese_date, description)
VALUES (10, 100, "To Kill a Mockingbird", "Fiction", 1, "1960-07-11",
        "A novel by Harper Lee set in the American South during the 1930s, addressing issues of racial injustice and moral growth."),
       (20, 100, "1984", "Dystopian Fiction", 2, "1949-06-08",
        "A dystopian novel by George Orwell depicting a totalitarian regime and the struggles of the protagonist against it."),
       (30, 200, "Pride and Prejudice", "Romance", 3, "1813-01-28",
        "A romantic novel by Jane Austen focusing on the manners and morals of the British landed gentry at the turn of the 19th century."),
       (40, 200, "The Great Gatsby", "Fiction", 4, "1925-04-10",
        "A novel by F. Scott Fitzgerald set in the Roaring Twenties, exploring themes of decadence, idealism, and the American Dream."),
       (50, 100, "Harry Potter and the Philosopher's Stone", "Fantasy", 5, "1997-06-26", "The first book in the Harry Potter series by J.K. Rowling, following the journey of a young wizard, Harry Potter, and his friends at Hogwarts School of Witchcraft and Wizardry.");

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(255),
    lname VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    profile_img_id INT,
    about TEXT
);

INSERT INTO users (id, fname, lname, email, password, profile_img_id, about)
VALUES (100, "Petar", "Petrovic", "ppero@gmail.com", "$2y$12$OmILLO99a6lsBmIkN3MS1.Ek2s7fhpbzOYdCVfi/7gxiEsnyAapSS", 20, "Hi"),
       (200, "Marko", "Markovic", "marakan@gmail.com", "$2y$12$OmILLO99a6lsBmIkN3MS1.Ek2s7fhpbzOYdCVfi/7gxiEsnyAapSS", 10, "Hi again");