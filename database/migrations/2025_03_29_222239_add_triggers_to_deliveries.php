<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggersToDeliveries extends Migration
{
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER asignar_estado_invoice
            AFTER INSERT ON deliveries
            FOR EACH ROW
            BEGIN
                UPDATE invoices
                SET status = 'Assigned'
                WHERE id = NEW.invoice_id;
            END;
        ");
    }

    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS asignar_estado_invoice;");
    }
}
