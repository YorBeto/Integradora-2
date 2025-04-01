<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::unprepared("
            CREATE PROCEDURE CreateDeliveryFromInvoice(
                IN p_invoice_id INT,
                IN p_worker_id INT,
                IN p_carrier VARCHAR(100)
            )
            BEGIN
                DECLARE v_delivery_id INT;
                DECLARE v_json_details JSON;
                DECLARE v_total_items INT;
                DECLARE v_index INT DEFAULT 0;
                DECLARE v_product_name VARCHAR(255);
                DECLARE v_quantity_weight FLOAT;
                DECLARE v_product_id INT;

                SELECT details INTO v_json_details FROM invoices WHERE id = p_invoice_id;

                INSERT INTO deliveries (invoice_id, worker_id, delivery_date, carrier, status)
                VALUES (p_invoice_id, p_worker_id, CURDATE(), p_carrier, 'Completed');

                SET v_delivery_id = LAST_INSERT_ID();

                SET v_total_items = JSON_LENGTH(JSON_EXTRACT(v_json_details, '$.items'));

                WHILE v_index < v_total_items DO
                    SET v_product_name = JSON_UNQUOTE(JSON_EXTRACT(v_json_details, CONCAT('$.items[', v_index, '].name')));

                    SET v_quantity_weight = JSON_UNQUOTE(JSON_EXTRACT(v_json_details, CONCAT('$.items[', v_index, '].grams')));

                    SELECT id INTO v_product_id FROM products WHERE name = v_product_name LIMIT 1;

                    IF v_product_id IS NOT NULL THEN
                        INSERT INTO delivery_details (delivery_id, product_id, quantity_weight)
                        VALUES (v_delivery_id, v_product_id, v_quantity_weight);
                    END IF;

                    SET v_index = v_index + 1;
                END WHILE;
            END;

        ");

    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS CreateDeliveryFromInvoice");
    }
};
