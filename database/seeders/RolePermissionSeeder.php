<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //superadmin lai sabai permissions dina 
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->permissions()->sync(
            Permission::pluck('id')
        );

        //admin lai permission dina lai 
        $admin = Role::where('name', 'admin')->first();

        $admin->permissions()->sync(
            Permission::whereIn('name', [
                //Hotels
                'view_hotels',
                'create_hotel',
                'update_hotel',
                'delete_hotel',
                'approve_hotel',
                'reject_hotel',
                'feature_hotel',

                //Rooms
                'view_rooms',
                'create_room',
                'update_room',
                'delete_room',
                'manage_room_availability',

                //Bookings
                'view_bookings',
                'update_booking',
                'cancel_booking',
                'check_in_guest',
                'check_out_guest',

                //Payments
                'view_payments',
                'process_payment',
                'refund_payment',
                'view_financial_reports',

                //Reviews
                'view_reviews',
                'delete_review',
                'moderate_reviews',

                //Coupons
                'view_coupons',
                'create_coupon',
                'update_coupon',
                'delete_coupon',
                'assign_coupon',

                //Banners
                'view_banners',
                'create_banner',
                'update_banner',
                'delete_banner',

                //offers
                'view_offers',
                'create_offer',
                'update_offer',
                'delete_offer',

                //Cities
                'view_cities',
                'create_city',
                'update_city',
                'delete_city',

                //Support
                'view_support_tickets',
                'reply_support_tickets',
                'close_support_tickets',

                // Contact Messages
                'view_contact_messages',
                'reply_contact_messages',
                'delete_contact_messages',

                //Users
                'view_users',
                'create_user',
                'update_user',
                'suspend_user',

                //Dashboard
                'view_dashboard',
                'view_reports',
                'view_statistics',
            ])->pluck('id')
        );

        // Permissions for hotel owner
        $hotelOwner = Role::where('name', 'hotel_owner')->first();

        $hotelOwner->permissions()->sync(
            Permission::whereIn('name', [
                //Hotels
                'view_hotels',
                'create_hotel',
                'update_hotel',

                //Rooms
                'view_rooms',
                'create_room',
                'update_room',
                'delete_room',
                'manage_room_availability',

                //Hotel Images
                'upload_hotel_images',
                'delete_hotel_images',

                //Room Images
                'upload_room_images',
                'delete_room_images',

                //Bookings
                'view_bookings',

                //Reviews
                'view_reviews',

                //Dashboard
                'view_dashboard',
            ])->pluck('id')
        );

        //Hotel Manager
        $hotelManager = Role::where('name', 'hotel_manager')->first();

        $hotelManager->permissions()->sync(
            Permission::whereIn('name', [
                //Hotels
                'view_hotels',
                'update_hotel',

                //Rooms
                'view_rooms',
                'create_room',
                'update_room',
                'delete_room',
                'manage_room_availability',

                //Bookings
                'view_bookings',
                'update_booking',
                'check_in_guest',
                'check_out_guest',

                //Reviews
                'view_reviews',

                //Dashboard
                'view_dashboard'
            ])->pluck('id')
        );


        $receptionist = Role::where('name', 'receptionist')->first();

        $receptionist->permissions()->sync(
            Permission::whereIn('name', [
                //Rooms

                'view_rooms',

                //bookings
                'view_bookings',
                'update_booking',

                //guest operations
                'check_in_guest',
                'check_out_guest',

                //Dahsboard
                'view_dashboard'
            ])->pluck('id')
        );

        //Finance Manager
        $financeManager = Role::where('name', 'finance_manager')->first();

        $financeManager->permissions()->sync(
            Permission::whereIn('name', [
                'view_payments',
                'process_payment',
                'refund_payment',
                'view_financial_reports',
                'view_dashboard'
            ])->pluck('id')
        );

        //Content Manager
        $contentManager = Role::where('name', 'content_manager')->first();

        $contentManager->permissions()->sync(
            Permission::whereIn('name', [
                'view_banners',
                'create_banner',
                'update_banner',
                'delete_banner',

                'view_offers',
                'create_offer',
                'update_offer',
                'delete_offer',

                'feature_hotel',

                'view_dashboard',
            ])->pluck('id')
        );

        //Support Agent
        $support = Role::where('name', 'support_agent')->first();

        $support->permissions()->sync(
            Permission::whereIn('name', [
                'view_support_tickets',
                'reply_support_tickets',
                'close_support_tickets',

                'view_contact_messages',
                'reply_contact_messages',

                'view_dashboard',
            ])->pluck('id')
        );

        //Customer

        $customer = Role::where('name', 'customer')->first();

        $customer->permissions()->sync(
            Permission::whereIn('name', [
                'view_hotels',
                'view_rooms',

                'create_booking',
                'cancel_booking',

                'create_review',
                'update_review',

                'add_favorite',
                'remove_favorite',
            ])->pluck('id')
        );
    }
}
