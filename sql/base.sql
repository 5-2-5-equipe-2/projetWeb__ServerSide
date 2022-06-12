DROP
DATABASE app_db;
CREATE
DATABASE app_db;
USE
app_db;
CREATE TABLE `user`
(
    `id`              integer PRIMARY KEY AUTO_INCREMENT,
    `username`        varchar(150) UNIQUE NOT NULL,
    `first_name`      varchar(150),
    `email`           varchar(300) UNIQUE,
    `surname`         varchar(150),
    `password`        char(128)           NOT NULL,
    `date_joined`     datetime,
    `last_login`      datetime,
    `is_super_user`   boolean,
    `profile_picture` varchar(500),
    `pixels_placed`   int,
    `next_time_pixel` datetime,
    `free_pixels`     int
);

CREATE TABLE `chat_room`
(
    `id`              integer PRIMARY KEY AUTO_INCREMENT,
    `name`            varchar(150) NOT NULL,
    `owner_id`        integer      NOT NULL,
    `created_at`      datetime     NOT NULL,
    `description`     text,
    `is_private`      boolean,
    `profile_picture` varchar(500)
);

CREATE TABLE `moderator`
(
    `id`           integer PRIMARY KEY AUTO_INCREMENT,
    `user_id`      integer NOT NULL,
    `chat_room_id` integer NOT NULL
);

CREATE TABLE `permission`
(
    `id`          integer PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(500) NOT NULL,
    `description` text
);

CREATE TABLE `moderator_permission`
(
    `id`            integer PRIMARY KEY AUTO_INCREMENT,
    `permission_id` integer NOT NULL,
    `moderator_id`  integer
);

CREATE TABLE `chat_room_user`
(
    `id`           integer PRIMARY KEY AUTO_INCREMENT,
    `chat_room_id` integer NOT NULL,
    `user_id`      integer NOT NULL
);

CREATE TABLE `message`
(
    `id`           integer PRIMARY KEY AUTO_INCREMENT,
    `user_id`      integer NOT NULL,
    `chat_room_id` integer NOT NULL,
    `content`      text    NOT NULL,
    `sent_date`    datetime
);

CREATE TABLE `pixel`
(
    `id`                     int PRIMARY KEY AUTO_INCREMENT,
    `x_position`             integer,
    `y_position`             integer,
    `color_id`               int NOT NULL,
    `user_id`                int,
    `last_updated`           datetime,
    `number_of_times_placed` int
);

CREATE TABLE `color`
(
    `id`       integer PRIMARY KEY AUTO_INCREMENT,
    `name`     varchar(200),
    `hex_code` char(7) NOT NULL
);

CREATE TABLE `games_list`
(
    `id`           int(11) NOT NULL,
    `name`         varchar(255) DEFAULT NULL,
    `explain_text` text,
    `difficulty`   int(11) DEFAULT NULL,
    `times`        int(11) DEFAULT NULL,
    `code`         int(11) DEFAULT NULL
);

CREATE TABLE `games_play`
(
    `id`              int(11) NOT NULL,
    `game_code`       int(11) DEFAULT NULL,
    `soluce`          text,
    `max_soluce_time` datetime DEFAULT NULL,
    `user_id`         int(11) DEFAULT NULL
);

CREATE TABLE `games_soluce`
(
    `id`        int(11) NOT NULL,
    `game_code` int(11) DEFAULT NULL,
    `soluce`    text
);

CREATE TABLE `guess_the_number`
(
    `id`                    integer PRIMARY KEY AUTO_INCREMENT,
    `number_to_find`        int(11) NOT NULL,
    `number_of_times_tried` integer NOT NULL,
    `min_value`             int (11) DEFAULT NULL,
    `max_value`             int(11) DEFAULT NULL
);

CREATE TABLE `files`
(
    `id`   int(11) NOT NULL,
    `url`  varchar(500),
    `size` int
);


ALTER TABLE `chat_room`
    ADD FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`);

ALTER TABLE `moderator`
    ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `moderator`
    ADD FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`);

ALTER TABLE `moderator_permission`
    ADD FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`);

ALTER TABLE `moderator_permission`
    ADD FOREIGN KEY (`moderator_id`) REFERENCES `moderator` (`id`);

ALTER TABLE `chat_room_user`
    ADD FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`);

