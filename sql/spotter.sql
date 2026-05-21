CREATE TABLE IF NOT EXISTS exercise(
    id bigint NOT NULL,
    name varchar(60) NOT NULL,
    description varchar(200) DEFAULT NULL,
    weight_required boolean NOT NULL DEFAULT 0,
    image_url varchar(255) DEFAULT NULL
);

ALTER TABLE exercise ADD PRIMARY KEY (id);
ALTER TABLE exercise MODIFY id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS workout(
    id bigint NOT NULL,
    workout_date date NOT NULL,
    workout_length SMALLINT DEFAULT NULL,
    exercise_id bigint NOT NULL
);

ALTER TABLE workout ADD PRIMARY KEY (id);
ALTER TABLE workout MODIFY id bigint NOT NULL AUTO_INCREMENT;

ALTER TABLE workout ADD CONSTRAINT fk_workout_exercise_id 
FOREIGN KEY (exercise_id) REFERENCES exercise(id);

CREATE TABLE IF NOT EXISTS workout_set(
    id bigint NOT NULL,
    rep_amount tinyint (2) NOT NULL,
    weight_amount decimal(5,2) NOT NULL,
    to_failure boolean NOT NULL DEFAULT 0,
    workout_id bigint NOT NULL
);

ALTER TABLE workout_set ADD PRIMARY KEY (id);
ALTER TABLE workout_set MODIFY id bigint NOT NULL AUTO_INCREMENT;

ALTER TABLE workout_set ADD CONSTRAINT fk_workout_set_workout_id
FOREIGN KEY (workout_id) REFERENCES workout(id);

-- Insert exercises with weight_required (1 = TRUE, 0 = FALSE)
INSERT INTO exercise (name, weight_required) VALUES
('Leg Raises', 0),
('Ab Crunch Machine', 1),
('Leg Press', 1),
('Squats', 1),
('Leg Extension', 1),
('Leg Curl', 1),
('Hip Thrust', 1),
('Calf Raise on Leg Press', 1),

('Dips', 0),
('Incline Dumbbell Press', 1),
('Overhead Triceps Extension', 1),
('Triceps Pulldown', 1),
('Cable Fly', 1),
('Shoulder Press Machine', 1),
('Rear Deltoid Machine', 1),

('Push-ups', 0),
('Lat Pulldown', 1),
('Seated Machine Row', 1),
('Biceps Curl Machine', 1),
('EZ Bar Curl', 1),
('EZ Bar Reverse Curl', 1),
('Shrugs with Barbell', 1),
('Pinch Plates', 1),
('Wrist Curl Dumbbell seated', 1);


