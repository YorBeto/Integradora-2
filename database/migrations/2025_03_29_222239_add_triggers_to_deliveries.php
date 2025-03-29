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

        DB::unprepared("
            CREATE TRIGGER actualizar_stock_delivery
            AFTER UPDATE ON deliveries
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'Completed' THEN
                    UPDATE products p
                    JOIN delivery_details dd ON p.id = dd.product_id
                    SET p.stock_weight = p.stock_weight - dd.quantity_weight
                    WHERE dd.delivery_id = NEW.id;
                END IF;
            END;
        ");
    }

    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS asignar_estado_invoice;");
        DB::unprepared("DROP TRIGGER IF EXISTS actualizar_stock_delivery;");
    }
}
