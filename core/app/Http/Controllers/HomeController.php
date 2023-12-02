<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapingJob;
use Goutte\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function scrapIt()
    {
        $pages = $this->calculatePageAmounts();

        for ($page = 1; $page <= 10; $page++) {
//            $url = ;
            ScrapingJob::dispatch($url)->delay(now()->addSeconds(2));
        }
    }

    private function checkPostAmount()
    {
        $url = "https://jobsireland.ie/en-US/browse-jobs?page=1&pageSize=10&VacancyTypeId=0";

        $client = new Client();

        $crawler = $client->request('GET', $url);

        $content = $crawler->filter('.showing-entry p')->text();
        $content = explode(' ', $content);

        return $content[5] ?? 0;
    }

    private function calculatePageAmounts()
    {
        $postAmount = $this->checkPostAmount();

        return (int) ceil((int) $postAmount / 10);
    }
}
