<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RankingPointRule;

class RankingPointRuleSeeder extends Seeder
{
    public function run(): void
    {
        // Reglas para eventos oficiales (FEMETEME)
        $femetemeRules = [
            ['min' => 0,   'max' => 12,   'positive' => 8, 'negative' => 8],
            ['min' => 13,  'max' => 37,   'positive' => 7, 'negative' => 10],
            ['min' => 38,  'max' => 62,   'positive' => 6, 'negative' => 13],
            ['min' => 63,  'max' => 87,   'positive' => 5, 'negative' => 16],
            ['min' => 88,  'max' => 112,  'positive' => 4, 'negative' => 20],
            ['min' => 113, 'max' => 133,  'positive' => 3, 'negative' => 25],
            ['min' => 134, 'max' => 158,  'positive' => 2, 'negative' => 30],
            ['min' => 159, 'max' => 183,  'positive' => 1, 'negative' => 35],
            ['min' => 184, 'max' => 208,  'positive' => 1, 'negative' => 40],
            ['min' => 209, 'max' => 237,  'positive' => 0, 'negative' => 45],
            ['min' => 238, 'max' => null, 'positive' => 0, 'negative' => 50],
        ];

        // Reglas para eventos NO oficiales
        $noOficialRules = [
            ['min' => 0,   'max' => 12,   'positive' => 4, 'negative' => 4],
            ['min' => 13,  'max' => 37,   'positive' => 4, 'negative' => 6],
            ['min' => 38,  'max' => 62,   'positive' => 3, 'negative' => 10],
            ['min' => 88,  'max' => 133,  'positive' => 2, 'negative' => 12],
            ['min' => 134, 'max' => 183,  'positive' => 1, 'negative' => 16],
            ['min' => 184, 'max' => 237,  'positive' => 0, 'negative' => 22],
            ['min' => 238, 'max' => null, 'positive' => 0, 'negative' => 25],
        ];

        foreach ($femetemeRules as $rule) {
            RankingPointRule::create([
                'min_difference' => $rule['min'],
                'max_difference' => $rule['max'],
                'positive_points' => $rule['positive'],
                'negative_points' => $rule['negative'],
                'type' => 'femeteme',
            ]);
        }

        foreach ($noOficialRules as $rule) {
            RankingPointRule::create([
                'min_difference' => $rule['min'],
                'max_difference' => $rule['max'],
                'positive_points' => $rule['positive'],
                'negative_points' => $rule['negative'],
                'type' => 'no_oficial',
            ]);
        }
    }
}
