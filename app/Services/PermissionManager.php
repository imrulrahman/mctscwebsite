<?php

namespace App\Services;

class PermissionManager
{
    public static function allGroups(): array
    {
        return [
            'admissions' => [
                'title' => 'Student Admissions',
                'icon' => 'user-check',
                'description' => 'Manage admission applications, registration records and printouts.',
                'permissions' => [
                    'admissions.view' => 'View Applications',
                    'admissions.create' => 'Add Student Application',
                    'admissions.edit' => 'Edit Application & Status',
                    'admissions.delete' => 'Delete Application',
                    'admissions.print' => 'Print & Export Application',
                ],
            ],
            'content' => [
                'title' => 'Courses & Projects',
                'icon' => 'book-open',
                'description' => 'Manage training courses and partner development projects.',
                'permissions' => [
                    'courses.manage' => 'Manage Courses',
                    'projects.manage' => 'Manage Projects',
                    'partners.manage' => 'Manage Development Partners',
                ],
            ],
            'notices_events' => [
                'title' => 'Notices, Events & Gallery',
                'icon' => 'bell',
                'description' => 'Publish notices, schedule institute events and manage photo gallery.',
                'permissions' => [
                    'notices.manage' => 'Manage Notices',
                    'events.manage' => 'Manage Events',
                    'gallery.manage' => 'Manage Photo Gallery',
                ],
            ],
            'messages' => [
                'title' => 'Contact Messages',
                'icon' => 'mail',
                'description' => 'Read and manage inquiry messages received through website contact form.',
                'permissions' => [
                    'messages.view' => 'View Messages',
                    'messages.delete' => 'Delete Messages',
                ],
            ],
            'settings' => [
                'title' => 'Site Settings & Branding',
                'icon' => 'sliders',
                'description' => 'Update contact details, institution profile, colors, logos and banners.',
                'permissions' => [
                    'settings.manage' => 'Manage Site Settings',
                ],
            ],
            'users' => [
                'title' => 'User Management',
                'icon' => 'users',
                'description' => 'Create admin accounts, edit credentials, assign roles and custom permissions.',
                'permissions' => [
                    'users.view' => 'View Users List',
                    'users.create' => 'Create New User',
                    'users.edit' => 'Edit User & Role',
                    'users.delete' => 'Delete User',
                    'users.permissions' => 'Manage Individual Permissions',
                ],
            ],
            'roles' => [
                'title' => 'Role Management',
                'icon' => 'shield',
                'description' => 'Create, edit, delete system roles and configure default role permissions.',
                'permissions' => [
                    'roles.manage' => 'Manage Roles & Permissions',
                ],
            ],
        ];
    }

    public static function allKeys(): array
    {
        $keys = [];
        foreach (self::allGroups() as $group) {
            foreach (array_keys($group['permissions']) as $key) {
                $keys[] = $key;
            }
        }
        return $keys;
    }

    public static function getLabel(string $permissionKey): string
    {
        foreach (self::allGroups() as $group) {
            if (isset($group['permissions'][$permissionKey])) {
                return $group['permissions'][$permissionKey];
            }
        }
        return $permissionKey;
    }

    public static function defaultRolePermissions(string $slug): array
    {
        return match ($slug) {
            'system-admin' => self::allKeys(),
            'admin' => [
                'admissions.view',
                'admissions.create',
                'admissions.edit',
                'admissions.delete',
                'admissions.print',
                'courses.manage',
                'projects.manage',
                'partners.manage',
                'notices.manage',
                'events.manage',
                'gallery.manage',
                'messages.view',
                'messages.delete',
                'settings.manage',
                'users.view',
                'users.create',
                'users.edit',
            ],
            'admission-officer' => [
                'admissions.view',
                'admissions.create',
                'admissions.edit',
                'admissions.print',
                'messages.view',
            ],
            'content-editor' => [
                'courses.manage',
                'projects.manage',
                'partners.manage',
                'notices.manage',
                'events.manage',
                'gallery.manage',
            ],
            default => [
                'admissions.view',
                'messages.view',
            ],
        };
    }
}
