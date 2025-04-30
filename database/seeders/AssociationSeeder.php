<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssociationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('associations')->insertOrIgnore([
            ['id' => 1, 'name' => 'Baja California', 'abbreviation' => 'BC'],
            ['id' => 2, 'name' => 'Baja California Sur', 'abbreviation' => 'BCS'],
            ['id' => 3, 'name' => 'Colima', 'abbreviation' => 'COL'],
            ['id' => 4, 'name' => 'Distrito Federal', 'abbreviation' => 'DF'],
            ['id' => 5, 'name' => 'Estado de México', 'abbreviation' => 'EDM'],
            ['id' => 6, 'name' => 'Guerrero', 'abbreviation' => 'GRO'],
            ['id' => 7, 'name' => 'Guanajuato', 'abbreviation' => 'GTO'],
            ['id' => 8, 'name' => 'Hidalgo', 'abbreviation' => 'HGO'],
            ['id' => 9, 'name' => 'Jalisco', 'abbreviation' => 'JAL'],
            ['id' => 10, 'name' => 'Michoacán', 'abbreviation' => 'MICH'],
            ['id' => 11, 'name' => 'Morelos', 'abbreviation' => 'MOR'],
            ['id' => 12, 'name' => 'Nayarit', 'abbreviation' => 'NAY'],
            ['id' => 13, 'name' => 'Nuevo León', 'abbreviation' => 'NL'],
            ['id' => 14, 'name' => 'Puebla', 'abbreviation' => 'PUE'],
            ['id' => 15, 'name' => 'Querétaro', 'abbreviation' => 'QRO'],
            ['id' => 16, 'name' => 'Quintana Roo', 'abbreviation' => 'Q. ROO'],
            ['id' => 17, 'name' => 'San Luis Potosí', 'abbreviation' => 'SLP'],
            ['id' => 18, 'name' => 'Sinaloa', 'abbreviation' => 'SIN'],
            ['id' => 19, 'name' => 'Sonora', 'abbreviation' => 'SON'],
            ['id' => 20, 'name' => 'Tabasco', 'abbreviation' => 'TAB'],
            ['id' => 21, 'name' => 'Tamaulipas', 'abbreviation' => 'TAMPS'],
            ['id' => 22, 'name' => 'Tlaxcala', 'abbreviation' => 'TLAX'],
            ['id' => 23, 'name' => 'Veracruz', 'abbreviation' => 'VER'],
            ['id' => 24, 'name' => 'Yucatán', 'abbreviation' => 'YUC'],
            ['id' => 25, 'name' => 'Zacatecas', 'abbreviation' => 'ZAC'],
            ['id' => 26, 'name' => 'Chiapas', 'abbreviation' => 'CHIS'],
            ['id' => 27, 'name' => 'Chihuahua', 'abbreviation' => 'CHIH'],
            ['id' => 28, 'name' => 'Durango', 'abbreviation' => 'DGO'],
            ['id' => 29, 'name' => 'Coahuila', 'abbreviation' => 'COAH'],
            ['id' => 30, 'name' => 'Oaxaca', 'abbreviation' => 'OAX'],
            ['id' => 31, 'name' => 'Aguascalientes', 'abbreviation' => 'AGS'],
            ['id' => 32, 'name' => 'Campeche', 'abbreviation' => 'CAMP'],
            ['id' => 33, 'name' => 'Instituto Politécnico Nacional', 'abbreviation' => 'IPN'],
            ['id' => 34, 'name' => 'Universidad Nacional Autónoma de México', 'abbreviation' => 'UNAM'],
            ['id' => 35, 'name' => 'Instituto Mexicano del Seguro Social', 'abbreviation' => 'IMSS'],
        ]);
    }
}
