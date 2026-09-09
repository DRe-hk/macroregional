<?php

namespace Database\Seeders;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Serie;
use App\Models\Torneo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Info del Torneo
        Torneo::updateOrCreate(
            ['id' => 1],
            [
                'nombre' => 'Competencia Deportiva Macroregional 2026',
                'subtitulo' => 'Torneo Deportivo Macroregional de Educación',
                'organizador' => 'Comisión Macroregional DREP',
                'sede_principal' => 'Puno / Juliaca',
                'anio' => 2026,
                'avance_porcentaje' => 0,
            ]
        );

        // 2. Delegaciones
        $delegaciones = [
            ['id' => 'drep', 'nombre' => 'DREP Sede Central', 'siglas' => 'DREP', 'provincia' => 'Puno'],
            ['id' => 'puno', 'nombre' => 'UGEL Puno', 'siglas' => 'Puno', 'provincia' => 'Puno'],
            ['id' => 'san-roman', 'nombre' => 'UGEL San Román', 'siglas' => 'San Román', 'provincia' => 'San Román'],
            ['id' => 'azangaro', 'nombre' => 'UGEL Azángaro', 'siglas' => 'Azángaro', 'provincia' => 'Azángaro'],
            ['id' => 'melgar', 'nombre' => 'UGEL Melgar', 'siglas' => 'Melgar', 'provincia' => 'Melgar'],
            ['id' => 'collao', 'nombre' => 'UGEL Collao', 'siglas' => 'Collao', 'provincia' => 'El Collao'],
            ['id' => 'chucuito', 'nombre' => 'UGEL Chucuito', 'siglas' => 'Chucuito', 'provincia' => 'Chucuito'],
            ['id' => 'yunguyo', 'nombre' => 'UGEL Yunguyo', 'siglas' => 'Yunguyo', 'provincia' => 'Yunguyo'],
            ['id' => 'huancane', 'nombre' => 'UGEL Huancané', 'siglas' => 'Huancané', 'provincia' => 'Huancané'],
            ['id' => 'lampa', 'nombre' => 'UGEL Lampa', 'siglas' => 'Lampa', 'provincia' => 'Lampa'],
            ['id' => 'carabaya', 'nombre' => 'UGEL Carabaya', 'siglas' => 'Carabaya', 'provincia' => 'Carabaya'],
            ['id' => 'sandia', 'nombre' => 'UGEL Sandia', 'siglas' => 'Sandia', 'provincia' => 'Sandia'],
            ['id' => 'putina', 'nombre' => 'UGEL Putina', 'siglas' => 'Putina', 'provincia' => 'San Antonio de Putina'],
            ['id' => 'moho', 'nombre' => 'UGEL Moho', 'siglas' => 'Moho', 'provincia' => 'Moho'],
            ['id' => 'crucero', 'nombre' => 'UGEL Crucero', 'siglas' => 'Crucero', 'provincia' => 'Carabaya'],
        ];

        foreach ($delegaciones as $del) {
            Delegacion::updateOrCreate(
                ['id' => $del['id']],
                [
                    'nombre' => $del['nombre'],
                    'siglas' => $del['siglas'],
                    'provincia' => $del['provincia'],
                    'puntos' => 0,
                    'pj' => 0,
                    'pg' => 0,
                    'pe' => 0,
                    'pp' => 0,
                    'gf' => 0,
                    'gc' => 0,
                    'dg' => 0,
                ]
            );
        }

        // 3. Disciplinas Deportivas y sus Series
        $disciplinas = [
            [
                'id' => 'futbol-libre',
                'slug' => 'futbol-libre',
                'nombre' => 'Fútbol Libre',
                'categoria' => 'Fútbol',
                'color_acento' => '#2563eb',
                'foto_url' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80',
                'descripcion' => 'Torneo de fútbol varones categoría libre en cancha reglamentaria.',
                'sede_principal' => 'Estadio Enrique Torres Belón · Puno',
                'series' => [
                    ['letra' => 'A', 'nombre' => 'Serie A', 'sede_nombre' => 'Estadio Enrique Torres Belón'],
                    ['letra' => 'B', 'nombre' => 'Serie B', 'sede_nombre' => 'Estadio Carolino Puno'],
                ],
            ],
            [
                'id' => 'voley-damas',
                'slug' => 'voley-damas',
                'nombre' => 'Vóley Damas',
                'categoria' => 'Vóley',
                'color_acento' => '#e11d48',
                'foto_url' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=800&auto=format&fit=crop&q=80',
                'descripcion' => 'Torneo femenino oficial de voleibol en coliseo cerrado.',
                'sede_principal' => 'Coliseo Eduardo Rodríguez Ponce de León',
                'series' => [
                    ['letra' => 'A', 'nombre' => 'Serie A', 'sede_nombre' => 'Coliseo Eduardo Rodríguez Ponce de León'],
                    ['letra' => 'B', 'nombre' => 'Serie B', 'sede_nombre' => 'Coliseo San Román · Juliaca'],
                ],
            ],
            [
                'id' => 'futsal-varones',
                'slug' => 'futsal-varones',
                'nombre' => 'Futsal Varones',
                'categoria' => 'Futsal',
                'color_acento' => '#059669',
                'foto_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&auto=format&fit=crop&q=80',
                'descripcion' => 'Fútbol de salón en losa deportiva reglamentaria.',
                'sede_principal' => 'Polideportivo San Román · Juliaca',
                'series' => [
                    ['letra' => 'A', 'nombre' => 'Serie A', 'sede_nombre' => 'Polideportivo San Román'],
                ],
            ],
            [
                'id' => 'futsal-damas',
                'slug' => 'futsal-damas',
                'nombre' => 'Futsal Damas',
                'categoria' => 'Futsal',
                'color_acento' => '#0d9488',
                'foto_url' => 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=800&auto=format&fit=crop&q=80',
                'descripcion' => 'Competencia de fútbol sala para trabajadoras de educación.',
                'sede_principal' => 'Complejo Deportivo Chanu Chanu · Puno',
                'series' => [
                    ['letra' => 'A', 'nombre' => 'Serie A', 'sede_nombre' => 'Complejo Deportivo Chanu Chanu'],
                ],
            ],
            [
                'id' => 'voley-mixto',
                'slug' => 'voley-mixto',
                'nombre' => 'Vóley Mixto',
                'categoria' => 'Vóley',
                'color_acento' => '#d97706',
                'foto_url' => 'https://images.unsplash.com/photo-1592656094267-764a45160876?w=800&auto=format&fit=crop&q=80',
                'descripcion' => 'Torneo integrador de vóley mixto.',
                'sede_principal' => 'Coliseo Municipal de Ilave',
                'series' => [
                    ['letra' => 'A', 'nombre' => 'Serie A', 'sede_nombre' => 'Coliseo Municipal de Ilave'],
                ],
            ],
            [
                'id' => 'basquet-libre',
                'slug' => 'basquet-libre',
                'nombre' => 'Básquetbol',
                'categoria' => 'Básquet',
                'color_acento' => '#ea580c',
                'foto_url' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80',
                'descripcion' => 'Campeonato oficial de baloncesto interinstitucional.',
                'sede_principal' => 'Coliseo Cerrado de Puno',
                'series' => [
                    ['letra' => 'A', 'nombre' => 'Serie A', 'sede_nombre' => 'Coliseo Cerrado de Puno'],
                ],
            ],
        ];

        foreach ($disciplinas as $dData) {
            $series = $dData['series'];
            unset($dData['series']);

            $disc = Disciplina::updateOrCreate(['id' => $dData['id']], $dData);

            foreach ($series as $sData) {
                Serie::firstOrCreate(
                    [
                        'disciplina_id' => $disc->id,
                        'letra' => $sData['letra'],
                    ],
                    [
                        'nombre' => $sData['nombre'],
                        'sede_nombre' => $sData['sede_nombre'],
                    ]
                );
            }
        }

        // 4. Usuarios del Sistema
        // Administrador General
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrador General',
                'email' => 'admin@macroregional.drep.gob.pe',
                'password' => Hash::make('drep2026'),
                'role' => 'ADMIN',
                'activo' => true,
            ]
        );

        // Delegado Puno
        User::updateOrCreate(
            ['username' => 'delegado_puno'],
            [
                'name' => 'Delegado UGEL Puno',
                'email' => 'puno@macroregional.drep.gob.pe',
                'password' => Hash::make('puno2026'),
                'role' => 'DELEGADO',
                'delegacion_id' => 'puno',
                'activo' => true,
            ]
        );

        // Delegado San Román
        User::updateOrCreate(
            ['username' => 'delegado_sanroman'],
            [
                'name' => 'Delegado UGEL San Román',
                'email' => 'sanroman@macroregional.drep.gob.pe',
                'password' => Hash::make('sanroman2026'),
                'role' => 'DELEGADO',
                'delegacion_id' => 'san-roman',
                'activo' => true,
            ]
        );
    }
}
