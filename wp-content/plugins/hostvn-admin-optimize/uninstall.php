<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit();

$option_name = 'hostvn_admin_optimize';

delete_option( $option_name );
