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

- Laravel
- Vue 3
- TypeScript
- Inertia.js
- Tailwind CSS
- OpenAI API
- trivago MCP API
- OpenStreetMap Nominatim geocoding
- SQLite / Laravel database layer

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