<?php

namespace App\Data;

class LlmScore {
    public int $romance;
    public int $adventure;
    public int $budget;

    /**
     * @param array $llmResponse Raw structured output from LLM
     */
    public function __construct(array $llmResponse)
    {
        $this->romance = $llmResponse['romance'] ?? 0;
        $this->adventure = $llmResponse['adventure'] ?? 0;
        $this->budget = $llmResponse['budget'] ?? 0;
    }
}