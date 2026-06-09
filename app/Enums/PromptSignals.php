<?php

namespace App\Enums;

enum PromptSignals: string
{
    case Budget = 'budget';
    case Family = 'family';
    case Adventure = 'adventure';
    case Romantic = 'romantic';
    case Business = 'business';
    case Luxury = 'luxury';
}
