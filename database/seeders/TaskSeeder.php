<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flat;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $defaultTasks = [
            'Limpiar los baños',
            'Sacar la basura',
            'Fregar los platos',
            'Limpiar la cocina',
            'Barrer la casa',
            'Hacer las habitaciones',
        ];

        // Si quieres aplicarlo solo a un flat concreto, cambia la consulta.
        $flats = Flat::all();

        foreach ($flats as $flat) {
            $existingNames = Task::where('flat_id', $flat->id)
                ->pluck('name')
                ->map(fn($n) => mb_strtolower(trim($n)))
                ->toArray();

            foreach ($defaultTasks as $name) {
                if (!in_array(mb_strtolower($name), $existingNames)) {
                    Task::create([
                        'flat_id' => $flat->id,
                        'name' => $name,
                        'description' => null,
                        'created_at' => Carbon::now()
                    ]);
                }
            }
        }
    }
}