ALTER TABLE `chat_room_user`
    ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `message`
    ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `message`
    ADD FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`);

ALTER TABLE `pixel`
    ADD FOREIGN KEY (`color_id`) REFERENCES `color` (`id`);

ALTER TABLE `pixel`
    ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `games_list`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `games_list`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `games_play`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `games_play`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `games_soluce`
    ADD PRIMARY KEY (`id`);

-- create
-- debug users
INSERT INTO user (username, password)
VALUES ('user1', '$2y$10$2o9ZtmWevHrMFFn1g9BYSOnpUyq6PxcL8GFl6DQpTy8uNssgaUEeq');

INSERT INTO user (username, password)
VALUES ('user2', '$2y$10$2o9ZtmWevHrMFFn1g9BYSOnpUyq6PxcL8GFl6DQpTy8uNssgaUEeq');

INSERT INTO user (username, password)
VALUES ('user3', '$2y$10$2o9ZtmWevHrMFFn1g9BYSOnpUyq6PxcL8GFl6DQpTy8uNssgaUEeq');

INSERT INTO user (username, password)
VALUES ('user4', '$2y$10$2o9ZtmWevHrMFFn1g9BYSOnpUyq6PxcL8GFl6DQpTy8uNssgaUEeq');



-- create
-- debug chat rooms
INSERT INTO chat_room (name, owner_id, created_at)
VALUES ('chat_room1', 1, '2018-01-01 00:00:00');
INSERT INTO chat_room (name, owner_id, created_at)
VALUES ('chat_room2', 2, '2018-01-01 00:00:00');

INSERT INTO chat_room (name, owner_id, created_at, description)
VALUES ('chat_room3', 3, '2018-01-01 00:00:00', 'This is a chat room for testing');

-- create debug chatroom relations
-- chat_room1 has user1, user2, user3
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (1, 1);
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (1, 2);
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (1, 3);
-- chat_room2 has user1, user2
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (2, 1);
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (2, 2);
-- chat_room3 has user3, user2, user4
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (3, 3);
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (3, 2);
INSERT INTO chat_room_user (chat_room_id, user_id)
VALUES (3, 4);
-- debug messages
INSERT INTO message (user_id, chat_room_id, content, sent_date)
VALUES (1, 1, 'Hello world!', '2016-01-01 00:00:00');
INSERT INTO message (user_id, chat_room_id, content, sent_date)
VALUES (2, 1, 'HELLO BACK', '2016-01-01 00:00:01');
INSERT INTO message (user_id, chat_room_id, content, sent_date)
VALUES (4, 2, 'HELLO 1!', '2016-01-01 00:00:01');
INSERT INTO message (user_id, chat_room_id, content, sent_date)
VALUES (3, 2, 'HELLO 2!', '2016-01-01 00:00:01');

-- insert 10 test colors
INSERT INTO color (name, hex_code)
VALUES ('red', '#FF0000');
INSERT INTO color (name, hex_code)
VALUES ('green', '#00FF00');
INSERT INTO color (name, hex_code)
VALUES ('blue', '#0000FF');
INSERT INTO color (name, hex_code)
VALUES ('yellow', '#FFFF00');
INSERT INTO color (name, hex_code)
VALUES ('black', '#000000');
INSERT INTO color (name, hex_code)
VALUES ('white', '#FFFFFF');
INSERT INTO color (name, hex_code)
VALUES ('orange', '#FFA500');
INSERT INTO color (name, hex_code)
VALUES ('purple', '#800080');
INSERT INTO color (name, hex_code)
VALUES ('pink', '#FFC0CB');
INSERT INTO color (name, hex_code)
VALUES ('brown', '#A52A2A');
INSERT INTO color (name, hex_code)
VALUES ('grey', '#808080');
INSERT INTO color (name, hex_code)
VALUES ('cyan', '#00FFFF');
INSERT INTO color (name, hex_code)
VALUES ('magenta', '#FF00FF');
INSERT INTO color (name, hex_code)
VALUES ('turquoise', '#40E0D0');
INSERT INTO color (name, hex_code)
VALUES ('gold', '#FFD700');
INSERT INTO color (name, hex_code)
VALUES ('silver', '#C0C0C0');


-- insert 100 pixels in a rectangle of size 100x100
-- with random colors
-- and random positions
-- and random users

INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (1, 1, 1, 1, 1, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (2, 2, 2, 2, 2, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (3, 3, 3, 3, 3, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (4, 4, 4, 4, 4, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (5, 5, 5, 5, 1, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (6, 6, 6, 6, 2, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (7, 7, 7, 7, 3, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (8, 8, 8, 8, 4, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (9, 8, 1, 1, 1, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (10, 8, 2, 2, 2, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (11, 8, 3, 3, 3, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (12, 8, 4, 4, 4, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (13, 8, 5, 5, 1, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (14, 8, 6, 6, 2, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (15, 8, 7, 7, 3, '2018-01-01 00:00:00', 1);
INSERT INTO pixel (id, x_position, y_position, color_id, user_id, last_updated, number_of_times_placed)
VALUES (16, 8, 8, 8, 4, '2018-01-01 00:00:00', 1);

INSERT INTO `games_soluce` (`id`, `game_code`, `soluce`)
VALUES (1, 1, 'test');
INSERT INTO `games_soluce` (`id`, `game_code`, `soluce`)
VALUES (2, 1, 'test2');
INSERT INTO `games_soluce` (`id`, `game_code`, `soluce`)
VALUES (3, 2, 'test');
INSERT INTO `games_soluce` (`id`, `game_code`, `soluce`)
VALUES (4, 2, 'test');
INSERT INTO `games_soluce` (`id`, `game_code`, `soluce`)
VALUES (5, 3, 'test3');
INSERT INTO `games_soluce` (`id`, `game_code`, `soluce`)
VALUES (6, 3, 'tezt');



CREATE INDEX pixel_position_index ON pixel (x_position, y_position);
CREATE INDEX pixel_color_index ON pixel (color_id);
CREATE INDEX pixel_user_index ON pixel (user_id);
