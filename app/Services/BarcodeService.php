<?php

namespace App\Services;

class BarcodeService
{
    private static array $patterns = [
        '0' => 'bwbwBwBwb', '1' => 'BwbwBwbwb', '2' => 'bwBwBwbwb', '3' => 'BwBwbwbwb',
        '4' => 'bwbwBwBwb', '5' => 'BwbwBwBwb', '6' => 'bwBwBwBwb', '7' => 'bwbwbwBwB',
        '8' => 'BwbwbwBwB', '9' => 'bwBwbwBwB', 'A' => 'BwbwbwbwB', 'B' => 'bwBwbwbwB',
        'C' => 'BwBwbwbwB', 'D' => 'bwbwBwbwB', 'E' => 'BwbwBwbwB', 'F' => 'bwBwBwbwB',
        'G' => 'bwbwbwBwB', 'H' => 'BwbwbwBwB', 'I' => 'bwBwbwBwB', 'J' => 'bwbwBwBwB',
        'K' => 'Bwbwbwbwb', 'L' => 'bwBwbwbwb', 'M' => 'BwBwbwbwb', 'N' => 'bwbwBwbwb',
        'O' => 'BwbwBwbwb', 'P' => 'bwBwBwbwb', 'Q' => 'bwbwbwBwb', 'R' => 'BwbwbwBwb',
        'S' => 'bwBwbwBwb', 'T' => 'bwbwBwBwb', 'U' => 'Bwbwbwbwb', 'V' => 'bwBwbwbwb',
        'W' => 'BwBwbwbwb', 'X' => 'bwbwBwbwb', 'Y' => 'BwbwBwbwb', 'Z' => 'bwBwBwbwb',
        '-' => 'bwbwbwbwB', '.' => 'Bwbwbwbwb', ' ' => 'bwbwBwbwb', '*' => 'bwbwBwBwb',
    ];

    public static function svg(string $code, int $height = 30, int $narrow = 1, int $wide = 3): string
    {
        $clean = strtoupper(preg_replace('/[^0-9A-Z\-]/', '', $code));
        if (empty($clean)) {
            $clean = 'MCTSC';
        }
        $code = '*' . $clean . '*';
        $elements = [];
        $totalWidth = 0;

        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            $pattern = self::$patterns[$char] ?? self::$patterns['-'];
            for ($p = 0; $p < strlen($pattern); $p++) {
                $isBar = ($p % 2 === 0);
                $isWide = ctype_upper($pattern[$p]);
                $w = $isWide ? $wide : $narrow;
                if ($isBar) {
                    $elements[] = sprintf('<rect x="%d" y="0" width="%d" height="%d" fill="#0f172a"/>', $totalWidth, $w, $height);
                }
                $totalWidth += $w;
            }
            $totalWidth += $narrow; // character separator
        }

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" style="display:block;margin:0 auto;max-width:100%%;">%s</svg>',
            $totalWidth, $height, $totalWidth, $height, implode('', $elements)
        );
    }
}
