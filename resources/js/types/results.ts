export type SearchResult = {
    id: number;
    status: string;
    prompt: string;
};

export type AccommidationResults = {
    accommodations: Array<AccommodationResult>;
}

export type AccommodationResult = {
    id: number;

    trivago_id: string;
    name: string;

    currency: string;

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

    score: Array<AccommidationScore>;

    wishlisted: boolean;
};

export type AccommidationScore = {
    id: number;
    romance: number; 
    adventure: number;
    budget: number;
    why: string;
}