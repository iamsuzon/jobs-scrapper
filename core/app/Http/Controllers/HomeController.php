<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapingJob;
use App\Models\Keywords;
use App\Models\SearchJobList;
use Goutte\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public const PAGESIZE = 10;
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
        $crawled = SearchJobList::latest('id')->select('created_at')->first();
        $searching = \DB::table('jobs')->count();
        $searching = $searching > 0 ? $searching * self::PAGESIZE : 0;

        return view('dashboard', compact('crawled','searching'));
    }

    public function allJobs()
    {
        $jobs = SearchJobList::paginate(20);
        return view('job-list', compact('jobs'));
    }

    public function allJobsType($type)
    {
        $type = trim(strtolower($type));
        $jobs = SearchJobList::where('status', $type)->paginate(20);
        return view('job-list', compact('jobs', 'type'));
    }

    public function scrapIt()
    {
        $scrapValidation = $this->validateScrapAction();
        if ($scrapValidation)
        {
            return back();
        }

        $keywords = Keywords::where('status', 1)->first();
        $pages = $this->calculatePageAmounts();

        for ($page = 1; $page <= $pages; $page++) {
            $url = $this->getPageUrl($page);
            ScrapingJob::dispatch($url, $page, json_decode($keywords->keywords))->delay(now()->addSeconds(2));
        }

        return back()->with('success', 'Scraping job has been dispatched successfully!');
    }

    private function validateScrapAction()
    {
        $last_scrap_job = \DB::table('jobs')->latest('id')->exists();
        if ($last_scrap_job && !session()->has('last_scrap_job_time'))
        {
            session()->put('last_scrap_job_time', now()->addMinutes(5));
        }

        if (session()->has('last_scrap_job_time'))
        {
            $last_scrap_job_time = session()->get('last_scrap_job_time');
            if ($last_scrap_job_time > now())
            {
                $time = $last_scrap_job_time->diff(now())->format('%i minutes and %s seconds');
                back()->with('error', "You can't scrap jobs before {$time}!");
                return true;
            }
        }

        return false;
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
        $page_size = self::PAGESIZE;
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

        return (int) ceil((int) $postAmount / self::PAGESIZE);
    }

    public function searchJob(Request $request)
    {
        $search_text = $request->data;
        $search_text = trim($search_text);

        $jobs = SearchJobList::where('status', '!=', 'hidden')
                ->where('ref', 'LIKE', "%{$search_text}%")
                ->orWhere('title', 'LIKE', "%{$search_text}%")
                ->get();

        return response()->json([
            'status' => 'success',
            'jobs' => $jobs,
            'total' => $jobs->count()
        ]);
    }

    public function statusChangeJob(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);

        $id = $request->id;
        $status = $request->status;

        $message = 'Job status has been changed successfully!';
        if (is_array($id))
        {
            SearchJobList::whereIn('id', $id)->update([
                'status' => $status
            ]);

            $message = 'All Selected Jobs status has been changed successfully!';
        } else {
            $job = SearchJobList::find($id);
            $job->status = $status;
            $job->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function searchingLeft()
    {
        $searching = \DB::table('jobs')->count();
        return response()->json([
            'status' => 'success',
            'searching' => $searching > 0 ? $searching * self::PAGESIZE : 0
        ]);
    }

    public function settings()
    {
        return view('settings');
    }

    public function updateDatabase()
    {
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
        \Artisan::call('optimize:clear');

        return back()->with('success', 'Database has been updated successfully!');
    }

    public function clearCache()
    {
        \Artisan::call('optimize:clear');
        return back()->with('success', 'Cache has been cleared successfully!');
    }
}
