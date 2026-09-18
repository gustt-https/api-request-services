<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use Illuminate\Database\Seeder;

class ServicePackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Econômico',
                'slug' => 'economico',
                'tagline' => 'Limpeza básica do essencial',
                'description' => 'Ideal para manutenção rápida de um apartamento pequeno.',
                'includes' => [
                    'Limpeza básica',
                    '1 quarto',
                    '1 banheiro',
                    'Sala',
                ],
                'price' => 89.90,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Padrão',
                'slug' => 'padrao',
                'tagline' => 'Mais cômodos, mesmo cuidado',
                'description' => 'Boa escolha para casas e apartamentos médios.',
                'includes' => [
                    'Limpeza completa',
                    '2 quartos',
                    '1 banheiro',
                    'Sala',
                    'Cozinha',
                ],
                'price' => 129.90,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Completo',
                'slug' => 'completo',
                'tagline' => 'Casa inteira, detalhada',
                'description' => 'Para quando você quer tudo em ordem de uma vez.',
                'includes' => [
                    'Limpeza detalhada',
                    '3 quartos',
                    '2 banheiros',
                    'Sala',
                    'Cozinha',
                    'Área de serviço',
                ],
                'price' => 189.90,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $package) {
            ServicePackage::query()->updateOrCreate(
                ['slug' => $package['slug']],
                $package,
            );
        }
    }
}
