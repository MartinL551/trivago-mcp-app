<?php

namespace App\Concerns;

trait SearchValidationRules
{
    /**
     * Get the validation rules used to validate search requests.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function searchRules(?int $userId = null): array
    {
        return [
            'prompt' => $this->promptRules(),
        ];
    }

    /**
     * Get the validation rules used to validate search prompts.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function promptRules(): array
    {
        return ['required', 'string', 'min:5', 'max:200'];
    }
}
