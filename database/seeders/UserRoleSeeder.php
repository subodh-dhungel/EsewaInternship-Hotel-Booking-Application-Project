<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //All the required role Goodies
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $hotelOwnerRole = Role::where('name', 'hotel_owner')->first();
        $hotelManagerRole = Role::where('name', 'hotel_manager')->first();
        $receptionistRole = Role::where('name', 'receptionist')->first();
        $supportAgentRole = Role::where('name', 'support_agent')->first();
        $contentManagerRole = Role::where('name', 'content_manager')->first();
        $financeManagerRole = Role::where('name', 'finance_manager')->first();
        $customerRole = Role::where('name', 'customer')->first();

        //assigning user[1] to the superadmin role from the existing table.
        $superAdmin = User::find(1);
        $superAdmin->roles()->attach($superAdminRole);

        //assigning user[2] to the admin role from the existing table.
        $admin = User::find(2);
        $admin->roles()->attach($adminRole);

        //assigning user[3] to the hotel owner role from the exiting table.
        $hotelOwner = User::find(3);
        $hotelOwner->roles()->attach($hotelOwnerRole);

        //assigning user[4] to the hotel manager rol;e from the existing table.
        $hotelManager = User::find(4);
        $hotelManager->roles()->attach($hotelManagerRole);

        //assigning user[5] to the receptionist role from the existing table
        $receptionist = User::find(5);
        $receptionist->roles()->attach($receptionistRole);

        //assigning user[6] to the support agaent role from the existing table
        $supportAgent = User::find(6);
        $supportAgent->roles()->attach($supportAgentRole);

        //assigning user[7] to the content manager role from the existing table
        $contentManager = User::find(7);
        $contentManager->roles()->attach($contentManagerRole);

        //assigning user[8] to the finance manager role from the existing table
        $financeManager = User::find(8);
        $financeManager->roles()->attach($financeManagerRole);

        //assigning user[9] to the customer role from the existing table
        $customer = User::find(9);
        $customer->roles()->attach($customerRole);
    }
}
