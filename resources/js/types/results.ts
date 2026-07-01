import type { CurrencyCode } from './currency';

export enum SearchRequestStatus {
    Pending = 'pending',
    Interpreting = 'interpreting',
    FetchingAccommodations = 'fetching_accommodations',
    Scoring = 'scoring',
    Complete = 'complete',
    Failed = 'failed',
}

export type SearchResult = {
    id: number;
    user_id?: number;
    status: SearchRequestStatus;
    prompt: string;
    main_signal?: string | null;
    secondary_signal?: string | null;
    city?: string | null;
    country?: string | null;
    landmark?: string | null;
    latitude?: number | string | null;
    longitude?: number | string | null;
    langauge?: string | null;
    currency?: CurrencyCode | null;
    created_at?: string;
    updated_at?: string;
};

export type SearchRequests = SearchResult[];

export type AccommodationResults = AccommodationResult[];
export type AccommidationResults = AccommodationResults;

export type AccommodationResult = {
    id: number;

    trivago_id: string;
    search_request_id: number;
    accommodationscore_id: number | null;
    name: string;

    currency: CurrencyCode;

    price_per_stay: number;
    price_per_night: number;

    hotel_rating: number;

    city: string;

    review_rating: number;
    review_count: number;

    amenites: string;

    trivago_url: string;
    trivago_image_url: string;

    distance_string: string | null;

    latitude: number | null;
    longitude: number | null;

    arrival: string | null;
    departure: string | null;

    advertiser: string | null;

    created_at: string;
    updated_at: string;

    scores?: AccommodationScore | null;

    wishlisted: boolean;
};

export type AccommodationScore = {
    id: number;
    accommodation_id: number;
    search_request_id: number;
    trivago_id: string;
    romance: number; 
    adventure: number;
    budget: number;
    luxury: number;
    business: number;
    family: number;
    why: string;
    created_at: string;
    updated_at: string;
};

export type AccommidationResult = AccommodationResult;
export type AccommidationScore = AccommodationScore;
