# Normalize LD-JSON FAQ

![PHPUnit](https://github.com/alejandroariassl/normalize-ld-json-faq/actions/workflows/phpunit.yml/badge.svg)

Genera automáticamente JSON-LD para FAQs a partir de acordeones en WordPress.  

## Instalación

1. Copia la carpeta `normalize-ld-json-faq` en `wp-content/plugins/`.
2. Activa el plugin desde el panel de WordPress.
3. Visita una página con acordeones; el JSON-LD se genera automáticamente en el `<head>`.

## Cómo funciona

El plugin detecta los acordeones de Elementor y extrae preguntas y respuestas para generar el JSON-LD FAQPage siguiendo la especificación de schema.org.

## Plugins soportados

- Elementor

## Desarrollo futuro
 - Añadir soporte para otros constructores visuales (Gutenberg, WPBakery, etc.)