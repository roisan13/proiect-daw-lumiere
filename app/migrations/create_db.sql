-- create the database
CREATE DATABASE lumiere_cinema CHARACTER SET=utf8mb4;

-- create the user and grant privileges
CREATE USER 'lumiereuser'@'localhost' IDENTIFIED BY 'lumierepass';
GRANT ALL ON lumiere_cinema.* TO 'lumiereuser'@'localhost';

-- create the user and grant privileges
CREATE USER 'lumiereuser'@'127.0.0.1' IDENTIFIED BY 'lumierepass';
GRANT ALL ON lumiere_cinema.* TO 'lumiereuser'@'127.0.0.1';

-- if you run the commans from phpmyadmin, comment the next line
USE lumiere_cinema;

-- create the tables
CREATE TABLE user_roles (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) UNIQUE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE users (
   id INTEGER AUTO_INCREMENT PRIMARY KEY,
   first_name VARCHAR(128),
   last_name VARCHAR(128),
   email VARCHAR(128) UNIQUE,
   password VARCHAR(128) NOT NULL,
   role_id INTEGER NOT NULL,
   send_notification BOOLEAN DEFAULT FALSE,
   FOREIGN KEY(role_id) REFERENCES user_roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- cinema specific tables
CREATE TABLE movies (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    duration INTEGER NOT NULL,
    release_date DATE NOT NULL,
    rating DECIMAL(3, 2) DEFAULT 0.0,
    poster VARCHAR(255),
    UNIQUE (title, release_date)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE cinema_halls (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    seating_capacity INTEGER NOT NULL
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE screenings (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    movie_id INTEGER NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    cinema_hall_id INTEGER NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (cinema_hall_id) REFERENCES cinema_halls(id) ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE bookings (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    screening_id INTEGER NOT NULL,
    number_of_seats INTEGER NOT NULL DEFAULT 1,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (screening_id) REFERENCES screenings(id) ON DELETE RESTRICT
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- this table is used to keep track of the migrations that have been run
CREATE TABLE migrations (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL UNIQUE,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- insert the default roles
INSERT INTO user_roles (name) VALUES ('admin');
INSERT INTO user_roles (name) VALUES ('user');
INSERT INTO user_roles (name) VALUES ('guest');

-- insert the default users
INSERT INTO users (first_name, last_name, email, password, role_id) VALUES ('admin', 'admin', 'admin@admin.com', 'admin', 1);
INSERT INTO users (first_name, last_name, email, password, role_id) VALUES ('user', 'user', 'user@user.com', 'user', 2);

-- insert movies
INSERT INTO movies (title, duration, release_date, rating, poster)
VALUES 
('Inception', 148, '2010-07-16', 8.8, 'https://xl.movieposterdb.com/10_06/2010/1375666/xl_1375666_07030c72.jpg?v=2024-11-10%2011:17:55'),
('The Godfather', 175, '1972-03-24', 9.2, 'https://xl.movieposterdb.com/22_07/1972/68646/xl_68646_8c811dec.jpg?v=2024-10-30%2019:57:17'),
('Pulp Fiction', 154, '1994-10-14', 8.9, 'https://xl.movieposterdb.com/07_10/1994/110912/xl_110912_55345443.jpg?v=2024-11-10%2011:17:12'),
('The Dark Knight', 152, '2008-07-18', 9.0, 'https://xl.movieposterdb.com/08_05/2008/468569/xl_468569_f0e2cd63.jpg?v=2024-11-04%2009:23:29'),
('Fight Club', 139, '1999-10-15', 8.8, 'https://xl.movieposterdb.com/05_02/1999/0137523/xl_7868_0137523_d46e33b9.jpg?v=2024-11-03%2023:16:40'),
('Forrest Gump', 142, '1994-07-06', 8.8, 'https://xl.movieposterdb.com/12_04/1994/109830/xl_109830_58524cd6.jpg?v=2024-10-01%2001:58:31'),
('The Matrix', 136, '1999-03-31', 8.7, 'https://xl.movieposterdb.com/06_01/1999/0133093/xl_77607_0133093_ab8bc972.jpg?v=2024-09-22%2018:03:53');

-- insert cinema halls
INSERT INTO cinema_halls (name, seating_capacity) 
VALUES 
('Hall 1', 150),
('Hall 2', 120),
('Hall 3', 100),
('VIP Hall', 50);

-- insert screenings
INSERT INTO screenings (movie_id, start_time, end_time, cinema_hall_id) 
VALUES 
(1, '2024-11-11 14:00:00', '2024-11-11 16:28:00', 1),
(1, '2024-11-11 18:30:00', '2024-11-11 20:58:00', 1),
(2, '2024-11-11 16:00:00', '2024-11-11 18:55:00', 2),
(3, '2024-11-11 19:00:00', '2024-11-11 21:34:00', 3),
(4, '2024-11-12 14:30:00', '2024-11-12 16:52:00', 1),
(4, '2024-11-12 20:00:00', '2024-11-12 22:32:00', 1),
(5, '2024-11-12 17:00:00', '2024-11-12 19:19:00', 2),
(6, '2024-11-12 21:00:00', '2024-11-12 23:22:00', 3),
(7, '2024-11-13 13:00:00', '2024-11-13 15:16:00', 1),
(7, '2024-11-13 18:00:00', '2024-11-13 20:16:00', 1),
(1, '2024-11-13 15:30:00', '2024-11-13 17:58:00', 2),
(2, '2024-11-13 20:00:00', '2024-11-13 22:55:00', 2),
(3, '2024-11-13 16:30:00', '2024-11-13 19:04:00', 3);

-- insert bookings
INSERT INTO bookings (user_id, screening_id, number_of_seats, booking_date)
VALUES 
(2, 1, 2, '2024-11-10 12:00:00'),
(2, 4, 3, '2024-11-10 15:30:00'),
(2, 7, 1, '2024-11-10 18:45:00');


-- insert the migration
INSERT INTO migrations (name) VALUES ('create_db');