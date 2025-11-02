<?php
// public/index.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PlacesController;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// PORTABLE: Extract API path dynamically (works in any environment)
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$apiPath = str_replace($scriptDir, '', $path);
$apiPath = strtok($apiPath, '?'); // Remove query string

// Ensure it starts with /
if (empty($apiPath) || $apiPath[0] !== '/') {
    $apiPath = '/' . $apiPath;
}

$controller = new PlacesController();

// Simple router
if ($apiPath === '/api/places' && $method === 'GET') {
    $controller->index();
} 
elseif ($apiPath === '/api/places' && $method === 'POST') {
    $controller->store();  // ← Fixed: call actual method
}
elseif (preg_match('#^/api/places/(\d+)$#', $apiPath, $matches) && $method === 'GET') {
    $controller->show($matches[1]);  // ← Fixed: call actual method
}
elseif (preg_match('#^/api/places/(\d+)$#', $apiPath, $matches) && $method === 'PUT') {
    $controller->update($matches[1]);  // ← Fixed: call actual method
}
elseif (preg_match('#^/api/places/(\d+)$#', $apiPath, $matches) && $method === 'DELETE') {
    $controller->destroy($matches[1]);  // ← Fixed: call actual method
}
else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Endpoint not found',
        'requested' => $apiPath,
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