<?php
// app/Controllers/PlacesController.php
namespace App\Controllers;

use App\Entities\Place;
use App\Repositories\PlaceRepository;

class PlacesController
{
    private $placeRepository;

    public function __construct() {
        $this->placeRepository = new PlaceRepository();
    }

    // GET /api/places - Get all places with optional filtering & sorting
    public function index() {
        // Get query parameters for filtering and sorting
        $filters = [];
        if (isset($_GET['category'])) $filters['category'] = $_GET['category'];
        if (isset($_GET['city'])) $filters['city'] = $_GET['city'];
        if (isset($_GET['rating'])) $filters['rating'] = $_GET['rating'];
        if (isset($_GET['submitted_by'])) $filters['submitted_by'] = $_GET['submitted_by'];
        
        $sort = $_GET['sort'] ?? 'created_at';
        $order = $_GET['order'] ?? 'DESC';

        try {
            $places = $this->placeRepository->findAll($filters, $sort, $order);
            
            $placesArray = array_map(function(Place $place) {
                return $place->toArray();
            }, $places);
            
            echo json_encode([
                'status' => 'success',
                'data' => $placesArray,
                'count' => count($placesArray),
                'filters' => $filters,
                'sort' => $sort,
                'order' => $order
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch places',
                'error' => $e->getMessage()
            ]);
        }
    }

    // GET /api/places/{id} - Get single place by ID
    public function show($id) {
        try {
            $place = $this->placeRepository->findById($id);
            
            if ($place) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $place->toArray()
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Place not found'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch place',
                'error' => $e->getMessage()
            ]);
        }
    }

    // POST /api/places - Create new place
    public function store() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON data'
                ]);
                return;
            }

            // You might want to get this from authentication/session in a real app
            if (!isset($input['submitted_by'])) {
                $input['submitted_by'] = 'anonymous'; // or get from session/auth
            }
            
            // Create Place entity from input
            $place = new Place($input);
            
            // Validate using entity's validation
            $errors = $place->getValidationErrors();
            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }
            
            // Save to database
            if ($this->placeRepository->create($place)) {
                http_response_code(201);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Place created successfully',
                    'data' => $place->toArray()
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Failed to create place'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create place',
                'error' => $e->getMessage()
            ]);
        }
    }

    // PUT /api/places/{id} - Update existing place
    public function update($id) {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON data'
                ]);
                return;
            }
            
            // Check if place exists
            $existingPlace = $this->placeRepository->findById($id);
            if (!$existingPlace) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Place not found'
                ]);
                return;
            }
            
            // Update the entity with new data
            $existingPlace->name = $input['name'] ?? $existingPlace->name;
            $existingPlace->description = $input['description'] ?? $existingPlace->description;
            $existingPlace->category = $input['category'] ?? $existingPlace->category;
            $existingPlace->address = $input['address'] ?? $existingPlace->address;
            $existingPlace->city = $input['city'] ?? $existingPlace->city;
            $existingPlace->rating = isset($input['rating']) ? floatval($input['rating']) : $existingPlace->rating;
            // Note: You might want to restrict who can change this field
            if (isset($input['submitted_by'])) {
                $existingPlace->submitted_by = $input['submitted_by'];
            }
            
            // Validate updated entity
            $errors = $existingPlace->getValidationErrors();
            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }
            
            // Save updates
            if ($this->placeRepository->update($existingPlace)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Place updated successfully',
                    'data' => $existingPlace->toArray()
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update place'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update place',
                'error' => $e->getMessage()
            ]);
        }
    }

    // DELETE /api/places/{id} - Delete place
    public function destroy($id) {
        try {
            // Check if place exists
            $existingPlace = $this->placeRepository->findById($id);
            if (!$existingPlace) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Place not found'
                ]);
                return;
            }
            
            if ($this->placeRepository->delete($id)) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Place deleted successfully'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete place'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete place',
                'error' => $e->getMessage()
            ]);
        }
    }
}