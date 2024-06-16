<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Membuat beberapa role
        // Membuat default user untuk super admin

        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        $studentRole = Role::create([
            'name' => 'student'
        ]);

        $teacherRole = Role::create([
            'name' => 'teacher'
        ]);


        // Akun super admin untuk data awal ketika nanti sudah dibuat website nya
        // data kategori, kelas, dsb
        $userOwner = User::create([
            'name' => 'Prima Pangasa',
            'occupation' => 'Educator',
            'avatar' => 'images/default-avatar.png',
            'email' => 'primapangasa30@gmail.com',
            'password' => bcrypt('202020')
        ]);

        $userOwner->assignRole($ownerRole);
    }
}
