# Places API

# API Places - Gestion de Lieux d'Intérêt

**API REST complète pour la gestion centralisée de lieux et commerces dans différentes villes.**

Cette API permet de créer, consulter, modifier et supprimer des lieux d'intérêt (magasins, cafés, restaurants, monuments, etc.) avec système de notation et de filtrage avancé.

## Fonctionnalités Principales
- ✅ **CRUD Complet** - Création, lecture, modification, suppression de lieux
- ✅ **Filtrage Multi-critères** - Par catégorie, ville, note minimum
- ✅ **Tri Flexible** - Par nom, note, date de création
- ✅ **Validation des Données** - Contrôles de cohérence automatiques
- ✅ **Architecture REST** - Endpoints standards et réponses JSON

## Installation
1. Importer `importsql.sql` dans phpMyAdmin
2. Configurer `app/Config/database.ini`
3. Accéder via le point d'entrée principal

## Endpoints
- `GET /api/places` - Liste des lieux (filtres: category, city, rating)
- `POST /api/places` - Créer un lieu
- `GET /api/places/{id}` - Obtenir un lieu
- `PUT /api/places/{id}` - Modifier un lieu  
- `DELETE /api/places/{id}` - Supprimer un lieu

## Documentation complète

Le fichier de documentation `documentation.html` se trouve à la **racine du projet** (pas dans le dossier public).

### Accès à la documentation :

**Si vous avez accès aux fichiers :**
- Ouvrez directement `documentation.html` dans votre navigateur

**Sur un serveur web :**

## 🏢 Use Cases réels

Cette API pourrait alimenter :

**Applications de recommandation** :
- Guides urbains (Type TripAdvisor, Yelp)
- Cartes interactives (Google Maps Business)