USE lumiere_cinema;

ALTER TABLE `cinema_halls`
ADD COLUMN `rows` INT NOT NULL AFTER `seating_capacity`;

UPDATE cinema_halls SET `rows` = 15 WHERE id = 1;
UPDATE cinema_halls SET `rows` = 8 WHERE id = 2;
UPDATE cinema_halls SET `rows` = 10 WHERE id = 3;
UPDATE cinema_halls SET `rows` = 5 WHERE id = 4;

CREATE TABLE cinema_hall_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hall_id INT NOT NULL,
    row_number INT NOT NULL,
    seat_number INT NOT NULL,
    is_available TINYINT(1) DEFAULT 1, -- 1 for available, 0 for booked
    screening_id INT NOT NULL,
    FOREIGN KEY (hall_id) REFERENCES cinema_halls(id) ON DELETE CASCADE,
    FOREIGN KEY (screening_id) REFERENCES screenings(id) ON DELETE CASCADE
)ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE cinema_hall_seats
ADD COLUMN booking_id INT DEFAULT NULL,
ADD CONSTRAINT fk_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL;

INSERT INTO permissions (id, name) VALUES (9, 'create_booking');
INSERT INTO roles_permissions (role_id, permission_id) VALUES (1, 9);
INSERT INTO roles_permissions (role_id, permission_id) VALUES (2, 9);