<?php

namespace App\Http\Controllers;

use App\Article;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;


class ArticleController extends Controller
{
    protected $articleModel;

    public function __construct(Article $article)
    {
        $this->articleModel = $article;
    }

    public function index()
    {
        return view('welcome');
    }

    public function suggest(Request $request)
    {
        $client = new Client();
        $text = $request->post('text');
        $data = [
            "suggest" => [
                "title" => [
                    "prefix" => $text,
                    "completion" => [
                        "field" => "suggest",
                        "size"=>10
                    ]
                ]
            ]
        ];
        $response = $client->request('GET', 'localhost:9200/article/_doc/_search', ['json' => $data]);
        $result = json_decode($response->getBody()->getContents());
        $suggestions = [];
        foreach ($result->suggest->title[0]->options as $option){
            array_push($suggestions,$option->text);
        }
        return response()->json($suggestions);
    }

    public function search(Request $request)
    {
        $text = $request->get('text');
        $articles = Article::searchByQuery(array("multi_match"=>array("query"=>$text,"fields"=>array("title","text"))),null,null,4613,null,null);
        $total = $articles->totalHits()["value"];
        $articles = $articles->paginate(20);
        $tokens = explode(" ", $text);

        foreach($articles as $article)
            foreach ($tokens as $token)
                $article->text = str_replace(' ' . $token . ' ', '<b class="text-warning"> ' . $token . ' </b>', $article->text);

        return view('welcome',compact('articles','total','text'));
    }
}
