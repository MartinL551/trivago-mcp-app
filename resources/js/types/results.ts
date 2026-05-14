export type SearchResult = {
    id: number;
    status: string;
    prompt: string;
};

export type AccommidationResults = {
    accommodations: Array<AccommidationResult>;
}

export type AccommidationResult = {
    id: number;
    name: string;
    postcode: string;
    address: string;
    currency: string;
    price_per_stay:  number;
    price_per_day: number;
    city: string;
    review_rating: string;
    review_count: number;
    amenites: string;
    trivago_url: string;
    trivago_image_url: string;
    distance_string: string;
    distance_to_center: number;
    distance_units: string;
    desc: string;
    created_at: string;
    updated_at: string;
}

export type AccommidationScore = {
    id: number;
    romance: number; 
    adventure: number;
    budget: number;
}