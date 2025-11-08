<?php
header('Content-Type: text/plain');

echo "=== Routing Debug ===\n\n";

echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";

$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
echo "Script Directory: '$scriptDir'\n";

$apiPath = str_replace($scriptDir, '', $_SERVER['REQUEST_URI']);
echo "Raw API Path: '$apiPath'\n";

$apiPath = strtok($apiPath, '?');
echo "Clean API Path: '$apiPath'\n";

echo "\nExpected: '/api/places'\n";

if ($apiPath === '/api/places') {
    echo "✅ Routing is CORRECT\n";
} else {
    echo "❌ Routing is BROKEN - got '$apiPath' instead of '/api/places'\n";
    
    echo "\nTrying manual fix...\n";
    // Manual path extraction
    $requestUri = $_SERVER['REQUEST_URI'];
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    
    // Remove base path
    if (strpos($requestUri, $basePath) === 0) {
        $path = substr($requestUri, strlen($basePath));
    } else {
        $path = $requestUri;
    }
    
    // Remove query string
    $path = parse_url($path, PHP_URL_PATH);
    echo "Manual path extraction: '$path'\n";
}
?>