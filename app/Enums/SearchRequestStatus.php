<?php

namespace App\Enums;

enum SearchRequestStatus: string
{
    case Pending = 'pending';
    case Interpreting = 'interpreting';
    case FetchingSuggestions = 'fetch_suggestions';
    case FetchingAccommodations = 'fetching_accommodations';
    case Scoring = 'scoring';
    case Complete = 'complete';
    case Failed = 'failed';
}
