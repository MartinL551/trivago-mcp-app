# Trivago MCP App

A Laravel + Vue travel search application that uses natural language input, the trivago MCP API, OpenAI structured outputs, and custom accommodation scoring to return a short ranked list of hotel recommendations.

The goal of the project is to explore how an AI-assisted travel search experience can be built around real external APIs, asynchronous processing, user preferences, and saved search results.

## Features

- Natural language travel search
- OpenAI-powered intent extraction
- trivago MCP accommodation search
- AI-assisted accommodation scoring
- Saved / previous search results
- Wishlist support
- User-selectable preferred currency
- Location geocoding for distance calculations
- Async search pipeline using Laravel jobs
- Authenticated user flow
- Vue + Inertia frontend

## Tech Stack

### Backend

- Laravel
- PHP
- SQLite
- Laravel Queues
- Laravel Socialite

### Frontend

- Vue 3
- TypeScript
- Inertia.js
- Tailwind CSS

### External Services

- OpenAI API
- trivago MCP API
- OpenStreetMap Nominatim Geocoding

## How It Works

```text
User prompt
    ↓
OpenAI extracts structured travel intent
    ↓
SearchRequest is created
    ↓
Location is geocoded and stored
    ↓
trivago MCP accommodation search is called
    ↓
Top accommodations are stored for the search
    ↓
Results are scored against the user's intent
    ↓
User can view, revisit, and wishlist results
```

## Architecture

The application follows a service-based architecture that keeps external integrations isolated from business logic.

### Core Domain Models

#### SearchRequest

Stores:

- Original user prompt
- Extracted travel intent
- Currency
- Language
- Destination
- Processing status
- Search metadata

#### Accommodation

Stores:

- Accommodation data returned by trivago
- Pricing information
- Ratings
- Amenities
- Location data
- Search-specific result information

#### AccommodationScore

Stores:

- AI-generated category scores
- Recommendation explanations
- Scoring metadata

#### Location

Stores cached geocoding results:

- Query
- Latitude
- Longitude

This reduces external geocoding requests and improves performance.

### Services

The application separates external integrations into dedicated services:

- OpenAI Service
- Trivago MCP Service
- Geocoding Service
- Accommodation Scoring Service

This allows third-party APIs to change without impacting the rest of the application.

## Current Status

The core product flow is feature complete.

### Completed

- End-to-end search flow
- AI intent extraction
- Accommodation retrieval
- AI scoring system
- Search history
- Wishlist functionality
- Currency preferences
- Geocoding integration
- Async processing pipeline

### Planned Improvements

- Daily search limits
- Additional automated tests
- UI polish and responsive improvements
- Production deployment
- Enhanced user settings

## Local Development

### Clone Repository

```bash
git clone https://github.com/MartinL551/trivago-mcp-app.git
cd trivago-mcp-app
```

### Install Dependencies

Backend:

```bash
composer install
```

Frontend:

```bash
npm install
```

### Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure required environment variables:

```env
OPENAI_API_KEY=
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

### Database

```bash
php artisan migrate
```

### Start Application

Laravel:

```bash
php artisan serve
```

Frontend:

```bash
npm run dev
```

Queue Worker:

```bash
php artisan queue:work
```

## Testing

Run the test suite:

```bash
php artisan test
```

## Why This Project Exists

This project was built to demonstrate practical full-stack software engineering skills through a realistic product rather than a simple CRUD application.

Areas demonstrated include:

- Laravel application architecture
- Vue + Inertia development
- AI structured outputs
- External API integration
- Asynchronous workflows
- Service-oriented design
- Authentication and user management
- Data modelling
- Handling evolving third-party APIs
- Converting natural language into structured search criteria

## Screenshots

Screenshots and UI walkthroughs will be added as the application styling reaches completion.

## License

This project is intended as a portfolio and learning project.