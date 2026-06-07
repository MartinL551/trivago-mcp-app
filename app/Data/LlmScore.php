<?php

namespace App\Data;

class LlmScore {
    public string $trivagoId;
    public int $romance;
    public int $adventure;
    public int $luxury;
    public int $family;
    public int $business;    
    public int $budget;
    public string $status;
    public string $why;

    /**
     * @param array $llmResponse Raw structured output from LLM
     */
    public function __construct(array $llmResponse)
    {
        $this->trivagoId = $llmResponse['trivago_id'] ?? '';
        $this->romance = $llmResponse['romance'] ?? 0;
        $this->adventure = $llmResponse['adventure'] ?? 0;
        $this->budget = $llmResponse['budget'] ?? 0;
        $this->luxury =  $llmResponse['luxury'] ?? 0;
        $this->business = $llmResponse['business'] ?? 0;
        $this->family = $llmResponse['family'] ?? 0;
        $this->why = $llmResponse['why'] ?? '';
        $this->status = count($llmResponse) === 0 ? "failed" : "success";
    }
}