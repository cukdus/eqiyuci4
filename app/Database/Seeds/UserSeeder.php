<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Entities\User as UserEntity;
use Myth\Auth\Models\GroupModel;
use Config\Database;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = Database::connect();
        $groupModel = model(GroupModel::class);

        // Hapus grup superadmin dan semua relasi jika ada
        $super = $groupModel->where('name', 'superadmin')->first();
        if ($super) {
            $db->table('auth_groups_users')->where('group_id', $super->id)->delete();
            $db->table('auth_groups_permissions')->where('group_id', $super->id)->delete();
            $groupModel->delete($super->id);
        }

        // Pastikan hanya grup admin dan staff tersedia
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'staff', 'description' => 'Staff'],
        ];
        foreach ($roles as $role) {
            if (! $groupModel->where('name', $role['name'])->first()) {
                // Hindari error placeholder validation
                $groupModel->skipValidation(true)->insert($role);
            }
        }

        $userModel = model(UserModel::class);

        // Buat user admin jika belum ada
        $adminEmail = 'admin@eqiyukita.com';
        if (! $userModel->where('email', $adminEmail)->first()) {
            $admin = new UserEntity([
                'email'    => $adminEmail,
                'username' => 'admin',
                'active'   => 1,
            ]);
            $admin->setPassword('admin123');
            $admin->activate();
            $userModel->withGroup('admin')->save($admin);
        }

        // Buat user staff jika belum ada
        $staffEmail = 'staff@eqiyukita.com';
        if (! $userModel->where('email', $staffEmail)->first()) {
            $staff = new UserEntity([
                'email'    => $staffEmail,
                'username' => 'staff',
                'active'   => 1,
            ]);
            $staff->setPassword('staff123');
            $staff->activate();
            $userModel->withGroup('staff')->save($staff);
        }
    }
}
