<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insertar ÁREAS
        DB::table('areas')->insert([
            ['id' => 1, 'name' => 'Tipos de C. por Material'],
            ['id' => 2, 'name' => 'Tamaño y Presentación'],
            ['id' => 3, 'name' => 'Estado del Material'],
        ]);

        // Insertar PRODUCTOS con exit_code numérico
        DB::table('products')->insert([
            // Tipos de C. por material
            ['name' => 'C. Corrugado', 'area_id' => 1, 'description' => 'Para empaques resistentes', 'stock_weight' => 100.5, 'exit_code' => 1001],
            ['name' => 'C. Compacto', 'area_id' => 1, 'description' => 'Para cajas más rígidas', 'stock_weight' => 80.2, 'exit_code' => 1002],
            ['name' => 'C. Reciclado', 'area_id' => 1, 'description' => 'Para procesos sostenibles', 'stock_weight' => 60.0, 'exit_code' => 1003],
            ['name' => 'C. Plastificado', 'area_id' => 1, 'description' => 'Resistente a la humedad', 'stock_weight' => 90.0, 'exit_code' => 1004],
            ['name' => 'C. Kraft', 'area_id' => 1, 'description' => 'Para empaques ecológicos', 'stock_weight' => 75.5, 'exit_code' => 1005],

            // Tamaño y presentación
            ['name' => 'Hojas de C. Grandes', 'area_id' => 2, 'description' => 'Para corte y personalización', 'stock_weight' => 200.0, 'exit_code' => 2001],
            ['name' => 'Bobinas de C.', 'area_id' => 2, 'description' => 'Para fábricas que usan rollos', 'stock_weight' => 150.3, 'exit_code' => 2002],
            ['name' => 'C. en Placas', 'area_id' => 2, 'description' => 'Pre-cortado para diferentes usos', 'stock_weight' => 130.0, 'exit_code' => 2003],
            ['name' => 'C. en Paquetes', 'area_id' => 2, 'description' => 'Para venta en cantidades menores', 'stock_weight' => 50.0, 'exit_code' => 2004],

            // Estado del material
            ['name' => 'C. Nuevo', 'area_id' => 3, 'description' => 'Recién adquirido', 'stock_weight' => 300.0, 'exit_code' => 3001],
            ['name' => 'C. en Procesamiento', 'area_id' => 3, 'description' => 'Corte y doblado', 'stock_weight' => 180.0, 'exit_code' => 3002],
            ['name' => 'C. Reciclable', 'area_id' => 3, 'description' => 'Material dañado para reprocesar', 'stock_weight' => 100.0, 'exit_code' => 3003],
        ]);
    }
}
