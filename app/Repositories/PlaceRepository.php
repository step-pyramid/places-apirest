<?php
// app/Repositories/PlaceRepository.php
namespace App\Repositories;

use App\Config\Database;
use App\Entities\Place;
use PDO;

class PlaceRepository
{
    private $conn;
    private $table_name = "places";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // GET all places with optional filtering and sorting
    public function findAll($filters = [], $sort = 'created_at', $order = 'DESC'): array {
        // Base query
        $query = "SELECT * FROM " . $this->table_name;
        
        // Add WHERE clauses for filters
        $whereConditions = [];
        $params = [];
        
        if (!empty($filters['category'])) {
            $whereConditions[] = "category = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['city'])) {
            $whereConditions[] = "city = :city";
            $params[':city'] = $filters['city'];
        }
        
        if (!empty($filters['rating'])) {
            $whereConditions[] = "rating >= :rating";
            $params[':rating'] = floatval($filters['rating']);
        }

        if (!empty($filters['submitted_by'])) {
            $whereConditions[] = "submitted_by = :submitted_by";
            $params[':submitted_by'] = $filters['submitted_by'];
        }
        
        // Add WHERE clause if filters exist
        if (!empty($whereConditions)) {
            $query .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        // Add ORDER BY for sorting
        $validSortFields = ['name', 'category', 'city', 'rating', 'created_at', 'submitted_by'];
        $validOrders = ['ASC', 'DESC'];
        
        $sortField = in_array($sort, $validSortFields) ? $sort : 'created_at';
        $sortOrder = in_array(strtoupper($order), $validOrders) ? strtoupper($order) : 'DESC';
        
        if ($sortField === 'submitted_by') {
            $query .= " ORDER BY COALESCE(" . $sortField . ", '') " . $sortOrder;
        } else {
            $query .= " ORDER BY " . $sortField . " " . $sortOrder;
        }
        
        // Execute query
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        $places = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $places[] = new Place($row);
        }
        return $places;
    }

    // GET single place by ID
    public function findById($id): ?Place {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Place($row) : null;
    }

    // CREATE new place
    public function create(Place $place): bool {
        $query = "INSERT INTO " . $this->table_name . " 
                 (name, description, category, address, city, rating, submitted_by, created_at) 
                 VALUES (:name, :description, :category, :address, :city, :rating, :submitted_by, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize data
        $name = htmlspecialchars(strip_tags($place->name));
        $description = htmlspecialchars(strip_tags($place->description));
        $category = htmlspecialchars(strip_tags($place->category));
        $address = htmlspecialchars(strip_tags($place->address));
        $city = htmlspecialchars(strip_tags($place->city));
        $rating = $place->rating;
        $submitted_by = htmlspecialchars(strip_tags($place->submitted_by ?? 'anonymous')); 
        if (empty($submitted_by)) {
            $submitted_by = 'anonymous';
        }
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':submitted_by', $submitted_by);
        
        if ($stmt->execute()) {
            $place->id = $this->conn->lastInsertId();
            $place->submitted_by = $submitted_by;
            return true;
        }
        return false;
    }

    // UPDATE place
    public function update(Place $place): bool {
        $query = "UPDATE " . $this->table_name . " 
                 SET name = :name, description = :description, category = :category,
                     address = :address, city = :city, rating = :rating,
                     submitted_by = :submitted_by, updated_at = NOW()
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize data
        $name = htmlspecialchars(strip_tags($place->name));
        $description = htmlspecialchars(strip_tags($place->description));
        $category = htmlspecialchars(strip_tags($place->category));
        $address = htmlspecialchars(strip_tags($place->address));
        $city = htmlspecialchars(strip_tags($place->city));
        $rating = $place->rating;
        $submitted_by = htmlspecialchars(strip_tags($place->submitted_by ?? 'anonymous'));
        if (empty($submitted_by)) {
            $submitted_by = 'anonymous';
        }
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':submitted_by', $submitted_by);
        $stmt->bindParam(':id', $place->id);
        
        if ($stmt->execute()) {
            // FIX: Update the Place object with the actual submitted_by value
            $place->submitted_by = $submitted_by;
            return true;
        }
        return false;
    }

    // DELETE place
    public function delete($id): bool {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get all unique cities (for filtering)
    public function getCities(): array {
        $query = "SELECT DISTINCT city FROM " . $this->table_name . " ORDER BY city";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get all categories (for filtering)
    public function getCategories(): array {
        return Place::CATEGORIES;
    }
}