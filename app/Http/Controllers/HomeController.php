<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeadLines;
use App\Models\HeadLineImages;
use Storage;

class HomeController extends Controller
{
    /**
     * [index home page with pagination]
     * @param  Request $request
     * @return [view]           [return htm view to the user]
     */
    public function index(Request $request)
    {
    	try {
    		$headlines = HeadLines::paginate(5);
    		return view("headline",compact('headlines'));
    	} catch (Exception $e) {
    	}
    }

    /**
     * [storyDownload download story in csv or json]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storyDownload(Request $request)
    {
    	try {
    		$headlines = HeadLines::with('images')->get();
    		$download_type = $request->input('type');
    		if($download_type == 'json'){
    			$headers = ['Content-type'=>'text/json'];
    			Storage::disk('local')->put('/public/top_headline_story.json', $headlines->toJson(JSON_PRETTY_PRINT));
    			return response()->download(storage_path('app/public/top_headline_story.json'),'top_headline_story.json',$headers)->deleteFileAfterSend(true);
    		}
    		elseif($download_type == 'csv'){
    			$headlines = HeadLines::all();
    			$headers = array(
			        "Content-type" => "text/csv",
			    );
			    $columns = array_keys($headlines[0]->getAttributes());
    			$headlines = $headlines->toArray();
    			$file = fopen(storage_path('app/public/top_headline_story.csv'), 'w');
    			fputcsv($file, $columns);
    			foreach($headlines as $headline) {
		            fputcsv($file, $headline);
		        }
		        fclose($file);
    			return response()->download(storage_path('app/public/top_headline_story.csv'))->deleteFileAfterSend(true);
    		}
    		else
    			return response()->json([
				    'message' => 'invalid File type'
				]);
    	} catch (Exception $e) {
    	}
    }
}
