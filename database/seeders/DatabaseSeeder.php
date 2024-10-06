<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Item;
use Database\Seeders\RankSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ItemSeeder::class,
            RankSeeder::class
        ]);
        Customer::factory(1000)->create();

        $item = Item::all();
        Purchase::factory(10000)->create()
        ->each(function(Purchase $purchase) use ($item){
            $purchase->items()->attach(
                $item->random(rand(1, 3))->pluck('id')->toArray(),
                [ 'quantity' => rand(1, 5)]
            );
        });

    }
}
