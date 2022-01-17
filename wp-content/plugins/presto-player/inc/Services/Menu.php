<?php

namespace PrestoPlayer\Services;

use PrestoPlayer\Plugin;
use PrestoPlayer\WPackio\Enqueue;

class Menu
{
    protected $enqueue;

    public function __construct()
    {
        $this->enqueue = new Enqueue(
            'prestoPlayer',
            'dist',
            Plugin::version(),
            'plugin',
            PRESTO_PLAYER_PLUGIN_FILE
        );
    }

    public function register()
    {
        add_action('admin_menu', [$this, 'addMenu']);
    }

    public function addMenu()
    {
        add_menu_page(
            __('Presto Player', 'presto-player'),
            __('Presto Player', 'presto-player'),
            'publish_posts',
            'edit.php?post_type=pp_video_block',
            '',
            "data:image/svg+xml,%3Csvg width='18' viewBox='0 0 43 41' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M27.2297 20.7969L37.0634 15.1194L37.0634 26.4744L27.2297 20.7969Z' fill='white' fill-opacity='0.7'/%3E%3Cpath d='M27.232 9.44301L37.0657 15.1205L27.232 20.7979L27.232 9.44301Z' fill='white' fill-opacity='0.75'/%3E%3Cpath d='M27.232 9.44273L27.232 20.7976L17.3984 15.1202L27.232 9.44273Z' fill='white' fill-opacity='0.8'/%3E%3Cpath d='M27.2565 9.43332L17.4229 15.1108L17.4229 3.75586L27.2565 9.43332Z' fill='white' fill-opacity='0.85'/%3E%3Cpath d='M17.4274 15.1241L17.4274 26.479L7.59371 20.8016L17.4274 15.1241Z' fill='white' fill-opacity='0.8'/%3E%3Cpath d='M17.4274 3.79576L17.4274 15.1507L7.59371 9.47322L17.4274 3.79576Z' fill='white' fill-opacity='0.9'/%3E%3Cpath d='M17.4274 26.4654L17.4274 37.8204L7.59371 32.1429L17.4274 26.4654Z' fill='white' fill-opacity='0.7'/%3E%3Cpath d='M27.232 20.7977L37.0657 26.4752L27.232 32.1527L27.232 20.7977Z' fill='white' fill-opacity='0.65'/%3E%3Cpath d='M7.59522 20.7977L17.4289 26.4752L7.59522 32.1527L7.59522 20.7977Z' fill='white' fill-opacity='0.75'/%3E%3Cpath d='M7.59522 9.4518L17.4289 15.1293L7.59522 20.8067L7.59522 9.4518Z' fill='white' fill-opacity='0.85'/%3E%3Cpath d='M27.232 20.7975L27.232 32.1524L17.3984 26.4749L27.232 20.7975Z' fill='white' fill-opacity='0.6'/%3E%3C/svg%3E%0A"
        );

        add_submenu_page(
            'edit.php?post_type=pp_video_block',
            __('Media Hub', 'presto-player'),
            __('Media Hub', 'presto-player'),
            'publish_posts',
            'edit.php?post_type=pp_video_block'
        );

        $analyics_page = add_submenu_page(
            'edit.php?post_type=pp_video_block',
            __('Analytics', 'presto-player'),
            !Plugin::isPro() ? __('Analytics', 'presto-player') . ' <span class="update-plugins" style="background-color: #ffffff1c"><span class="plugin-count">Pro</span></span>' : __('Analytics', 'presto-player'),
            'publish_posts',
            'presto-analytics',
            function () {
                ob_start();
?>
            <div class="presto-player-dashboard__header">
                <img class="presto-player-dashboard__logo" src="<?php echo esc_url(PRESTO_PLAYER_PLUGIN_URL . '/img/logo.svg'); ?>" />
                <div class="presto-player-dashboard__version">v<?php echo esc_html(Plugin::version()); ?></div>
            </div>
            <div id="presto-analytics-page"></div>
            <?php wp_auth_check_html(); ?>
<?php
                $page = ob_get_clean();
                echo $page;
            }
        );

        add_action("admin_print_scripts-{$analyics_page}", [$this, 'analyticsAssets']);

        $settings_page = add_submenu_page(
            'edit.php?post_type=pp_video_block',
            __('Presto Player Settings', 'presto-player'),
            __('Settings', 'presto-player'),
            'manage_options',
            'presto-player-settings',
            "PrestoPlayer\Services\Settings::template",
            5
        );

        add_action("admin_print_scripts-{$settings_page}", [$this, 'settingsAssets']);
    }

    /**
     * Scripts needed on settings page
     */
    public function settingsAssets()
    {
        wp_enqueue_media();
        wp_enqueue_code_editor(['type' => "text/css"]);

        $assets =  $this->enqueue->enqueue('settings', 'admin', [
            'js_dep' => [
                'wp-components',
                'wp-element',
                'wp-codemirror',
                'wp-api',
                'wp-i18n',
                'wp-editor',
                'wp-blob',
                'wp-blocks',
                'wp-data',
                'wp-core-data'
            ]
        ]);
        wp_enqueue_style('wp-components');

        $entry_point = array_pop($assets['js']);

        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations($entry_point['handle'], 'presto-player');
        }

        wp_localize_script(
            $entry_point['handle'],
            'prestoPlayer',
            apply_filters('presto-settings-js-options', [
                'root' => esc_url_raw(get_rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
                'proVersion' => Plugin::proVersion(),
                'isSetup' => [
                    'bunny' => false
                ],
                'isPremium' => Plugin::isPro(),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'wpVersionString' => 'wp/v2/',
                'prestoVersionString' => 'presto-player/v1/',
                'debug' => defined('SCRIPT_DEBUG') && SCRIPT_DEBUG
            ])
        );
    }

    /**
     * Scripts needed on analytics page
     */
    public function analyticsAssets()
    {
        $assets = $this->enqueue->enqueue('analytics', 'admin', [
            'js_dep' => [
                'hls.js',
                'presto-components',
                'wp-components',
                'wp-element',
                'wp-api',
                'wp-i18n',
                'wp-editor',
                'wp-blob',
                'media',
                'wp-blocks',
                'wp-data',
                'wp-core-data'
            ]
        ]);
        wp_enqueue_style('wp-components');
        wp_enqueue_media();
        $entry_point = array_pop($assets['js']);

        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations($entry_point['handle'], 'presto-player');
        }

        wp_localize_script($entry_point['handle'], 'prestoPlayer', [
            'root' => esc_url_raw(get_rest_url()),
            'isPremium' => Plugin::isPro(),
            'plugin_url' => esc_url_raw(trailingslashit(PRESTO_PLAYER_PLUGIN_URL)),
            'nonce' => wp_create_nonce('wp_rest'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'wpVersionString' => 'wp/v2/',
            'prestoVersionString' => 'presto-player/v1/',
            'i18n' => Translation::geti18n()
        ]);
    }

    public function template()
    {
        echo '<div id="presto-player-dashboard"></div>';
    }
}
