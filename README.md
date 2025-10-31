# Places API

A RESTful API for managing places (stores, cafes, restaurants, etc.)

**Design Note**: I implemented the API to manage 'places' as a generalized resource that can encompass various types of establishments including stores. This demonstrates flexible data modeling and scalable architecture.

## Quick Start

### Prerequisites
- PHP 8.2+
- MySQL/MariaDB database

## Setup
1. Import the SQL database schema (importsql.sql)
2. Configure database connection in `app/Config/database.ini`
3. Point your web server to the `public` directory

## API Endpoints
- `GET /api/places` - List all places with filtering & sorting
- `POST /api/places` - Create a new place  
- `GET /api/places/{id}` - Get single place
- `PUT /api/places/{id}` - Update place
- `DELETE /api/places/{id}` - Delete place

## Features

- **Full CRUD operations** with JSON input/output
- **Filtering** by category, city, rating, and submitter
- **Sorting** by any field with ascending/descending order
- **Input validation** and error handling
- **Comprehensive documentation**

## Testing
Use **Postman** or similar API client to test endpoints. See `documentation.html` for complete API documentation with request/response examples.

The API has been thoroughly tested with Postman and is fully functional.