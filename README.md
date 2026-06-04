# Trivago MCP App

An AI-powered hotel search application built with Laravel, Vue, Inertia.js, OpenAI, and the Trivago MCP API.

Users can search for accommodation using natural language prompts. The application extracts travel intent from the prompt, retrieves accommodation data from Trivago MCP, and ranks results based on the user's preferences.

> 🚧 Work in Progress

## Features

### Natural Language Search

Search using prompts such as:

- "I want a luxury hotel in London for a romantic weekend."
- "Find a family-friendly hotel in Berlin."
- "Show me a business hotel with good wifi near Cologne."

The application uses OpenAI structured outputs to extract:

- Destination
- Dates
- Number of guests
- Amenities
- Travel intent

### AI-Powered Ranking

Accommodation results are ranked using travel signals such as:

- Luxury
- Romantic
- Family
- Business
- Adventure
- Budget

The primary signal is weighted more heavily than the secondary signal when ranking results.

### Google Authentication

Users can sign in using Google via Laravel Socialite.

### Wishlists

Users can save accommodations to a personal wishlist.

### Search History

Search requests are stored and processed asynchronously, allowing users to revisit previous searches and view results.

---

## Tech Stack

### Backend

- PHP 8+
- Laravel
- Laravel Queues
- Laravel Socialite
- OpenAI API
- Trivago MCP API
- SQLite (Development)

### Frontend

- Vue 3
- TypeScript
- Inertia.js
- Tailwind CSS

### Infrastructure

Current:

- Local development environment
- Queue workers
- SQLite

Planned:

- AWS EC2
- Nginx
- PHP-FPM
- GitHub Actions
- Terraform

---

## Architecture

### Search Flow

1. User submits a natural language search.
2. A `SearchRequest` record is created.
3. A queued job extracts structured search data using OpenAI.
4. Destination suggestions are retrieved from Trivago MCP.
5. Accommodation results are fetched.
6. Results are scored using travel intent signals.
7. Ranked results are returned to the user.

### Search Request Statuses

- Pending
- Scoring
- Complete
- Failed

The frontend polls for updates while processing is running in the background.

---

## Example

### User Prompt

```text
I want a luxury hotel in London for a romantic holiday for two people.
```

### Extracted Intent

```json
{
    "main_signal": "luxury",
    "secondary_signal": "romantic",
    "destination": "London"
}
```

### Ranked Results

Results are ranked using a weighted scoring system based on the extracted signals.

---

## Local Development

### Requirements

- PHP 8+
- Composer
- Node.js
- npm

### Installation

```bash
git clone https://github.com/MartinL551/trivago-mcp-app.git

cd trivago-mcp-app

composer install

npm install

cp .env.example .env

php artisan key:generate

php artisan migrate
```

### Start Development Environment

```bash
npm run dev
```

```bash
php artisan serve
```

```bash
php artisan queue:work
```

---

## Current Development Goals

- Improve accommodation scoring
- Improve intent extraction
- Add profile page
- Complete wishlist functionality
- Add search history UI
- Improve error handling
- Add rate limiting
- Add bot protection

---

## Learning Goals

This project was built to explore:

- AI-assisted search workflows
- OpenAI structured outputs
- MCP integrations
- Queue-based application architecture
- Laravel + Vue + Inertia development
- Modern deployment workflows

---

## Disclaimer

This project is an independent learning project and is not affiliated with Trivago.