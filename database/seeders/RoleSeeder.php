<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'customer',
                'description'=>'Regular user who can search and book hotels.'
            ],

            [
                'name'=>'hotel_owner',
                'description'=>'Owns and manages hotel on the platform.'
            ],
            [
                'name'=>'hotel_manager',
                'description'=>'Manages the day to day operations of the hotel.'
            ],

            [
                'name'=>'receptionist',
                'description'=>'Handles hotel guests, bookings, check-ins and check-outs.'
            ],

            [
                'name'=>'support_agent',
                'description'=>'Handles customer support and support tickets.'
            ],

            [
                'name'=> 'content_manager',
                'description'=>'Manages Promotional and platform content.'
            ],

            [
                'name'=>'finance_manager',
                'description'=>'Manages Payments, refunds and financial information.'
            ],

            [
                'name'=>'admin',
                'description'=>'Manages general platform operations.'
            ],

            [
                'name'=>'super_admin',
                'description'=>'has complete control over the platform.'
            ]
        ];

        foreach($roles as $role){
            Role::create($role);
        }
    }
}
