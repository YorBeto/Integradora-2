<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDeliveryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW delivery_view AS
            SELECT 
                d.invoice_id,
                d.worker_id,
                d.delivery_date,
                GROUP_CONCAT(pr.name SEPARATOR ', ') AS products,
                p.name AS worker_name,
                d.carrier,
                d.status
            FROM deliveries d
            JOIN workers w ON d.worker_id = w.id
            JOIN people p ON w.person_id = p.id
            JOIN delivery_details dd ON d.id = dd.delivery_id
            JOIN products pr ON dd.product_id = pr.id
            GROUP BY d.id, d.invoice_id, d.worker_id, p.name, d.delivery_date, d.carrier, d.status;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS delivery_view");
    }
}
