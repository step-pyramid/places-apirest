<?php
// public/index.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PlacesController;

$method = $_SERVER['REQUEST_METHOD'];

// ROBUST PATH EXTRACTION - works in any environment
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remove query string
$requestUri = strtok($requestUri, '?');

// If the script is not in the root, remove the script directory
if (dirname($scriptName) !== '/') {
    $basePath = dirname($scriptName);
    if (strpos($requestUri, $basePath) === 0) {
        $path = substr($requestUri, strlen($basePath));
    } else {
        $path = $requestUri;
    }
} else {
    $path = $requestUri;
}

// Ensure path starts with /
if (empty($path) || $path[0] !== '/') {
    $path = '/' . $path;
}

// Debug output (remove in production)
// error_log("Path extracted: " . $path);

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$controller = new PlacesController();

// Router
if ($path === '/api/places' && $method === 'GET') {
    $controller->index();
} 
elseif ($path === '/api/places' && $method === 'POST') {
    $controller->store();
}
elseif (preg_match('#^/api/places/(\d+)$#', $path, $matches) && $method === 'GET') {
    $controller->show($matches[1]);
}
elseif (preg_match('#^/api/places/(\d+)$#', $path, $matches) && $method === 'PUT') {
    $controller->update($matches[1]);
}
elseif (preg_match('#^/api/places/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    $controller->destroy($matches[1]);
}
else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Endpoint not found',
        'requested' => $path,
        'method' => $method,
        'available_endpoints' => [
            'GET /api/places',
            'POST /api/places', 
            'GET /api/places/{id}',
            'PUT /api/places/{id}',
            'DELETE /api/places/{id}'
        ]
    ]);
}
?>