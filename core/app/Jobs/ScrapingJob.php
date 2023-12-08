<?php

namespace App\Jobs;

use App\Models\SearchJobList;
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
    public int $page_number;
    public array $keyWords;
    public function __construct($url, $page_number, $keyWords)
    {
        $this->url = $url;
        $this->page_number = $page_number;
        $this->keyWords = $keyWords;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keyWords = $this->keyWords;

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

            if (count($finalResults) > 0) {
                $this->saveToDatabase($finalResults);
            }
        }
    }

    private function saveToDatabase($finalResults): void
    {
        foreach ($finalResults as $item)
        {
            $item['status'] = 'unapplied';
            $item['page'] = $this->page_number;
            $item['content'] = json_encode($item);

            SearchJobList::firstOrCreate(
                [
                    "ref" => $item['ref']
                ],
                [
                    "ref" => $item['ref'],
                    "title" => $item['title'],
                    "content" => $item['content'],
                    "status" => $item['status'],
                ]
            );
        }
    }

    private function setTimeMemoryLimit(): void
    {
        // Increase memory limit to 512MB
        ini_set('memory_limit', '512M');

        // Increase max execution time to 1200 seconds (20 minutes)
        ini_set('max_execution_time', 1200);
    }
}
