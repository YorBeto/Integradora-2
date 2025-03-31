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
            ['id' => 1, 'name' => 'Área A'],
            ['id' => 2, 'name' => 'Área B'],
            ['id' => 3, 'name' => 'Área C'],
            ['id' => 4, 'name' => 'Área D'],
            ['id' => 5, 'name' => 'Área E'],
            ['id' => 6, 'name' => 'Área F'],
        ]);

        // Insertar PRODUCTOS con los nombres originales de las áreas
        DB::table('products')->insert([
            // Área 1: C. Corrugado
            ['name' => 'C. Corrugado', 'area_id' => 1, 'description' => 'Producto de Área A', 'stock_weight' => 10000.0, 'exit_code' => 1011, 'image' => 'https://equiposikra.s3.us-east-2.amazonaws.com/productos/Carton+Corrugado.png'],

            // Área 2: C. Compacto
            ['name' => 'C. Compacto', 'area_id' => 2, 'description' => 'Producto de Área B', 'stock_weight' => 8000.0, 'exit_code' => 1021, 'image' => 'https://equiposikra.s3.us-east-2.amazonaws.com/productos/Carton+Compacto.png'],

            // Área 3: C. Reciclado
            ['name' => 'C. Reciclado', 'area_id' => 3, 'description' => 'Producto de Área C', 'stock_weight' => 9000.0, 'exit_code' => 1031, 'image' => 'https://equiposikra.s3.us-east-2.amazonaws.com/productos/Carton+reciclado.png'],

            // Área 4: C. Plastificado
            ['name' => 'C. Plastificado', 'area_id' => 4, 'description' => 'Producto de Área D', 'stock_weight' => 7000.0, 'exit_code' => 1041, 'image' => 'https://equiposikra.s3.us-east-2.amazonaws.com/productos/Carton+plastificado.png'],

            // Área 5: C. Kraft
            ['name' => 'C. Kraft', 'area_id' => 5, 'description' => 'Producto de Área E', 'stock_weight' => 11000.0, 'exit_code' => 1051, 'image' => 'https://equiposikra.s3.us-east-2.amazonaws.com/productos/Carton+kraft.png'],

            // Área 6: C. en Procesamiento
            ['name' => 'C. en Procesamiento', 'area_id' => 6, 'description' => 'Producto de Área F', 'stock_weight' => 10000.0, 'exit_code' => 1061, 'image' => 'https://equiposikra.s3.us-east-2.amazonaws.com/productos/Carton+reciclable.png'],
        ]);
    }
}
