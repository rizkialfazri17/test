<?php
// Load Models
use App\Models\WebsiteConfigurationModel;

// Preload models
$web_config = new WebsiteConfigurationModel;

// Base Website Configuration
$website_name = $web_config->getValue('website_name');
?>
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <div class="logo d-flex align-items-center">
            <a href="<?= base_url('/' . service('request')->getLocale() . '/admin/dashboard'); ?>" class="d-none d-lg-block"><?= $website_name; ?></a>
        </div>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <div class="p-3 d-none d-md-flex">
        <span class="small" id="date_time_now"></span>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item pe-3">
                <a href="javascript:void(0);" class="theme-switcher" id="theme-switcher" title="<?= (service('request')->getLocale() == 'id' ? 'Ganti Tema' : 'Change Theme') ?>"></a>
            </li>
            <li class="nav-item pe-3">
                <?php if (service('request')->getLocale() == 'en') { ?>
                    <a href="<?= base_url('/id/admin/dashboard'); ?>">
                        <img src="<?= base_url('/assets/images/flag/en.png'); ?>" alt="Inggris" title="Change language to Indonesia" width="25" height="25" />
                    </a>
                <?php } else { ?>
                    <a href="<?= base_url('/en/admin/dashboard'); ?>">
                        <img src="<?= base_url('/assets/images/flag/id.png'); ?>" alt="Indonesia" title="Change language to English" width="25" height="25" />
                    </a>
                <?php } ?>
            </li>
            <!-- <li class="nav-item dropdown pe-3">
                <a class="nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger badge-number">4</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 4 new notifications
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="notification-item active">
                        <div>
                            <a href="#">Quae dolorem earum veritatis oditseno oditseno</a>
                            <time>30 min ago</time>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="notification-item active">
                        <div>
                            <a href="#">Quae dolorem earum veritatis oditseno oditseno</a>
                            <time>30 min ago</time>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="notification-item">
                        <div>
                            <a href="#">Quae dolorem earum veritatis oditseno oditseno</a>
                            <time>30 min ago</time>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="notification-item">
                        <div>
                            <a href="#">Quae dolorem earum veritatis oditseno</a>
                            <time>30 min ago</time>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="notification-item">
                        <div>
                            <a href="#">Quae dolorem earum veritatis oditseno</a>
                            <time>30 min ago</time>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="notification-item">
                        <div>
                            <a href="#">Quae dolorem earum veritatis oditseno</a>
                            <time>30 min ago</time>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="dropdown-footer">
                        <a href="#">Show all notifications</a>
                    </li>
                </ul>
            </li> -->
            <li class="nav-item dropdown pe-3">
                <a class="nav-profile d-flex align-items-center pe-3" href="#" data-bs-toggle="dropdown">
                    <?php if (!empty($DataAccount['photo'])) {
                        $getPhoto = FCPATH . '/assets/images/photo/' . $DataAccount['photo'];

                        if (file_exists($getPhoto)) {
                            echo '<img src="' . base_url('/assets/images/photo/' . $DataAccount['photo']) . '" alt="Photo Profile ' . $DataAccount['full_name'] . '" class="rounded-circle" height="36" />';
                        } else {
                            echo '<img src="' . base_url('/assets/images/photo/default.png') . '" alt="Photo Profile ' . $DataAccount['full_name'] . '" class="rounded-circle" height="36" />';
                        }
                    } else {
                        echo '<img src="' . base_url('/assets/images/photo/default.png') . '" alt="Photo Profile" class="rounded-circle" height="36" />';
                    } ?>
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?= (isset($DataAccount) ? $DataAccount['username'] : ''); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('/' . service('request')->getLocale() . '/admin/my-profile'); ?>">
                            <i class="bi bi-person"></i> <span><?= lang('Menu.my_profile'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('/' . service('request')->getLocale() . '/admin/account-settings'); ?>">
                            <i class="bi bi-gear"></i> <span><?= lang('Menu.account_settings'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('/' . service('request')->getLocale() . '/admin/change-password'); ?>">
                            <i class="bi bi-lock"></i> <span><?= lang('Menu.change_password'); ?></span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('/' . service('request')->getLocale() . '/admin/my-history'); ?>">
                            <i class="bi bi-card-list"></i> <span><?= lang('Menu.my_history'); ?></span>
                        </a>
                    </li>
                    <!-- <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-question-circle"></i> <span>Need Help?</span>
                        </a>
                    </li> -->
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('/' . service('request')->getLocale() . '/admin/signout'); ?>">
                            <i class="bi bi-box-arrow-right"></i> <span><?= lang('Menu.sign_out'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>