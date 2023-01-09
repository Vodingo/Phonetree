<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{

    const STATUS = [
        [ 'id' => 1, 'label' => 'Accounted'],
        [ 'id' => 2, 'label' => 'Not Accounted'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::STATUS as $status) {
            DB::table('statuses')->insert([
                'id' => $status['id'],
                'name' => $status['label'],
            ]);
        }
        
    }
}
