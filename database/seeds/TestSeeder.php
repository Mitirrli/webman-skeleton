<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class TestSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $faker = Factory::create();

        $data = [];
        for($i = 0; $i < 100;$i++) {
            $data[] = ['user_id' => mt_rand(1,1000), 'created' => date('Y-m-d H:i:s')];
        }

        $this->table('test')->insert($data)->save();
    }
}
