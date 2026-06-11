<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('Roles')->insert([
            ['Name' => 'Admin',    'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Agent',    'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Employee', 'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Manager',  'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('Categories')->insert([
            ['Name' => 'Hardware',       'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Software',       'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Network',        'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Email',          'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Access Request', 'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Other',          'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('Priorities')->insert([
            ['Name' => 'Low',      'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Medium',   'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'High',     'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Critical', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('Statuses')->insert([
            ['Name' => 'Open',        'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Pending',     'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Resolved',    'created_at' => now(), 'updated_at' => now()],
            ['Name' => 'Closed',      'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('Users')->insert([
            [
                'Name'       => 'Admin User',
                'Email'      => 'admin@helpdesk.com',
                'Password'   => Hash::make('password123'),
                'RoleId'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Name'       => 'John Agent',
                'Email'      => 'agent@helpdesk.com',
                'Password'   => Hash::make('password123'),
                'RoleId'     => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Name'       => 'Sara Employee',
                'Email'      => 'employee@helpdesk.com',
                'Password'   => Hash::make('password123'),
                'RoleId'     => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Name'       => 'Manager User',
                'Email'      => 'manager@helpdesk.com',
                'Password'   => Hash::make('password123'),
                'RoleId'     => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Name' => 'Hussein Diab',
                'Email' => 'hsendiab21@gmail.com',
                'Password' => Hash::make('password123'),
                'RoleId' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('Tickets')->insert([
            [
                'RefNumber'   => 'TKT-001',
                'Title'       => 'Outlook not opening',
                'Description' => 'Microsoft Outlook crashes on startup',
                'UserId'      => 3,
                'AssignedTo'  => 2,
                'CategoryId'  => 2,
                'PriorityId'  => 3,
                'StatusId'    => 1,
                'created_at'  => now(),
                'updated_at'  => now()
            ],
            [
                'RefNumber'   => 'TKT-002',
                'Title'       => 'VPN connection issue',
                'Description' => 'Cannot connect to company VPN from home',
                'UserId'      => 3,
                'AssignedTo'  => 2,
                'CategoryId'  => 3,
                'PriorityId'  => 4,
                'StatusId'    => 2,
                'created_at'  => now(),
                'updated_at'  => now()
            ],
            [
                'RefNumber'   => 'TKT-003',
                'Title'       => 'Printer not found',
                'Description' => 'Office printer not showing on network',
                'UserId'      => 3,
                'AssignedTo'  => null,
                'CategoryId'  => 1,
                'PriorityId'  => 2,
                'StatusId'    => 1,
                'created_at'  => now(),
                'updated_at'  => now()
            ],
            [
                'RefNumber'   => 'TKT-004',
                'Title'       => 'Password reset request',
                'Description' => 'User forgot password and needs reset',
                'UserId'      => 3,
                'AssignedTo'  => 2,
                'CategoryId'  => 5,
                'PriorityId'  => 1,
                'StatusId'    => 4,
                'created_at'  => now(),
                'updated_at'  => now()
            ],
            [
                'RefNumber'   => 'TKT-005',
                'Title'       => 'Main server offline',
                'Description' => 'Main server is not responding',
                'UserId'      => 3,
                'AssignedTo'  => 2,
                'CategoryId'  => 3,
                'PriorityId'  => 4,
                'StatusId'    => 2,
                'created_at'  => now(),
                'updated_at'  => now()
            ],
        ]);
    }
}
