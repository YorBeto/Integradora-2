<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMarkDeletedWorkerProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE PROCEDURE MarkDeletedWorker(IN p_worker_id INT)
        BEGIN
            UPDATE workers
            SET deleted_at = NOW()
            WHERE id = p_worker_id;

            UPDATE people
            SET deleted_at = NOW()
            WHERE id = (SELECT person_id FROM workers WHERE id = p_worker_id);

            UPDATE users
            SET deleted_at = NOW()
            WHERE id = (SELECT user_id FROM people WHERE id = (SELECT person_id FROM workers WHERE id = p_worker_id));

            UPDATE role_user
            SET deleted_at = NOW()
            WHERE user_id = (SELECT user_id FROM people WHERE id = (SELECT person_id FROM workers WHERE id = p_worker_id));
        END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS MarkDeletedWorker');
    }
}