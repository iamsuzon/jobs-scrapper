<?php

namespace App\Jobs;

use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public string $url;
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keyWords = ['fitter', 'assembler', 'worker'];

        $this->setTimeMemoryLimit();

        if ($this->url) {
            $client = new Client();

            $crawler = $client->request('GET', $this->url);

            $nodeArr = [];
            $crawler->filter('.job-list .job-heading')->each(function ($node) use (&$nodeArr) {
                $nodeArr[] = $node;
            });

            $results = [];
            foreach ($nodeArr ?? [] as $index => $node) {
                $results[$index]['title'] = $node->filter('.job-detail-main-box .title-box .job-title-box')->text();
                $results[$index]['ref'] = $node->filter('.job-detail-main-box .d-flex .flex-item:first-child ul li:first-child span')->text();
            }

            $finalResults = [];
            foreach ($results as $item) {
                // check if each keyword contains in the title
                foreach ($keyWords as $keyWord) {
                    if (strpos(strtolower($item['title']), $keyWord) !== false) {
                        $finalResults[] = $item;
                    }
                }
            }
        }

        \Log::info('ScrapingJob: ' . json_encode($finalResults));
    }

    private function setTimeMemoryLimit(): void
    {
        // Increase memory limit to 512MB
        ini_set('memory_limit', '512M');

        // Increase max execution time to 300 seconds (10 minutes)
        ini_set('max_execution_time', 600);
    }
}
