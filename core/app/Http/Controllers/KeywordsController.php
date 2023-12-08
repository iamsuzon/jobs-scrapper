<?php

namespace App\Http\Controllers;

use App\Models\Keywords;
use Illuminate\Http\Request;

class KeywordsController extends Controller
{
    public function keywords()
    {
        $keywords = Keywords::orderByDesc('id')->paginate(20);
        return view('keywords.index', compact('keywords'));
    }

    public function keywordsUpdate(Request $request)
    {
        $request->validate([
            'keyword_group_name' => 'required|unique:keywords,identifier',
            'keywords' => 'required|array',
        ]);

        foreach ($request->keywords as $key => $keyword)
        {
            if (empty($keyword))
            {
                $index = ++$key;
                $number = (new \NumberFormatter("en", \NumberFormatter::ORDINAL))->format($index);
                return back()->with('error', "{$number} Keyword Field is empty!");
            }
        }

        $name = $request->keyword_group_name;

        $keywords = array_map(function ($keyword){
            return trim(strtolower($keyword));
        } ,$request->keywords);

        try {
            Keywords::create([
                'identifier' => $name,
                'keywords' => json_encode($keywords)
            ]);

            return back()->with('success', 'Keyword group has been created successfully!');
        } catch (\Exception $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function keywordsEdit($id)
    {
        $finalId = $this->extractId($id);
        abort_if(!Keywords::find($finalId), 404);

        $keyword = Keywords::find($finalId);
        return view('keywords.edit', compact('keyword'));
    }

    public function keywordsDelete($id)
    {
        $finalId = $this->extractId($id);
        abort_if(!Keywords::find($finalId), 404);

        try {
            Keywords::find($finalId)->delete();
            return back()->with('success', 'Keyword group has been deleted successfully!');
        } catch (\Exception $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    private function extractId($id)
    {
        abort_if(empty($id), 404);
        return (int) substr($id, 0, 1);
    }

    public function keywordsStatus($id)
    {
        $id = $this->extractId($id);
        abort_if(!Keywords::find($id), 404);

        try {
            Keywords::where('status', true)->update(['status' => false]);

            $keyword = Keywords::find($id);
            $keyword->status = !$keyword->status;
            $keyword->save();

            return back()->with('success', 'Keyword group status has been updated successfully!');
        } catch (\Exception $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }
}
