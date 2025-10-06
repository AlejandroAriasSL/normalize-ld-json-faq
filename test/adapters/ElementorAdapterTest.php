<?php
require_once __DIR__ . '/stubs.php';

use NormalizeLdJsonFAQ\Adapters\ElementorAdapter;
use PHPUnit\Framework\TestCase;

class ElementorAdapterTest extends TestCase {

    public function testShouldReturnQuestionAndAnswers()
    {
        $json = file_get_contents(__DIR__ . '/../fixtures/elementor-ast.json');
        $data = json_decode($json, true);

        global $mock_json;
        $mock_json = json_encode($data);

        $adapter = new ElementorAdapter();

        $result = $adapter->extractFaq(123);

        $this->assertCount(2, $result);
        $this->assertSame('¿Qué es este plugin?', $result[0]['question']);
        $this->assertStringContainsString('Compatible con Elementor', $result[0]['answer']);
        $this->assertSame('¿Cómo se instala?', $result[1]['question']);
        $this->assertStringContainsString('Descarga el plugin', $result[1] ['answer']);
    }

    public function testReturnsEmptyArrayWhenNoNestedAccordion()
    {
        $json = json_encode([
            [
                "elType" => "widget",
                "widgetType" => "text-editor",
                "settings" => [
                    "editor" => "<p>Solo un texto, sin acordeón.</p>"
                ],
                "elements" => []
            ]
        ]);

        global $mock_json;
        $mock_json = $json;

        $adapter = new ElementorAdapter();
        $result = $adapter->extractFaq(123);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}
?>