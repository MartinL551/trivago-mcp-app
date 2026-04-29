<?php

namespace App\Enums;

enum SearchRequestStatus: string
{
    case Pending = 'pending';
    case Interpreting = 'interpreting';
    case Fetching = 'fetching';
    case Scoring = 'scoring';
    case Complete = 'complete';
    case Failed = 'failed';
}
