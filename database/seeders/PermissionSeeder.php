<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [

            // Hotels
            ['name' => 'view_hotels', 'description' => 'View hotel listings and hotel details.'],
            ['name' => 'create_hotel', 'description' => 'Create a new hotel.'],
            ['name' => 'update_hotel', 'description' => 'Edit hotel information.'],
            ['name' => 'delete_hotel', 'description' => 'Delete a hotel.'],
            ['name' => 'approve_hotel', 'description' => 'Approve a hotel for public listing.'],
            ['name' => 'reject_hotel', 'description' => 'Reject a hotel submission.'],
            ['name' => 'feature_hotel', 'description' => 'Mark a hotel as featured on the platform.'],
            ['name'=>'deactivate_hotel', 'description' => 'temporarily deactivate the hotel'],
            ['name'=>'activate_hotel', 'description' =>'activate previously deactivated hotels'],

            // Rooms
            ['name' => 'view_rooms', 'description' => 'View room information.'],
            ['name' => 'create_room', 'description' => 'Add a new room to a hotel.'],
            ['name' => 'update_room', 'description' => 'Edit room details.'],
            ['name' => 'delete_room', 'description' => 'Remove a room.'],
            ['name' => 'manage_room_availability', 'description' => 'Manage room availability and inventory.'],

            // Bookings
            ['name' => 'view_bookings', 'description' => 'View booking records.'],
            ['name' => 'create_booking', 'description' => 'Create a new booking.'],
            ['name' => 'update_booking', 'description' => 'Modify an existing booking.'],
            ['name' => 'cancel_booking', 'description' => 'Cancel a booking.'],
            ['name' => 'check_in_guest', 'description' => 'Check a guest into the hotel.'],
            ['name' => 'check_out_guest', 'description' => 'Check a guest out of the hotel.'],

            // Payments
            ['name' => 'view_payments', 'description' => 'View payment transactions.'],
            ['name' => 'process_payment', 'description' => 'Process customer payments.'],
            ['name' => 'refund_payment', 'description' => 'Issue payment refunds.'],
            ['name' => 'view_financial_reports', 'description' => 'View financial reports and revenue.'],

            // Reviews
            ['name' => 'view_reviews', 'description' => 'View hotel reviews.'],
            ['name' => 'create_review', 'description' => 'Submit a review.'],
            ['name' => 'update_review', 'description' => 'Edit an existing review.'],
            ['name' => 'delete_review', 'description' => 'Delete a review.'],
            ['name' => 'moderate_reviews', 'description' => 'Moderate and manage user reviews.'],

            // Favorites
            ['name' => 'add_favorite', 'description' => 'Add a hotel to favorites.'],
            ['name' => 'remove_favorite', 'description' => 'Remove a hotel from favorites.'],

            // Coupons
            ['name' => 'view_coupons', 'description' => 'View coupon information.'],
            ['name' => 'create_coupon', 'description' => 'Create a new coupon.'],
            ['name' => 'update_coupon', 'description' => 'Modify coupon details.'],
            ['name' => 'delete_coupon', 'description' => 'Delete a coupon.'],
            ['name' => 'assign_coupon', 'description' => 'Assign coupons to bookings or users.'],

            // Hotel Images
            ['name' => 'upload_hotel_images', 'description' => 'Upload hotel images.'],
            ['name' => 'delete_hotel_images', 'description' => 'Remove hotel images.'],

            // Room Images
            ['name' => 'upload_room_images', 'description' => 'Upload room images.'],
            ['name' => 'delete_room_images', 'description' => 'Remove room images.'],

            // Banners
            ['name' => 'view_banners', 'description' => 'View promotional banners.'],
            ['name' => 'create_banner', 'description' => 'Create a new banner.'],
            ['name' => 'update_banner', 'description' => 'Edit banner information.'],
            ['name' => 'delete_banner', 'description' => 'Delete a banner.'],

            // Offers
            ['name' => 'view_offers', 'description' => 'View promotional offers.'],
            ['name' => 'create_offer', 'description' => 'Create a promotional offer.'],
            ['name' => 'update_offer', 'description' => 'Modify an offer.'],
            ['name' => 'delete_offer', 'description' => 'Delete an offer.'],

            // Cities
            ['name' => 'view_cities', 'description' => 'View supported cities.'],
            ['name' => 'create_city', 'description' => 'Add a new city.'],
            ['name' => 'update_city', 'description' => 'Edit city information.'],
            ['name' => 'delete_city', 'description' => 'Remove a city.'],

            // Contact Messages
            ['name' => 'view_contact_messages', 'description' => 'View customer contact messages.'],
            ['name' => 'reply_contact_messages', 'description' => 'Respond to contact messages.'],
            ['name' => 'delete_contact_messages', 'description' => 'Delete contact messages.'],

            // Support Tickets
            ['name' => 'view_support_tickets', 'description' => 'View support tickets.'],
            ['name' => 'reply_support_tickets', 'description' => 'Respond to support tickets.'],
            ['name' => 'close_support_tickets', 'description' => 'Close resolved support tickets.'],

            // Users
            ['name' => 'view_users', 'description' => 'View registered users.'],
            ['name' => 'create_user', 'description' => 'Create a new user account.'],
            ['name' => 'update_user', 'description' => 'Modify user information.'],
            ['name' => 'delete_user', 'description' => 'Delete a user account.'],
            ['name' => 'suspend_user', 'description' => 'Suspend a user account.'],

            // User Types
            ['name' => 'view_user_types', 'description' => 'View user types.'],
            ['name' => 'create_user_type', 'description' => 'Create a new user type.'],
            ['name' => 'update_user_type', 'description' => 'Modify user type information.'],
            ['name' => 'delete_user_type', 'description' => 'Delete a user type.'],

            // Roles
            ['name' => 'view_roles', 'description' => 'View system roles.'],
            ['name' => 'create_role', 'description' => 'Create a new role.'],
            ['name' => 'update_role', 'description' => 'Modify role information.'],
            ['name' => 'delete_role', 'description' => 'Delete a role.'],
            ['name' => 'assign_role', 'description' => 'Assign roles to users.'],
            ['name' => 'remove_role', 'description' => 'Remove roles from users.'],

            // Permissions
            ['name' => 'view_permissions', 'description' => 'View system permissions.'],
            ['name' => 'create_permission', 'description' => 'Create a new permission.'],
            ['name' => 'update_permission', 'description' => 'Modify permission information.'],
            ['name' => 'delete_permission', 'description' => 'Delete a permission.'],
            ['name' => 'assign_permission', 'description' => 'Assign permissions to roles.'],
            ['name' => 'remove_permission', 'description' => 'Remove permissions from roles.'],

            // Dashboard
            ['name' => 'view_dashboard', 'description' => 'Access the system dashboard.'],
            ['name' => 'view_reports', 'description' => 'View business reports.'],
            ['name' => 'view_statistics', 'description' => 'View platform statistics and analytics.'],

            // Platform
            ['name' => 'manage_settings', 'description' => 'Manage platform settings.'],
            ['name' => 'manage_platform', 'description' => 'Perform platform-wide administrative operations.'],
        ];

        foreach ($permissions as $permission) {
            //create permission
            Permission::create($permission);
        }
    }
}
