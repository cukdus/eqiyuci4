<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

// Compatibility alias for libraries expecting CodeIgniter\Entity (pre-4.0 style)
// Maps to the modern CodeIgniter\Entity\Entity class if available.
if (!class_exists('CodeIgniter\Entity') && class_exists(\CodeIgniter\Entity\Entity::class)) {
    class_alias(\CodeIgniter\Entity\Entity::class, 'CodeIgniter\Entity');
}

// Ensure alias exists even if target class isn't loaded yet.
spl_autoload_register(function ($class) {
    if ($class === 'CodeIgniter\\Entity' && !class_exists('CodeIgniter\\Entity', false)) {
        // Trigger autoload for the target class
        class_exists(\CodeIgniter\Entity\Entity::class);
        if (class_exists(\CodeIgniter\Entity\Entity::class)) {
            class_alias(\CodeIgniter\Entity\Entity::class, 'CodeIgniter\\Entity');
        }
    }
}, true, true);

if (! function_exists('get_nav_items')) {
    /**
     * Mengembalikan array item navigasi utama.
     *
     * @return array
     */
    function get_nav_items(): array
    {
        return [
            ['slug' => 'index', 'label' => 'Beranda', 'path' => base_url()],
            ['slug' => 'tentang', 'label' => 'Tentang', 'path' => base_url('tentang')],
            ['slug' => 'info', 'label' => 'Info', 'path' => base_url('info')],
            ['slug' => 'kontak', 'label' => 'Kontak', 'path' => base_url('kontak')],
            ['slug' => 'jadwal', 'label' => 'Jadwal', 'path' => base_url('jadwal')],
            ['slug' => 'sertifikat', 'label' => 'Sertifikat', 'path' => base_url('sertifikat')],
            ['slug' => 'bonus', 'label' => 'Bonus', 'path' => base_url('bonus')],
        ];
    }
}
