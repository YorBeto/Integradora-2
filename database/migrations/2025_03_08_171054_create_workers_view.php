<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE VIEW workers_view AS
            SELECT
                workers.id,
                users.email,
                people.name,
                people.last_name,
                people.birth_date,
                TIMESTAMPDIFF(YEAR, people.birth_date, CURDATE()) AS age,
                people.phone,
                COUNT(d.id) AS assigned_orders,
                workers.RFID,
                workers.RFC,
                workers.NSS,
                users.activate,
                workers.deleted_at
            FROM
                workers
            INNER JOIN people ON workers.person_id = people.id
            INNER JOIN users ON people.user_id = users.id
            LEFT JOIN deliveries d ON workers.id = d.worker_id AND d.status = 'Pending'
            GROUP BY
                workers.id,
                users.email,
                people.name,
                people.last_name,
                people.birth_date,
                people.phone,
                workers.RFID,
                workers.RFC,
                workers.NSS,
                users.activate,
                workers.deleted_at;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers_view');
    }
};
