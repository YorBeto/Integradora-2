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

            -- Verificar si el usuario ya existe (incluyendo eliminados lógicamente)
            SELECT id INTO v_user_id FROM users WHERE email = p_email LIMIT 1;

            IF v_user_id IS NOT NULL THEN
                -- Restaurar el usuario si está eliminado lógicamente
                UPDATE users SET deleted_at = NULL, password = p_password, profile_photo = p_photo, updated_at = NOW() WHERE id = v_user_id;

                -- Verificar si la persona asociada ya existe
                SELECT id INTO v_person_id FROM people WHERE user_id = v_user_id LIMIT 1;

                IF v_person_id IS NOT NULL THEN
                    -- Restaurar la persona si está eliminada lógicamente
                    UPDATE people SET deleted_at = NULL, name = p_name, last_name = p_last_name, birth_date = p_birth_date, phone = p_phone, updated_at = NOW() WHERE id = v_person_id;

                    -- Verificar si el trabajador asociado ya existe
                    IF EXISTS (SELECT 1 FROM workers WHERE person_id = v_person_id) THEN
                        -- Restaurar el trabajador si está eliminado lógicamente
                        UPDATE workers SET deleted_at = NULL, RFID = p_RFID, RFC = p_RFC, NSS = p_NSS, updated_at = NOW() WHERE person_id = v_person_id;
                    ELSE
                        -- Insertar un nuevo trabajador
                        INSERT INTO workers (RFID, RFC, NSS, person_id, created_at, updated_at)
                        VALUES (p_RFID, p_RFC, p_NSS, v_person_id, NOW(), NOW());
                    END IF;
                END IF;
            ELSE
                -- Insertar un nuevo usuario
                INSERT INTO users (email, password, profile_photo, created_at, updated_at)
                VALUES (p_email, p_password, p_photo, NOW(), NOW());

                -- Obtener el ID del usuario recién insertado
                SET v_user_id = LAST_INSERT_ID();

                -- Insertar una nueva persona
                INSERT INTO people (name, last_name, birth_date, phone, user_id, created_at, updated_at)
                VALUES (p_name, p_last_name, p_birth_date, p_phone, v_user_id, NOW(), NOW());

                -- Obtener el ID de la persona recién insertada
                SET v_person_id = LAST_INSERT_ID();

                -- Insertar un nuevo trabajador
                INSERT INTO workers (RFID, RFC, NSS, person_id, created_at, updated_at)
                VALUES (p_RFID, p_RFC, p_NSS, v_person_id, NOW(), NOW());
            END IF;

            -- Verificar si el usuario ya tiene el rol 'worker'
            IF NOT EXISTS (SELECT 1 FROM role_user WHERE role_id = v_worker_role_id AND user_id = v_user_id) THEN
                -- Insertar en la tabla role_user con el rol de 'worker'
                INSERT INTO role_user (role_id, user_id, created_at, updated_at)
                VALUES (v_worker_role_id, v_user_id, NOW(), NOW());
            END IF;
        END;
        ");
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS RegisterWorker");
    }
};
