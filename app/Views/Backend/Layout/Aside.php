<?php
// Load Models
use App\Models\AdminPermissionsModel;

// Preload models
$permissions = new AdminPermissionsModel;

// Variable
$getSessionAdmin        = (session()->get('role') >= 2);
$getSessionSuperadmin   = (session()->get('role') >= 3);
$getAccountPermissions  = $permissions->where('username', session()->get('username'))->first();
?>
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= (current_url() == base_url('/admin/dashboard') || current_url() == base_url('/' . service('request')->getLocale() . '/admin/dashboard') ? '' : 'collapsed'); ?>" href="<?= base_url('/' . service('request')->getLocale() . '/admin/dashboard'); ?>">
                <i class="bi bi-grid"></i> <span>Dashboard</span>
            </a>
        </li>

        <!-- Staff -->
        <li class="nav-heading">Staff</li>
        <li class="nav-item">
            <a class="nav-link 
                <?= (current_url() === base_url('/admin/post') || current_url() === base_url('/admin/add-post') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/post') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/add-post') ? '' : 'collapsed'); ?>" data-bs-target="#staff-post" data-bs-toggle="collapse" href="#">
                <i class="bi bi-newspaper"></i><span><?= lang('Menu.post'); ?></span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="staff-post" class="nav-content collapse <?= (current_url() === base_url('/admin/post') || current_url() === base_url('/admin/post/add') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/post') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/post/add') ? 'show' : ''); ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/post'); ?>" <?= (current_url() === base_url('/admin/post') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/post') ? 'class="active"' : ''); ?>>
                        <i class="bi bi-display"></i><span><?= lang('Menu.list_post'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/post/add'); ?>" <?= (current_url() === base_url('/admin/post/add') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/post/add') ? 'class="active"' : ''); ?>>
                        <i class="bi bi-plus-circle"></i><span><?= lang('Menu.add_post'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Admin -->
        <?php if (isset($getSessionAdmin) || isset($getAccountPermissions)) : ?>
            <?php $gerPermissionsManageUsers    = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'manage_users') : FALSE); ?>
            <?php $gerPermissionsAddUsers       = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'add_users') : FALSE); ?>
            <?php $gerPermissionsEditUsers      = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'edit_users') : FALSE); ?>

            <?php if ($getSessionAdmin === TRUE || $gerPermissionsManageUsers === TRUE || $gerPermissionsAddUsers === TRUE || $gerPermissionsEditUsers === TRUE) : ?>
                <!-- <li class="nav-heading">Admin</li> -->
            <?php endif; ?>
            <?php if ($getSessionAdmin === TRUE || $gerPermissionsManageUsers === TRUE || $gerPermissionsAddUsers === TRUE || $gerPermissionsEditUsers === TRUE) : ?>
                <!-- <li class="nav-item">
                    <a class="nav-link <?= (current_url() === base_url('/admin/manage-users') || current_url() === base_url('/admin/manage-users/add') || current_url() === base_url('/admin/manage-admins/manage-users/edit') ? '' : 'collapsed'); ?>" data-bs-target="#admin-manage-users" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-people"></i><span>Manage Users</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="admin-manage-users" class="nav-content collapse <?= (current_url() === base_url('/admin/manage-users') || current_url() === base_url('/admin/manage-users/add') || current_url() === base_url('/admin/manage-users/edit') ? 'show' : ''); ?>" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="<?= base_url('/admin/manage-users'); ?>">
                                <i class="bi bi-circle-fill"></i><span>List Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('/admin/manage-users/add'); ?>">
                                <i class="bi bi-circle-fill"></i><span>Add Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('/admin/manage-users/edit'); ?>">
                                <i class="bi bi-circle-fill"></i><span>Edit Users</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
            <?php endif; ?>
        <?php endif; ?>

        <!-- Superadmin -->
        <?php if (isset($getSessionSuperadmin) || isset($getAccountPermissions)) : ?>
            <?php $getPermissionsManageAdmin        = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'manage_admins') : FALSE); ?>
            <?php $getPermissionsEditAdmin          = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'edit_admins') : FALSE); ?>
            <?php $getPermissionsEditPermissions    = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'edit_admin_permissions') : FALSE); ?>
            <?php $getPermissionsWebsiteSettings    = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'edit_website_settings') : FALSE); ?>
            <?php $getPermissionManageLogs          = (!empty($getAccountPermissions) ? getExistPermission($getAccountPermissions['permissions'], 'manage_logs') : FALSE); ?>

            <?php if ($getSessionSuperadmin === TRUE || $getPermissionsManageAdmin === TRUE || $getPermissionsEditAdmin === TRUE || $getPermissionsEditPermissions === TRUE || $getPermissionsWebsiteSettings === TRUE || $getPermissionManageLogs === TRUE) : ?>
                <li class="nav-heading">Superadmin</li>
            <?php endif; ?>
            <?php if ($getSessionSuperadmin === TRUE || $getPermissionsManageAdmin === TRUE || $getPermissionsEditAdmin === TRUE || $getPermissionsEditPermissions === TRUE) : ?>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() === base_url('/admin/manage-admins') || current_url() === base_url('/admin/manage-admins/edit') || current_url() === base_url('/admin/manage-admins/manage-admins/edit-permissions') ||
                                            current_url() === base_url('/' . service('request')->getLocale() . '/admin/manage-admins') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/manage-admins/edit') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/manage-admins/manage-admins/edit-permissions')
                                            ? '' : 'collapsed'); ?>" data-bs-target="#superadmin-manage-admin" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-people"></i><span>Admins</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="superadmin-manage-admin" class="nav-content collapse <?= (current_url() === base_url('/admin/manage-admins') || current_url() === base_url('/admin/manage-admins/edit') || current_url() === base_url('/admin/manage-admins/edit-permissions') ? 'show' : ''); ?>" data-bs-parent="#sidebar-nav">
                        <?php if ($getSessionSuperadmin === TRUE || $getPermissionsManageAdmin === TRUE) : ?>
                            <li>
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/manage-admins'); ?>" <?= (current_url() === base_url('/admin/manage-admins') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/manage-admins') ? 'class="active"' : ''); ?>>
                                    <i class="bi bi-circle-fill"></i><span><?= lang('Menu.list_admins'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($getSessionSuperadmin === TRUE || $getPermissionsEditAdmin === TRUE) : ?>
                            <li>
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/manage-admins/edit'); ?>" <?= (current_url() === base_url('/admin/manage-admins/edit') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/manage-admins/edit') ? 'class="active"' : ''); ?>>
                                    <i class="bi bi-circle-fill"></i><span><?= lang('Menu.edit_admins'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($getSessionSuperadmin == TRUE || $getPermissionsEditPermissions === TRUE) : ?>
                            <li>
                                <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/manage-admins/edit-permissions'); ?>" <?= (current_url() === base_url('/admin/manage-admins/edit-permissions') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/manage-admins/edit-permissions') ? 'class="active"' : ''); ?>>
                                    <i class="bi bi-circle-fill"></i><span><?= lang('Menu.edit_permissions'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if ($getSessionSuperadmin === TRUE || $getPermissionsWebsiteSettings === TRUE) : ?>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() === base_url('/admin/website-settings') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/website-settings') ? '' : 'collapsed'); ?>" href="<?= base_url('/' . service('request')->getLocale() . '/admin/website-settings'); ?>">
                        <i class="bi bi-gear"></i><span><?= lang('Menu.website_settings'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($getSessionSuperadmin === TRUE || $getPermissionManageLogs === TRUE) : ?>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() === base_url('/admin/logs') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/logs') ? '' : 'collapsed'); ?>" href="<?= base_url('/' . service('request')->getLocale() . '/admin/logs'); ?>">
                        <i class="bi bi-table"></i><span><?= lang('Menu.logs'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- User -->
        <li class="nav-heading"><?= lang('Menu.user') ?></li>
        <li class="nav-item">
            <a class="nav-link <?= (current_url() === base_url('/admin/my-profile') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/my-profile') ? '' : 'collapsed'); ?>" href="<?= base_url('/' . service('request')->getLocale() . '/admin/my-profile'); ?>">
                <i class="bi bi-person"></i><span><?= lang('Menu.my_profile') ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= (current_url() === base_url('/admin/my-history') || current_url() === base_url('/' . service('request')->getLocale() . '/admin/my-history') ? '' : 'collapsed'); ?>" href="<?= base_url('/' . service('request')->getLocale() . '/admin/my-history'); ?>">
                <i class="bi bi-card-list"></i><span><?= lang('Menu.my_history') ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= base_url('/' . service('request')->getLocale() . '/admin/signout'); ?>">
                <i class="bi bi-box-arrow-right"></i><span><?= lang('Menu.sign_out') ?></span>
            </a>
        </li>
    </ul>
</aside>