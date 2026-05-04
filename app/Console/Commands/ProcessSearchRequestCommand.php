<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\SearchRequest;
use App\Actions\Tasks\ExtractIntentTask;

#[Signature('search:process {prompt}')]
#[Description('Send a prompt to the LLM')]
class ProcessSearchRequestCommand extends Command
{
    public function handle(): int
    {
          $prompt = $this->argument('prompt');

        $searchRequest = SearchRequest::create([
            'prompt' => $prompt,
            'status' => 'pending',
        ]);

         $this->info("Search for Request {$searchRequest->id}");

        $intent = app(ExtractIntentTask::class)->handle($searchRequest);

        $this->info("Intent From LLM");
        foreach ($intent as $key => $value) {
            if(gettype($value) != "array"){
                $this->info("{$key}: {$value}");
            } else {
                $this->info("{$key}");
                foreach($value as $arrKey => $arrValue) {
                    $this->info("{$arrKey}: {$arrValue}");
                }
            }
     
        }

        return self::SUCCESS;
    }
}
