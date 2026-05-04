<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use App\Enums\SearchRequestStatus;

#[Unguarded]
class SearchRequest extends Model
{
    //unguarded for now. Need to loop back later

    public function setStatus(SearchRequestStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }
}
