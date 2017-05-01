<?php

use App\Models\Configs;
use Illuminate\Database\Seeder;

  class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
      Configs::create([
        'id' => 1,
        'connected' => false,
        'updated_at' => time(),
        'created_at' => time()
      ]);
    }

  }