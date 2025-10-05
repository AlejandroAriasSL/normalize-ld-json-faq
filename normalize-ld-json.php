
<?php
/* 
Plugin Name: Normalize LD-JSON FAQ
Description: Limpia y genera JSON-LD FAQ para todos los acordeones
Version: 0.1
Author: Alejandro Arias Silva
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/vendor/autoload.php';

use NormalizeLdJsonFAQ\Adapters\ElementorAdapter;

add_action('wp_head', 'normalize_ldjson');
function normalize_ldjson() {
    if (!is_singular()) return;

    global $post;

    $adapter = new ElementorAdapter();
    $qas = $adapter->extractFaq($post->ID);

    if (!$qas) return;

    $mainEntity = array_map(function($qa){
        return [
            '@type' => 'Question',
            'name' => $qa['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $qa['answer']
            ]
        ];
    }, $qas);

    $ld = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $mainEntity
    ];

    echo "<script type='application/ld+json'>\n" . wp_json_encode($ld, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . "\n</script>";
}

/**
 * Normalize LD-JSON FAQ
 * Copyright (C) 2025  Alejandro Arias
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <https://www.gnu.org/licenses/>.
 */
?>