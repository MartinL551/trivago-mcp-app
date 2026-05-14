export type SearchResult = {
    id: number;
    status: string;
    promt: string;
    accommodations: Array<AccommidationResult>
};

export type AccommidationResult = {
    id: number;
}

export type AccommidationScore = {
    id: number;
    romance: number; 
    adventure: number;
    budget: number;
}