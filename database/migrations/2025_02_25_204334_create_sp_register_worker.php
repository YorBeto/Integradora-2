<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::unprepared("
            CREATE PROCEDURE RegisterWorker(
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_photo VARCHAR(255),
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

                -- Obtener el ID del rol 'worker'
                SELECT id INTO v_worker_role_id FROM roles WHERE name = 'worker' LIMIT 1;

                -- Insertar en la tabla users
                INSERT INTO users (email, password, profile_photo, created_at, updated_at)
                VALUES (p_email, p_password, p_photo, NOW(), NOW());

                -- Obtener el ID del usuario recién insertado
                SET v_user_id = LAST_INSERT_ID();

                -- Insertar en la tabla people
                INSERT INTO people (name, last_name, birth_date, phone, user_id, created_at, updated_at)
                VALUES (p_name, p_last_name, p_birth_date, p_phone, v_user_id, NOW(), NOW());

                -- Obtener el ID de la persona recién insertada
                SET v_person_id = LAST_INSERT_ID();

                -- Insertar en la tabla workers
                INSERT INTO workers (RFID, RFC, NSS, person_id, created_at, updated_at)
                VALUES (p_RFID, p_RFC, p_NSS, v_person_id, NOW(), NOW());

                -- Insertar en la tabla role_user con el rol de 'worker'
                INSERT INTO role_user (role_id, user_id, created_at, updated_at)
                VALUES (v_worker_role_id, v_user_id, NOW(), NOW());
            END;
        ");
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS RegisterWorker");
    }
};
