<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpRegisterWorker extends Migration
{
    public function up()
    {
        DB::unprepared("
            CREATE PROCEDURE RegisterWorker(
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_name VARCHAR(255),
                IN p_last_name VARCHAR(255),
                IN p_birth_date DATE,
                IN p_phone VARCHAR(20),
                IN p_RFID VARCHAR(255),
                IN p_RFC VARCHAR(255),
                IN p_NSS VARCHAR(255)
            )
            BEGIN
                DECLARE v_user_id INT;
                DECLARE v_person_id INT;
                DECLARE v_worker_role_id INT;

                -- Get the role ID for 'worker'
                SELECT id INTO v_worker_role_id FROM roles WHERE name = 'user' LIMIT 1;

                -- Validate that the role exists
                IF v_worker_role_id IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'The worker role does not exist';
                END IF;

                -- Insert into the users table
                INSERT INTO users (email, password, created_at, updated_at)
                VALUES (p_email, p_password, NOW(), NOW());

                -- Get the last inserted user ID
                SET v_user_id = LAST_INSERT_ID();

                -- Insert into the people table
                INSERT INTO people (name, last_name, birth_date, phone, user_id, created_at, updated_at)
                VALUES (p_name, p_last_name, p_birth_date, p_phone, v_user_id, NOW(), NOW());

                -- Get the last inserted person ID
                SET v_person_id = LAST_INSERT_ID();

                -- Insert into the workers table
                INSERT INTO workers (RFID, RFC, NSS, person_id, created_at, updated_at)
                VALUES (p_RFID, p_RFC, p_NSS, v_person_id, NOW(), NOW());

                -- Insert into the role_user table with 'worker' role
                INSERT INTO role_user (role_id, user_id, created_at, updated_at)
                VALUES (v_worker_role_id, v_user_id, NOW(), NOW());
            END
        ");
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS RegisterWorker");
    }
}
