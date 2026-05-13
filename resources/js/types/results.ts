export type SearchResult = {
    id: number;
    status: string;
    resultState: Array<AccommidationResult>;
};

export type AccommidationResult = {
    id: number;
    score: AccommidationScore;
}

export type AccommidationScore = {
    id: number;
    romance: number; 
    adventure: number;
    budget: number;
}