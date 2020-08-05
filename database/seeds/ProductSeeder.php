<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = (int)$this->command->ask('How many products do you need ?', 10);

        $this->command->info("Creating {$count} products.");

        // Create the product
        factory(Product::class, $count)->create();

        $this->command->info('Products Created!');

    }
}
