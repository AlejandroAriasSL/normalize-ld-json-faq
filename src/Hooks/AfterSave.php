<?php
namespace NormalizeLdJsonFAQ\Hooks;

if (!defined('ABSPATH')) exit;

add_action('elementor/editor/after_save_post', function($post_id) {
    $rawAST = get_post_meta($post_id, '_elementor_data', true);
    if (!$rawAST) return;

    $data = json_decode($rawAST, true);
    if (!$data) return;

    $walker = function (&$elements) use (&$walker) {
        foreach ($elements as &$element) {
            if (($element['elType'] ?? '') === 'widget' && ($element['widgetType'] ?? '') === 'text-editor') {
                $editor = $element['settings']['editor'] ?? '';
                if (empty($editor) && !empty($element['settings']['editor_backup'])) {
                    $element['settings']['editor'] = $element['settings']['editor_backup'];
                    unset($element['settings']['editor_backup']);
                }
            }
            if (!empty($element['elements'])) $walker($element['elements']);
        }
    };

    if (!empty($data['elements'])) {
        $walker($data['elements']);
        update_post_meta($post_id, '_elementor_data', wp_json_encode($data));
    }
}, 20, 1);