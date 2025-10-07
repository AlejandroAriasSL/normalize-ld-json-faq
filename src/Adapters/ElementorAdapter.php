<?php
namespace NormalizeLdJsonFAQ\Adapters;

use NormalizeLdJsonFAQ\Adapter;
use NormalizeLdJsonFAQ\Utils\HTMLExtractor;

class ElementorAdapter implements Adapter {

    public function extractFaq($post_id): array
    {
        $rawAST = get_post_meta($post_id, '_elementor_data', true);
        if (!$rawAST) return [];

        $data = json_decode($rawAST, true);
        if (!$data) return [];

        $qas = [];
        $this->parseElements($data, $qas);
        return $qas;
    }

    public function enqueueScript(): void 
    {
        add_action('elementor/editor/after_enqueue_scripts', function() {
            wp_enqueue_script(
                'my-elementor-editor',
                plugin_dir_url(__FILE__) . '/../Runtime/onEdit-elementor.js',
                [],
                '1.0',
                true
            );
        });
    }

    /**
     * Recorre recursivamente el AST de Elementor para encontrar el primer nested-accordion.
     * 
     * @param array $elements Subárbol de elementos.
     * @param array $qas Array donde se guardan las Q&A.
     * @return bool True si ya se encontró un nested-accordion y no hay que seguir descendiendo.
     */
    private function parseElements(array $elements, array &$qas): bool
    {
        foreach ($elements as $element) 
        {

            $elType = $element['elType'] ?? null;
            $widgetType = $element['widgetType'] ?? null;

            if ($elType === 'widget' && $widgetType === 'nested-accordion') {
                foreach ($element["elements"] as $accordion_element)
                {
                    $title = $accordion_element['settings']['_title'] ?? null;

                    $answers = $this->extractEditorText($accordion_element["elements"]);
    
                    foreach ($answers as $answer) {
                        $qas[] = [
                            'question' => $title,
                            'answer' => $answer
                        ];
                    }
                }

                return true;
            }

            if (!empty($element['elements'])) 
            {
                $found = $this->parseElements($element['elements'], $qas);
                if ($found) return true;
            }
        }

        return false;
    }

    private function extractEditorText(array $elements): array
    {
        $texts = [];

        foreach ($elements as $element) 
        {
            $elType = $element['elType'] ?? null;
            $widgetType = $element['widgetType'] ?? null;

            if ($elType === 'widget' && $widgetType === 'text-editor') {
               $editorHtml = $element['settings']['editor'] ?? '';
               $texts[] = HTMLExtractor::extractLastParagraph($editorHtml);
            }

            if (!empty($element['elements'])) {
                $texts = array_merge($texts, $this->extractEditorText($element['elements']));
            }
        }

        return $texts;
    }
}


?>