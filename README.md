# Places API

A RESTful API for managing places (stores, cafes, restaurants, etc.)

I implemented the API to manage 'places' as a generalized resource that can encompass various types of establishments including stores.

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

## Testing
Use Postman or curl to test endpoints. See `documentation.html` for full examples.