<?php

defined('ABSPATH') || exit();

$active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : '';
?>
<style>
    .ao-label, select.postform {
        display: inline-block !important
    }

    .ao-label {
        background: 0 0 !important;
        border: none !important;
        font-weight: 600;
        font-size: 14px;
        line-height: 18px;
        width: 290px
    }

    .group-label {
        margin: 14px 0;
    }
</style>
<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h1><?php
        echo esc_html__('Hostvn Admin Optimize Settings', 'hostvn_ao_lang') ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=hvn-admin-optimize-settings&tab=update"
           class="nav-tab <?php
           echo $active_tab == 'update' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Disable Update', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=widget"
           class="nav-tab <?php
           echo $active_tab == 'widget' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Dashboard widget', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=optimize"
           class="nav-tab <?php
           echo $active_tab == 'optimize' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Optimize', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=security"
           class="nav-tab <?php
           echo $active_tab == 'security' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Security', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=admin-menu"
           class="nav-tab <?php
           echo $active_tab == 'admin-menu' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Admin Menu', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=recaptcha"
           class="nav-tab <?php
           echo $active_tab == 'recaptcha' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Recaptcha', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=smtp"
           class="nav-tab <?php
           echo $active_tab == 'smtp' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Smtp', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=contact-button"
           class="nav-tab <?php
           echo $active_tab == 'contact-button' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Contact Button', 'hostvn-ao-lang') ?>
        </a>
        <a href="?page=hvn-admin-optimize-settings&tab=other"
           class="nav-tab <?php
           echo $active_tab == 'other' ? 'nav-tab-active' : ''; ?>">
            <?php
            echo esc_html__('Other', 'hostvn-ao-lang') ?>
        </a>
    </h2>

    <form method="post" action="options.php">
        <?php
        settings_fields('hostvn_ao_option'); ?>
        <?php
        switch ($active_tab) :
            case 'other':
                do_settings_fields($this->menuSlug, $this->hvn_ao_extra_section);
                break;
            case 'recaptcha':
                do_settings_fields($this->menuSlug, $this->hvn_ao_recaptcha_section);
                break;
            case 'widget':
                do_settings_fields($this->menuSlug, $this->hvn_ao_widget_section);
                break;
            case 'optimize':
                do_settings_fields($this->menuSlug, $this->hvn_ao_optimize_section);
                break;
            case 'security':
                do_settings_fields($this->menuSlug, $this->hvn_ao_security_section);
                break;
            case 'smtp':
                do_settings_fields($this->menuSlug, $this->hvn_ao_smtp_section);
                break;
            case 'contact-button':
                do_settings_fields($this->menuSlug, $this->hvn_ao_cb_section);
                break;
            case 'admin-menu':
                do_settings_fields($this->menuSlug, $this->hvn_ao_admin_menu_section);
                break;
            default:
                do_settings_fields($this->menuSlug, $this->hvn_ao_update_section);
                break;
        endswitch; ?>
        <?php submit_button(); ?>
    </form>
</div>
