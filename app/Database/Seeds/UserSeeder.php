<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        
        // Data untuk user superadmin
        $superadmin = new User([
            'email' => 'superadmin@eqiyukita.com',
            'username' => 'superadmin',
            'nama_lengkap' => 'Super Administrator',
            'role' => 'superadmin',
        ]);
        
        // Set password
        $superadmin->setPassword('superadmin123');
        
        // Aktifkan user
        $superadmin->activate();
        
        // Simpan user
        $userModel->save($superadmin);
        
        // Data untuk user admin
        $admin = new User([
            'email' => 'admin@eqiyukita.com',
            'username' => 'admin',
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
        ]);
        
        // Set password
        $admin->setPassword('admin123');
        
        // Aktifkan user
        $admin->activate();
        
        // Simpan user
        $userModel->save($admin);
        
        // Data untuk user staff
        $staff = new User([
            'email' => 'staff@eqiyukita.com',
            'username' => 'staff',
            'nama_lengkap' => 'Staff',
            'role' => 'staff',
        ]);
        
        // Set password
        $staff->setPassword('staff123');
        
        // Aktifkan user
        $staff->activate();
        
        // Simpan user
        $userModel->save($staff);
    }
}
