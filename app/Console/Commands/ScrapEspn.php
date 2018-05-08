<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Storage;
use App\Models\HeadLines;
use App\Models\HeadLineImages;
use App\Models\CronStatus;
use Symfony\Component\DomCrawler\Crawler;

class ScrapEspn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap:espn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scrap top headlines from espncricinfo.com';

    /**
     * base uri for espn crickinfo
     */

    private $base_uri;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->base_uri = "http://www.espncricinfo.com";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try {
            $client = new Client();
            $espn_response = $client->request('GET', $this->base_uri);
            $contents = $espn_response->getBody()->getContents();
            $headline_crawler = new Crawler($contents);
            $hedaline = $headline_crawler->filter('.col-three > div.headlineStack > section.headlineStack__listContainer > ul.headlineStack__list > li')->each(function (Crawler $node, $i) {
                $story = array();
                $story['headline'] = $node->text();
                $story['story_id'] = $node->attr('data-story-id');
                $story['story_link'] = $node->filter('a')->attr('href');
                /**
                 * read individual story
                 */
                $client = new Client();
                $story_url = '';
                // check story link has whole url or partial url
                if (strpos($story['story_link'], $this->base_uri) !== false)
                    $story_url = $story['story_link'];
                else
                    $story_url = $this->base_uri.$story['story_link'];
                $single_story = $client->request('GET',$story_url)->getBody()->getContents();
                $story_crawler = new Crawler($single_story);
                $story_content = $story_crawler->filter('div.article-body > p')->each(function (Crawler $story_node, $story_index){
                    return $story_node->text();
                });

                /**
                 * get all images from individual story
                 */
                $images_src = $story_crawler->filter('source')->each(function (Crawler $image_node, $Image_index){
                    return $image_node->attr('srcset');
                });
                $image_paths = array();
                // download and save the image to the folder
                foreach ($images_src as $image_src) {
                    $query = parse_url($image_src, PHP_URL_QUERY);
                    parse_str($query, $params);
                    if(array_key_exists("img",$params))
                    {
                        $image_name = "/public".$params['img'];
                        $contents = @file_get_contents(urldecode($image_src), true);
                        if($contents)
                        {
                            $path = array();
                            Storage::disk('local')->put($image_name, $contents,'public');
                            if(!in_array($params['img'], array_column($image_paths, 'image_path')))
                            {
                                $path['image_path'] = $params['img'];
                                array_push($image_paths,$path);
                            }
                        }
                    }
                }
                $story['story_content'] = implode($story_content);
                unset($story['story_link']);
                $head_line = HeadLines::where('story_id',$story['story_id'])->first();
                if(!$head_line)
                    $head_line = HeadLines::create($story);
                else
                {
                    $head_line->headline = $story['headline'];
                    $head_line->story_content = $story['story_content'];
                    $head_line->save();
                }
                $images = HeadLineImages::where('headline_id',$head_line->id)->delete();
                //$images->delete();
                $head_line->images()->createMany($image_paths);
                return $story;
            });
        } catch (Exception $e) {
            print_r($e);
        }

    }
}