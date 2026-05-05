<?php
// database/seeders/AdminSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@eventms.com'],
            [
                'first_name'  => 'Admin',
                'middle_name' => null,
                'last_name'   => 'User',
                'email'       => 'admin@eventms.com',
                'contact_number' => '09000000000',
                'employee_id' => 'ADMIN-001',
                'position'    => 'System Administrator',
                'password'    => Hash::make('admin1234'),
                'role'        => 'admin',
                'status'      => 'approved',
            ]
        );
    }
}
