<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pacientes = [
            ['name' => 'María', 'last_name' => 'Gonzales López', 'ci' => '6543210', 'birth_date' => '1992-05-12', 'age' => 32, 'phone' => '71122334', 'email' => 'maria.gonzales@example.com', 'address' => 'Zona Central, Sucre'],
            ['name' => 'Juan', 'last_name' => 'Flores Quispe', 'ci' => '7123456', 'birth_date' => '1985-08-20', 'age' => 39, 'phone' => '72011223', 'email' => 'juan.flores@example.com', 'address' => 'Villa Armonía, Sucre'],
            ['name' => 'Luis', 'last_name' => 'Mamani Paco', 'ci' => '8012345', 'birth_date' => '1998-02-10', 'age' => 27, 'phone' => '78655432', 'email' => 'luis.mamani@example.com', 'address' => 'Alto San Pedro, La Paz'],
            ['name' => 'Ana', 'last_name' => 'Rojas Pérez', 'ci' => '7456321', 'birth_date' => '1990-11-05', 'age' => 34, 'phone' => '72533456', 'email' => 'ana.rojas@example.com', 'address' => 'Zona Norte, Cochabamba'],
            ['name' => 'Carla', 'last_name' => 'Cardozo Vaca', 'ci' => '7032145', 'birth_date' => '2000-01-22', 'age' => 25, 'phone' => '78944321', 'email' => 'carla.cardozo@example.com', 'address' => 'El Tejar, Sucre'],
            ['name' => 'Rodrigo', 'last_name' => 'Camacho Céspedes', 'ci' => '7256789', 'birth_date' => '1987-03-18', 'age' => 38, 'phone' => '77112233', 'email' => 'rodrigo.camacho@example.com', 'address' => 'San Roque, Sucre'],
            ['name' => 'Lucía', 'last_name' => 'Valenzuela Torres', 'ci' => '8321456', 'birth_date' => '1995-09-15', 'age' => 29, 'phone' => '76432155', 'email' => 'lucia.valenzuela@example.com', 'address' => 'Zona Muyurina, Cochabamba'],
            ['name' => 'Marco', 'last_name' => 'Salazar Gutiérrez', 'ci' => '7896541', 'birth_date' => '1993-06-06', 'age' => 31, 'phone' => '74567890', 'email' => 'marco.salazar@example.com', 'address' => 'Plan 3000, Santa Cruz'],
            ['name' => 'Sofía', 'last_name' => 'Rivera Flores', 'ci' => '7212349', 'birth_date' => '2002-12-01', 'age' => 23, 'phone' => '76221144', 'email' => 'sofia.rivera@example.com', 'address' => 'Zona Central, Sucre'],
            ['name' => 'Jorge', 'last_name' => 'Cortez Medina', 'ci' => '6954321', 'birth_date' => '1980-07-27', 'age' => 44, 'phone' => '79912345', 'email' => 'jorge.cortez@example.com', 'address' => 'Sopocachi, La Paz'],

            ['name' => 'Paola', 'last_name' => 'Fernández Lima', 'ci' => '7543210', 'birth_date' => '1994-10-11', 'age' => 30, 'phone' => '78888221', 'email' => 'paola.fernandez@example.com', 'address' => 'Zona Villa Fátima, La Paz'],
            ['name' => 'Diego', 'last_name' => 'Rentería Mercado', 'ci' => '7412569', 'birth_date' => '1999-04-14', 'age' => 26, 'phone' => '77123456', 'email' => 'diego.renteria@example.com', 'address' => 'Pampa de la Isla, Santa Cruz'],
            ['name' => 'Camila', 'last_name' => 'Ortiz Cabrera', 'ci' => '8123457', 'birth_date' => '2001-03-09', 'age' => 24, 'phone' => '78561234', 'email' => 'camila.ortiz@example.com', 'address' => 'Horno Kasa, Sucre'],
            ['name' => 'Fernando', 'last_name' => 'Villegas Suárez', 'ci' => '7321984', 'birth_date' => '1983-05-30', 'age' => 41, 'phone' => '74112233', 'email' => 'fernando.villegas@example.com', 'address' => 'Av. Busch, Santa Cruz'],
            ['name' => 'Gabriela', 'last_name' => 'Mendoza Aguilar', 'ci' => '7564981', 'birth_date' => '1997-09-08', 'age' => 27, 'phone' => '70011223', 'email' => 'gabriela.mendoza@example.com', 'address' => 'Zona Max Paredes, La Paz'],
            ['name' => 'Miguel', 'last_name' => 'Santos Quispe', 'ci' => '7893214', 'birth_date' => '1989-02-02', 'age' => 36, 'phone' => '73144556', 'email' => 'miguel.santos@example.com', 'address' => 'Zona Sud, Oruro'],
            ['name' => 'Valeria', 'last_name' => 'Paredes Arce', 'ci' => '7124596', 'birth_date' => '1996-07-18', 'age' => 28, 'phone' => '74556671', 'email' => 'valeria.paredes@example.com', 'address' => 'Av. Jaime Mendoza, Sucre'],
            ['name' => 'Hugo', 'last_name' => 'Navarro Céspedes', 'ci' => '7654982', 'birth_date' => '1988-12-24', 'age' => 36, 'phone' => '74991234', 'email' => 'hugo.navarro@example.com', 'address' => 'Zona Cala Cala, Cochabamba'],
            ['name' => 'Melisa', 'last_name' => 'López Vargas', 'ci' => '7542169', 'birth_date' => '1991-11-01', 'age' => 33, 'phone' => '78906543', 'email' => 'melisa.lopez@example.com', 'address' => 'Av. Las Américas, Sucre'],
            ['name' => 'Kevin', 'last_name' => 'Gutiérrez Rocha', 'ci' => '7987456', 'birth_date' => '1998-08-30', 'age' => 26, 'phone' => '72003456', 'email' => 'kevin.gutierrez@example.com', 'address' => 'Urbanización Patriota, Santa Cruz'],
        ];

        foreach ($pacientes as $paciente) {
            DB::table('pacientes')->insert([
                'name' => $paciente['name'],
                'last_name' => $paciente['last_name'],
                'ci' => $paciente['ci'],
                'birth_date' => $paciente['birth_date'],
                'age' => $paciente['age'],
                'phone' => $paciente['phone'],
                'email' => $paciente['email'],
                'address' => $paciente['address'],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
