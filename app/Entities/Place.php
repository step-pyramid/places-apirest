<?php
// app/Entities/Place.php
namespace App\Entities;

class Place
{
    // Core properties
    public $id;
    public $name;
    public $description;
    public $category;
    public $address;
    public $city;
    public $rating;
    public $submitted_by;
    public $created_at;
    public $updated_at;

    // Valid categories
    const CATEGORIES = [
        'cafe', 'restaurant', 'museum', 'park', 'landmark', 
        'shop', 'hotel', 'theater', 'bar', 'bakery', 'library',
        'gallery', 'monument', 'mall', 'market'
    ];

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->rating = isset($data['rating']) ? floatval($data['rating']) : null;
        $this->submitted_by = $data['submitted_by'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'address' => $this->address,
            'city' => $this->city,
            'rating' => $this->rating,
            'submitted_by' => $this->submitted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
    
    public function isValid() {
        return empty($this->getValidationErrors());
    }
    
    public function getValidationErrors() {
        $errors = [];
        
        // Required fields validation
        if (empty(trim($this->name))) {
            $errors[] = 'Name is required';
        }
        
        if (empty(trim($this->category))) {
            $errors[] = 'Category is required';
        }
        
        if (empty(trim($this->address))) {
            $errors[] = 'Address is required';
        }
        
        if (empty(trim($this->city))) {
            $errors[] = 'City is required';
        }
        
        // Field length validation
        if (strlen(trim($this->name)) > 255) {
            $errors[] = 'Name must be less than 255 characters';
        }
        
        if (strlen(trim($this->category)) > 50) {
            $errors[] = 'Category must be less than 50 characters';
        }
        
        if (strlen(trim($this->city)) > 100) {
            $errors[] = 'City must be less than 100 characters';
        }
        
        // Category validation
        if (!empty($this->category) && !in_array($this->category, self::CATEGORIES)) {
            $errors[] = 'Category must be one of: ' . implode(', ', self::CATEGORIES);
        }
        
        // Rating validation
        if ($this->rating !== null && ($this->rating < 0 || $this->rating > 5)) {
            $errors[] = 'Rating must be between 0 and 5';
        }
        
        return $errors;
    }
    
    // Helper methods
    public function hasRating() {
        return $this->rating !== null;
    }
    
    public function getFormattedRating() {
        return $this->rating !== null ? number_format($this->rating, 1) : 'Not rated';
    }
    
    public function getFullAddress() {
        return $this->address . ', ' . $this->city;
    }
}