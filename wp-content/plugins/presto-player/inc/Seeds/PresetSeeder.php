<?php

namespace PrestoPlayer\Seeds;

use PrestoPlayer\Models\Preset;

class PresetSeeder
{
    /**
     * To update, change seed data and increment
     * this version number
     *
     * @var integer
     */
    protected $version = 3;

    public function run()
    {
        $db_version = get_option('presto_preset_seed_version', 0);

        if ($db_version < $this->version) {
            $this->seedDefault();
            $this->seedCourse();
            $this->seedSimple();
            $this->seedMinimal();
            $this->seedYoutube();

            update_option('presto_preset_seed_version', $this->version);
        }
    }

    public function seedDefault()
    {
        $preset = new Preset();
        $preset->updateOrCreate(
            ['slug' => 'default'],
            [
                'name' => __("Default", 'presto-player'),
                'slug' => 'default',
                'skin' => 'default',
                'icon' => 'format-video',
                "play-large" => true,
                'rewind' => true,
                'play' => true,
                "fast-forward" => true,
                'progress' => true,
                'current-time' => true,
                'mute' => true,
                'volume' => true,
                'speed' => false,
                'pip' => false,
                'fullscreen' => true,
                'captions' => false,
                // behavior
                'save_player_position' => true,
                'auto_hide' => true,
                'reset_on_end' => true,
                'sticky_scroll' => false,
                // youtube
                'hide_youtube' => false,
                'lazy_load_youtube' => false,
                'hide_logo' => false,
                'is_locked' => true
            ]
        );
    }

    public function seedCourse()
    {
        $preset = new Preset();
        $preset->updateOrCreate(
            ['slug' => 'course'],
            [
                'name' => __("Course", 'presto-player'),
                'slug' => 'course',
                'skin' => 'stacked',
                'icon' => 'welcome-learn-more',
                "play-large" => true,
                'rewind' => true,
                'play' => true,
                "fast-forward" => true,
                'progress' => true,
                'current-time' => true,
                'mute' => true,
                'volume' => true,
                'speed' => true,
                'pip' => true,
                'fullscreen' => true,
                'captions' => false,
                // behavior
                'save_player_position' => true,
                'auto_hide' => true,
                'reset_on_end' => true,
                'sticky_scroll' => true,
                // youtube
                'hide_youtube' => false,
                'lazy_load_youtube' => false,
                'hide_logo' => false,
                'is_locked' => true
            ]
        );
    }

    public function seedYoutube()
    {
        $preset = new Preset();
        $preset->updateOrCreate(
            ['slug' => 'youtube'],
            [
                'name' => __("Youtube Optimized", 'presto-player'),
                'slug' => 'youtube',
                'skin' => 'stacked',
                'icon' => 'youtube',
                "play-large" => true,
                'rewind' => true,
                'play' => true,
                "fast-forward" => true,
                'progress' => true,
                'current-time' => true,
                'mute' => true,
                'volume' => true,
                'speed' => true,
                'pip' => false,
                'fullscreen' => true,
                'captions' => false,
                // behavior
                'save_player_position' => false,
                'auto_hide' => true,
                'reset_on_end' => true,
                'sticky_scroll' => false,
                // youtube
                'hide_youtube' => false,
                'lazy_load_youtube' => true,
                'action_bar' => [
                    'enabled' => true,
                    'percentage_start' => 0,
                    'text' => __('Subscribe to our channel', 'presto-player'),
                    'background_color' => '#1d1d1d',
                    'button_type' => 'youtube',
                    'button_count' => false,
                ],
                'hide_logo' => false,
                'is_locked' => true
            ]
        );
    }

    public function seedSimple()
    {
        $preset = new Preset();
        $preset->updateOrCreate(
            ['slug' => 'simple'],
            [
                'name' => __("Simple", 'presto-player'),
                'slug' => 'simple',
                'icon' => 'video-alt3',
                "play-large" => true,
                'rewind' => false,
                'play' => true,
                "fast-forward" => false,
                'progress' => true,
                'current-time' => false,
                'mute' => false,
                'volume' => false,
                'speed' => false,
                'pip' => false,
                'fullscreen' => true,
                'captions' => false,
                // behavior
                'save_player_position' => false,
                'auto_hide' => true,
                'reset_on_end' => true,
                'sticky_scroll' => false,
                // youtube
                'hide_youtube' => true,
                'lazy_load_youtube' => false,
                'hide_logo' => false,
                'is_locked' => true
            ]
        );
    }

    public function seedMinimal()
    {
        $preset = new Preset();
        $preset->updateOrCreate(
            ['slug' => 'minimal'],
            [
                'name' => __("Minimal", 'presto-player'),
                'slug' => "minimal",
                'icon' => 'controls-play',
                "play-large" => true,
                'rewind' => false,
                'play' => false,
                "fast-forward" => false,
                'progress' => false,
                'current-time' => false,
                'mute' => false,
                'volume' => false,
                'speed' => false,
                'pip' => false,
                'fullscreen' => false,
                'captions' => false,
                // behavior
                'save_player_position' => false,
                'auto_hide' => false,
                'reset_on_end' => true,
                'sticky_scroll' => false,
                // youtube
                'hide_youtube' => true,
                'lazy_load_youtube' => false,
                'hide_logo' => true,
                'is_locked' => true
            ]
        );
    }
}
