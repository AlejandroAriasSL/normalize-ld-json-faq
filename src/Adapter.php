<?php
namespace NormalizeLdJsonFAQ;

Interface Adapter
{
    public function extractFaq($post_id): array;
    public function enqueueScript(): void;
}