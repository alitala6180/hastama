<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Helpers;
use FontLib\Font;

$fontPath = __DIR__ . '/public/fonts/Vazir.ttf';
$fontName = 'Vazir';

if (!file_exists($fontPath)) {
    echo "Font file not found: $fontPath\n";
    exit(1);
}

$options = new Options();
$options->set('fontDir', __DIR__ . '/storage/fonts');
$options->set('fontCache', __DIR__ . '/storage/fonts');
// Allow dompdf to access project files
$options->set('chroot', [__DIR__]);

$dompdf = new Dompdf($options);

// Enable dompdf warnings output
$GLOBALS['_dompdf_show_warnings'] = true;

$fontMetrics = $dompdf->getFontMetrics();

$style = [
    'family' => $fontName,
    'weight' => 'normal',
    'style' => 'normal'
];

echo "Attempting to read font file via Helpers::getFileContent()...\n";
[$content, $headers] = Helpers::getFileContent($fontPath);
if ($content === null) {
    echo "Could not read font via Helpers::getFileContent()\n";
} else {
    echo "Read font content, size: " . strlen($content) . " bytes\n";
}

echo "Attempting to load font with FontLib...\n";
$font = Font::load($fontPath);
if (!$font) {
    echo "FontLib::load failed\n";
} else {
    echo "FontLib loaded, parsing...\n";
    $font->parse();
    $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vazir_test.ufm';
    $font->saveAdobeFontMetrics($tmp);
    if (file_exists($tmp)) {
        echo "Saved test UFM to $tmp\n";
        @unlink($tmp);
    } else {
        echo "Failed to save test UFM\n";
    }
}

echo "Calling FontMetrics::registerFont()...\n";
$registered = $fontMetrics->registerFont($style, $fontPath);
if (!$registered) {
    echo "Retrying with file:// prefix...\n";
    $registered = $fontMetrics->registerFont($style, 'file://' . realpath($fontPath));
}

if ($registered) {
    echo "Font registered: $fontName\n";
    $fontMetrics->saveFontFamilies();
    echo "Installed fonts file: " . $fontMetrics->getUserFontsFilePath() . "\n";
    echo "storage/fonts listing:\n";
    $files = scandir(__DIR__ . '/storage/fonts');
    foreach ($files as $f) { echo " - $f\n"; }
    exit(0);
} else {
    echo "Failed to register font: $fontName\n";
    echo "storage/fonts listing:\n";
    $files = scandir(__DIR__ . '/storage/fonts');
    foreach ($files as $f) { echo " - $f\n"; }
    exit(2);
}
