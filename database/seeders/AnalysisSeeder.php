<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $analyses = [
            ['name'=>'Hemograma completo','area'=>'Hematología','price'=>80.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'VSG (Velocidad de sedimentación)','area'=>'Hematología','price'=>40.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'Conteo de plaquetas','area'=>'Hematología','price'=>50.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'Tinción de frotis / Hematología microscópica','area'=>'Hematología','price'=>60.00,'duration_minutes'=>45,'status'=>1],

            ['name'=>'Glucosa en ayunas','area'=>'Química clínica','price'=>35.00,'duration_minutes'=>15,'status'=>1],
            ['name'=>'Glucosa postprandial','area'=>'Química clínica','price'=>40.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'HbA1c (Hemoglobina glicosilada)','area'=>'Química clínica','price'=>150.00,'duration_minutes'=>120,'status'=>1],

            ['name'=>'Perfil lipídico (Colesterol total, HDL, LDL, TG)','area'=>'Química clínica','price'=>120.00,'duration_minutes'=>60,'status'=>1],
            ['name'=>'Colesterol total','area'=>'Química clínica','price'=>44.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'Triglicéridos','area'=>'Química clínica','price'=>45.00,'duration_minutes'=>30,'status'=>1],

            ['name'=>'Urea','area'=>'Química clínica','price'=>40.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'Creatinina','area'=>'Química clínica','price'=>44.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'Depuración de creatinina (24h)','area'=>'Química clínica','price'=>130.00,'duration_minutes'=>1440,'status'=>1],

            ['name'=>'Pruebas hepáticas (TGO/AST, TGP/ALT, Bilirrubina)','area'=>'Química clínica','price'=>120.00,'duration_minutes'=>60,'status'=>1],
            ['name'=>'Bilirrubina total','area'=>'Química clínica','price'=>50.00,'duration_minutes'=>30,'status'=>1],

            ['name'=>'Ácido úrico','area'=>'Química clínica','price'=>45.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'Electrolitos (Na, K, Cl)','area'=>'Química clínica','price'=>80.00,'duration_minutes'=>60,'status'=>1],
            ['name'=>'Proteínas totales / Albúmina','area'=>'Química clínica','price'=>60.00,'duration_minutes'=>30,'status'=>1],

            ['name'=>'PCR (Proteína C Reactiva) cuantitativa','area'=>'Inflamación','price'=>80.00,'duration_minutes'=>60,'status'=>1],
            ['name'=>'Ferritina','area'=>'Bioquímica','price'=>120.00,'duration_minutes'=>120,'status'=>1],
            ['name'=>'Hierro sérico','area'=>'Bioquímica','price'=>70.00,'duration_minutes'=>60,'status'=>1],

            ['name'=>'TSH','area'=>'Hormonas','price'=>130.00,'duration_minutes'=>120,'status'=>1],
            ['name'=>'T4 libre','area'=>'Hormonas','price'=>140.00,'duration_minutes'=>120,'status'=>1],

            ['name'=>'Perfil renal completo','area'=>'Química clínica','price'=>180.00,'duration_minutes'=>90,'status'=>1],

            ['name'=>'EGO (Examen general de orina)','area'=>'Orina','price'=>35.00,'duration_minutes'=>15,'status'=>1],
            ['name'=>'Urocultivo','area'=>'Microbiología','price'=>110.00,'duration_minutes'=>2880,'status'=>1],
            ['name'=>'Coprocultivo','area'=>'Microbiología','price'=>90.00,'duration_minutes'=>2880,'status'=>1],
            ['name'=>'Amebas en fresco','area'=>'Microbiología','price'=>15.00,'duration_minutes'=>15,'status'=>1],

            ['name'=>'VIH (ELISA)','area'=>'Serología','price'=>120.00,'duration_minutes'=>120,'status'=>1],
            ['name'=>'VDRL / RPR','area'=>'Serología','price'=>40.00,'duration_minutes'=>30,'status'=>1],
            ['name'=>'HBsAg (Hepatitis B)','area'=>'Serología','price'=>90.00,'duration_minutes'=>60,'status'=>1],
            ['name'=>'Anticuerpos anti-HCV','area'=>'Serología','price'=>110.00,'duration_minutes'=>60,'status'=>1],

            ['name'=>'Prueba rápida antígeno COVID-19','area'=>'Pruebas rápidas','price'=>120.00,'duration_minutes'=>20,'status'=>1],
            ['name'=>'PCR SARS-CoV-2','area'=>'Biología molecular','price'=>450.00,'duration_minutes'=>360,'status'=>1],
            ['name'=>'PCR panel respiratorio','area'=>'Biología molecular','price'=>1000.00,'duration_minutes'=>720,'status'=>1],

            ['name'=>'H. pylori (antígeno o serología)','area'=>'Gastroenterología','price'=>130.00,'duration_minutes'=>120,'status'=>1],
            ['name'=>'Toxo IgG/IgM','area'=>'Serología','price'=>140.00,'duration_minutes'=>120,'status'=>1],

            ['name'=>'PSA Total','area'=>'Marcadores tumorales','price'=>120.00,'duration_minutes'=>60,'status'=>1],
            ['name'=>'Perfil tiroideo completo','area'=>'Hormonas','price'=>300.00,'duration_minutes'=>180,'status'=>1],

            ['name'=>'Perfil preoperatorio básico','area'=>'Paquetes','price'=>220.00,'duration_minutes'=>90,'status'=>1],
            ['name'=>'Panel metabólico ampliado','area'=>'Paquetes','price'=>420.00,'duration_minutes'=>180,'status'=>1],

            ['name'=>'Cultivo de secreciones','area'=>'Microbiología','price'=>150.00,'duration_minutes'=>4320,'status'=>1],
            ['name'=>'Prueba de embarazo (Beta hCG cuantitativa)','area'=>'Serología','price'=>80.00,'duration_minutes'=>60,'status'=>1],
        ];

        foreach ($analyses as $analysis) {
            DB::table('analyses')->insert([
                'name' => $analysis['name'],
                'area' => $analysis['area'],
                'price' => $analysis['price'],
                'duration_minutes' => $analysis['duration_minutes'],
                'status' => $analysis['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
