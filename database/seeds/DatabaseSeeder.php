<?php

namespace Mai1015\PlusIllusts\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PixivTableSeeder::class);
    }
}
