<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@gmail.com',
            'password' => bcrypt('customer123'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Test Restaurant Owner',
            'email' => 'restaurantowner@gmail.com',
            'password' => bcrypt('restaurantowner123'),
            'role' => 'restaurant_owner',
        ]);

        $deliveryUser = User::create([
            'name' => 'Rampura Delivery Man',
            'email' => 'deliveryman@gmail.com',
            'password' => bcrypt('delivery123'),
            'role' => 'delivery_man',
        ]);

        DB::table('delivery_men')->insert([
            'user_id' => $deliveryUser->id,
            'phone' => '5551001001',
            'status' => 'available',
            'location' => DB::raw("ST_GeomFromText('POINT(23.761801 90.422216)', 4326)"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deliveryUser1 = User::create([
            'name' => 'East West University Delivery Man',
            'email' => 'deliveryman1@gmail.com',
            'password' => bcrypt('delivery123'),
            'role' => 'delivery_man',
        ]);

        DB::table('delivery_men')->insert([
            'user_id' => $deliveryUser1->id,
            'phone' => '5551001002',
            'status' => 'available',
            'location' => DB::raw("ST_GeomFromText('POINT(23.768623 90.425607)', 4326)"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $deliveryUser2 = User::create([
            'name' => 'Uttara Delivery Man',
            'email' => 'deliveryman2@gmail.com',
            'password' => bcrypt('delivery123'),
            'role' => 'delivery_man',
        ]);

        DB::table('delivery_men')->insert([
            'user_id' => $deliveryUser2->id,
            'phone' => '5551001003',
            'status' => 'available',
            'location' => DB::raw("ST_GeomFromText('POINT(23.869328 90.392689)', 4326)"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
