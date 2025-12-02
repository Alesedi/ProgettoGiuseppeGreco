<?php
/**
 * Script per generare immagini placeholder SVG per i pannelli solari
 * e aggiornare il database
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';

$pdo = Database::getConnection();

echo "=== Generazione Immagini Placeholder SVG ===\n\n";

$imgDir = __DIR__ . '/../public/images/products';
if (!is_dir($imgDir)) {
    mkdir($imgDir, 0755, true);
    echo "✓ Cartella creata\n";
}

// Template SVG per pannelli solari
$svgTemplates = [
    'monocristallino' => function($title) {
        return <<<SVG
<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="mono-grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#1a1a2e;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#0f3460;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="800" height="600" fill="url(#mono-grad)"/>
  <g opacity="0.3">
    <rect x="50" y="50" width="340" height="240" fill="none" stroke="#4ecdc4" stroke-width="2"/>
    <rect x="410" y="50" width="340" height="240" fill="none" stroke="#4ecdc4" stroke-width="2"/>
    <rect x="50" y="310" width="340" height="240" fill="none" stroke="#4ecdc4" stroke-width="2"/>
    <rect x="410" y="310" width="340" height="240" fill="none" stroke="#4ecdc4" stroke-width="2"/>
  </g>
  <text x="400" y="320" font-family="Arial, sans-serif" font-size="32" fill="#4ecdc4" text-anchor="middle" font-weight="bold">$title</text>
  <text x="400" y="360" font-family="Arial, sans-serif" font-size="20" fill="#95e1d3" text-anchor="middle">Monocristallino Full Black</text>
</svg>
SVG;
    },
    'bifacciale' => function($title) {
        return <<<SVG
<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bifa-grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#2193b0;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#6dd5ed;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="800" height="600" fill="url(#bifa-grad)"/>
  <g opacity="0.4">
    <rect x="100" y="100" width="280" height="180" fill="none" stroke="#fff" stroke-width="3" rx="10"/>
    <rect x="420" y="100" width="280" height="180" fill="none" stroke="#fff" stroke-width="3" rx="10"/>
    <rect x="100" y="320" width="280" height="180" fill="none" stroke="#fff" stroke-width="3" rx="10"/>
    <rect x="420" y="320" width="280" height="180" fill="none" stroke="#fff" stroke-width="3" rx="10"/>
  </g>
  <text x="400" y="320" font-family="Arial, sans-serif" font-size="32" fill="#fff" text-anchor="middle" font-weight="bold">$title</text>
  <text x="400" y="360" font-family="Arial, sans-serif" font-size="20" fill="#e0f7fa" text-anchor="middle">Pannello Bifacciale</text>
</svg>
SVG;
    },
    'flessibile' => function($title) {
        return <<<SVG
<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="flex-grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#ee9ca7;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#ffdde1;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="800" height="600" fill="url(#flex-grad)"/>
  <path d="M 150 150 Q 250 100, 350 150 T 550 150 Q 650 100, 750 150 L 750 450 Q 650 500, 550 450 T 350 450 Q 250 500, 150 450 Z" 
        fill="none" stroke="#d32f2f" stroke-width="3" opacity="0.5"/>
  <text x="400" y="320" font-family="Arial, sans-serif" font-size="32" fill="#d32f2f" text-anchor="middle" font-weight="bold">$title</text>
  <text x="400" y="360" font-family="Arial, sans-serif" font-size="20" fill="#c62828" text-anchor="middle">Pannello Flessibile</text>
</svg>
SVG;
    },
];

$products = [
    ['sku' => 'MONO-S-BALCONE', 'type' => 'monocristallino', 'title' => 'BALCONE'],
    ['sku' => 'MONO-M-RESIDENZIALE', 'type' => 'monocristallino', 'title' => 'RESIDENZIALE'],
    ['sku' => 'MONO-L-COMMERCIALE', 'type' => 'monocristallino', 'title' => 'COMMERCIALE'],
    ['sku' => 'BIFA-S-GARDEN', 'type' => 'bifacciale', 'title' => 'GARDEN'],
    ['sku' => 'BIFA-M-PERGOLA', 'type' => 'bifacciale', 'title' => 'PERGOLA'],
    ['sku' => 'BIFA-L-GROUND', 'type' => 'bifacciale', 'title' => 'GROUND UTILITY'],
    ['sku' => 'FLEX-XS-ZAINO', 'type' => 'flessibile', 'title' => 'ZAINO'],
    ['sku' => 'FLEX-S-MARINE', 'type' => 'flessibile', 'title' => 'MARINE'],
    ['sku' => 'FLEX-L-VANLIFE', 'type' => 'flessibile', 'title' => 'VAN LIFE'],
];

echo "Generazione SVG placeholder...\n";

foreach ($products as $prod) {
    $filename = strtolower($prod['sku']) . '.svg';
    $filepath = $imgDir . '/' . $filename;
    
    $svg = $svgTemplates[$prod['type']]($prod['title']);
    file_put_contents($filepath, $svg);
    
    echo "  ✓ Creato: $filename\n";
    
    // Aggiorna database
    $relPath = '/public/images/products/' . $filename;
    $stmt = $pdo->prepare("UPDATE products SET image = :img WHERE sku = :sku");
    $stmt->execute(['img' => $relPath, 'sku' => $prod['sku']]);
}

echo "\n=== Link per scaricare immagini REALI ===\n\n";

echo "Puoi sostituire i placeholder con queste immagini reali:\n\n";

echo "MONOCRISTALLINO:\n";
echo "1. Balcone: https://images.pexels.com/photos/356036/pexels-photo-356036.jpeg\n";
echo "2. Residenziale: https://images.pexels.com/photos/433308/pexels-photo-433308.jpeg\n";
echo "3. Commerciale: https://images.pexels.com/photos/2800832/pexels-photo-2800832.jpeg\n\n";

echo "BIFACCIALE:\n";
echo "1. Garden: https://images.pexels.com/photos/9875415/pexels-photo-9875415.jpeg\n";
echo "2. Pergola: https://images.pexels.com/photos/8853508/pexels-photo-8853508.jpeg\n";
echo "3. Ground: https://images.pexels.com/photos/9875457/pexels-photo-9875457.jpeg\n\n";

echo "FLESSIBILE:\n";
echo "1. Zaino: https://images.pexels.com/photos/5650026/pexels-photo-5650026.jpeg\n";
echo "2. Marine: https://images.pexels.com/photos/1072179/pexels-photo-1072179.jpeg\n";
echo "3. Van Life: https://images.pexels.com/photos/2533092/pexels-photo-2533092.jpeg\n\n";

echo "Per usarle:\n";
echo "1. Scarica le immagini dai link sopra\n";
echo "2. Salvale in: public/images/products/\n";
echo "3. Rinominale come: mono-s-balcone.jpg, mono-m-residenziale.jpg, ecc.\n";
echo "4. Esegui: php scripts/update_image_extensions.php\n\n";

echo "=== Completato! ===\n";
echo "Immagini placeholder SVG create e associate.\n";
