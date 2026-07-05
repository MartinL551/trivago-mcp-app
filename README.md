# Trivago MCP App

An AI-powered accommodation search demo built with Laravel, Vue, Inertia.js, OpenAI, and the Trivago MCP API.

Users can describe the kind of trip they want in natural language. The application extracts structured travel intent from the prompt, searches accommodation data through Trivago MCP, scores the results against the user's preferences, and presents a ranked list of hotels.

This project is a portfolio and learning project. It is not affiliated with Trivago.

---

## Features

### Natural language accommodation search

Users can search with prompts such as:

```text
I want a luxury hotel in London for a romantic weekend.
Find a family-friendly hotel in Berlin.
Show me a business hotel with good WiFi near Cologne.
```

The prompt is interpreted into structured travel data, including:

- Destination
- Arrival and departure dates
- Guest count
- Rooms
- Children and child ages
- Preferred amenities
- Budget preference
- Trip type / travel intent

### AI-assisted result ranking

Accommodation results are scored against travel intent signals such as:

- Luxury
- Romantic
- Family
- Business
- Adventure
- Budget

The scoring process combines structured accommodation data with AI-generated reasoning so that results can be ranked and explained to the user.

### Asynchronous search processing

Searches are processed with Laravel queue jobs rather than blocking the request/response cycle.

The frontend creates a search request, redirects the user to the results page, and polls for updates while the backend works through the search pipeline.

### Google authentication

Users can sign in with Google using Laravel Socialite.

### Wishlist

Authenticated users can save accommodation results to a personal wishlist.

### Search history

Search requests are stored so users can revisit previous searches and view their results.

---

## Tech Stack

### Backend

- PHP 8.3+
- Laravel 13
- Laravel Queues
- Laravel Fortify
- Laravel Socialite
- OpenAI PHP client
- Trivago MCP API
- SQLite for local development
- Database-backed sessions, cache, and queues

### Frontend

- Vue 3
- TypeScript
- Inertia.js
- Vite
- Tailwind CSS
- Ziggy
- Wayfinder

### Tooling

- Laravel Pint
- ESLint
- Prettier
- Vue TypeScript checking
- PHPUnit
- GitHub Actions for linting and tests

---

## Application Flow

```text
User prompt
    ↓
SearchRequest is created
    ↓
Queued job extracts structured travel intent with OpenAI
    ↓
Accommodation search is requested from Trivago MCP
    ↓
Results are stored against the SearchRequest
    ↓
Queued scoring process ranks accommodations
    ↓
Frontend polls until results are complete
    ↓
User can view results and save wishlist items
```

Search requests move through statuses such as:

- Pending
- Interpreting
- Scoring
- Complete
- Failed

---

## Local Development

### Requirements

- PHP 8.3+
- Composer
- Node.js
- npm
- SQLite
- OpenAI API key
- Google OAuth credentials
- Access to the Trivago MCP API

### Installation

Clone the repository:

```bash
git clone https://github.com/MartinL551/trivago-mcp-app.git
cd trivago-mcp-app
```

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Create the local SQLite database if it does not already exist:

```bash
touch database/database.sqlite
```

Run migrations:

```bash
php artisan migrate
```

Start the development environment:

```bash
composer dev
```

This runs the Laravel server, queue listener, and Vite dev server together.

Alternatively, run them separately:

```bash
php artisan serve
php artisan queue:work
npm run dev
```

---

## Environment Variables

The application expects the usual Laravel environment variables, plus values for OpenAI and Google authentication.

Important variables include:

```env
APP_NAME=TrivagoMcp
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
SESSION_DOMAIN=null

OPENAI_API_KEY=
OPENAI_MODEL=gpt-4.1-mini

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

ACCOMMODATION_RESULT_LIMIT=5
```

For local development, the default SQLite setup is enough.

For hosted environments, a server database such as MySQL or PostgreSQL is recommended.

---

## Google OAuth Setup

Create OAuth credentials in Google Cloud Console and set the callback URL to match your environment.

Local callback:

```text
http://localhost:8000/auth/google/callback
```

Production callback example:

```text
https://your-domain.com/auth/google/callback
```

Update the following values in `.env`:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

---

## Queues

This project relies on queues for search processing.

For local development:

```bash
php artisan queue:work
```

For production, the queue worker should be managed by a process monitor such as Supervisor:

```bash
php artisan queue:work database --sleep=3 --tries=3 --timeout=120
```

The queue worker must be running for search requests to complete.

---

## Production Notes

A simple VPS deployment can run the full application with:

- Nginx
- PHP-FPM
- Composer
- Node build output
- MySQL or PostgreSQL
- Supervisor for queue workers
- Laravel scheduler cron
- HTTPS via Let's Encrypt

Typical production setup steps:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

The web server should point to:

```text
/public
```

The following directories must be writable by the web server user:

```text
storage
bootstrap/cache
```

---

## Scheduled Tasks

If scheduled pruning, cleanup, or maintenance tasks are enabled, production should include the Laravel scheduler cron entry:

```cron
* * * * * cd /path/to/trivago-mcp-app && php artisan schedule:run >> /dev/null 2>&1
```

---

## Code Quality

Run the backend test/check pipeline:

```bash
composer test
```

Run the full CI-style check:

```bash
composer ci:check
```

Run frontend checks manually:

```bash
npm run lint:check
npm run format:check
npm run types:check
```

Format PHP code with Pint:

```bash
composer lint
```

---

## Current Status

This is the first host-ready version of the project.

The core application flow is in place:

- User authentication
- Natural language search
- OpenAI intent extraction
- Trivago MCP accommodation fetching
- Queued background processing
- Result scoring
- Search result polling
- Wishlist support
- Search history

Areas still worth improving:

- More robust pruning of old search data
- Better production error monitoring
- Stronger fallback behaviour when external APIs fail
- More complete automated test coverage
- Deployment automation with GitHub Actions
- Optional Docker/Terraform setup later

---

## Disclaimer

This is an independent learning and portfolio project.

It is not affiliated with, endorsed by, or sponsored by Trivago.
