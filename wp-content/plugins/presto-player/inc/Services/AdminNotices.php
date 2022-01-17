<?php

namespace PrestoPlayer\Services;

class AdminNotices
{
    public function register()
    {
        add_action('admin_init', [$this, 'dismiss']);
    }

    public static function isDismissed($name)
    {
        return (bool) get_option("presto_player_dismissed_notice_" . sanitize_text_field($name), false);
    }

    public function dismiss()
    {
        // permissions check
        if (!current_user_can('install_plugins')) {
            return;
        }

        // not our notices, bail
        if (!isset($_GET['presto_action']) || 'dismiss_notices' !== $_GET['presto_action']) {
            return;
        }

        // get notice
        $notice = !empty($_GET['presto_notice']) ? sanitize_text_field($_GET['presto_notice']) : '';
        if (!$notice) {
            return;
        }

        // notice is dismissed
        update_option("presto_player_dismissed_notice_" . sanitize_text_field($notice), 1);
    }
}
