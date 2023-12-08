<?php

namespace App\Console\Commands;

use App\Jobs\ScrapingJob;
use App\Models\Keywords;
use Goutte\Client;
use Illuminate\Console\Command;

class AutoSearchJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $keywords = Keywords::where('status', 1)->first();
        $pages = $this->calculatePageAmounts();

        for ($page = 1; $page <= $pages; $page++) {
            $url = $this->getPageUrl($page);
            ScrapingJob::dispatch($url, $page, json_decode($keywords->keywords))->delay(now()->addSeconds(2));
        }
    }

    private function getPageUrl($page)
    {
        return str_replace('{$page}', $page, ENV('JOB_SCRAPER_URL'));
    }

    private function getPageSizeUrl($page_size)
    {
        return str_replace('{$page_size}', $page_size, ENV('JOB_SCRAPER_SIZE_URL'));
    }

    private function checkPostAmount()
    {
        $page_size = pageSize();
        $url = $this->getPageSizeUrl($page_size);

        $client = new Client();

        $crawler = $client->request('GET', $url);

        $content = $crawler->filter('.showing-entry p')->text();
        $content = explode(' ', $content);

        return $content[5] ?? 0;
    }

    private function calculatePageAmounts()
    {
        $postAmount = $this->checkPostAmount();

        return (int) ceil((int) $postAmount / pageSize());
    }
}
