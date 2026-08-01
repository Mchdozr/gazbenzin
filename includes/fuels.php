<?php
declare(strict_types=1);

function fuelDefinitions(): array
{
    return [
        ['key' => 'diesel', 'label' => 'Diesel'],
        ['key' => 'e10', 'label' => 'Super E10'],
        ['key' => 'e5', 'label' => 'Super E5'],
        ['key' => 'superPlus', 'label' => 'Super Plus'],
        ['key' => 'adBlue', 'label' => 'AdBlue'],
    ];
}

function formatPriceParts(float $value): array
{
    $raw = number_format($value, 3, ',', '');
    return [
        'full' => $raw,
        'main' => substr($raw, 0, -1),
        'sup' => substr($raw, -1),
    ];
}

function brandLettersHtml(string $text = 'LTC TANKSTELLE'): string
{
    $html = '';
    $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    foreach ($chars as $char) {
        if ($char === ' ') {
            $html .= '<span class="brand-space" aria-hidden="true"></span>';
            continue;
        }
        $html .= '<span>' . htmlspecialchars($char, ENT_QUOTES, 'UTF-8') . '</span>';
    }
    return $html;
}
