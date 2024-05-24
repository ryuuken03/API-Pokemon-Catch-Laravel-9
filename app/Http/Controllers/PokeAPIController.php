<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokeAPIController extends Controller
{
    public function all(Request $request)
    {

        $page = $request->page;
        $offset = 0; 
        $limit = $request->limit;
        if(empty($limit)){
           $limit = 20; 
        }else{
            if($limit < 0){
                $limit = 20; 
            }
        }
        if(!empty($page)){
            if($offset < 0){
                $offset = 0; 
            }else{
                $offset = ($page - 1) * $limit;
            } 
        }
        $url = 'https://pokeapi.co/api/v2/pokemon?offset='.$offset."&limit=".$limit;
        $response = Http::get($url);
        $result = $response['results'];
        for($i=0;$i<count($result);$i++){
            $split = explode('/pokemon/',$result[$i]['url']) ;
            $result[$i]['id'] = str_replace('/','',$split[1]);
            $image = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/";
            $result[$i]['image'] =  $image.$result[$i]['id'].".png";
            // unset($result[$i]['url']);
        }
        $next_page = $page;
        if(empty($response['next'])){
            $next_page = NULL;
        }else{
            $next_page = $page+1;
        }
        $previous_page = $page;
        if(empty($response['previous'])){
            $previous_page = NULL;
        }else{
            $previous_page = $page-1;
        }
        $max = ceil($response['count']/$limit);
        $data = [
            "page"=> (int)$page,
            "limit"=> (int)$limit,
            "max_page"=>$max,
            // "original"=>$url,
            // "count"=>$response['count'],
            // "next"=>$response['next'],
            "next_page"=>$next_page,
            // "previous"=>$response['previous'],
            "previous_page"=>$previous_page,
            "results"=>$result 
        ];
        $return = response()->json($data);
        // $return = $response->json();

        return $return;
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $url = 'https://pokeapi.co/api/v2/pokemon/'.$id;
        $response = Http::get($url);
        // $return = $response->json();
        $image = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/";
        $data = [
            "id"=> $response['id'],
            "name"=> $response['name'],
            "image"=> $image.$response['id'].".png",
            "height"=> $response['height'],
            "weight"=> $response['weight'],
            "types"=> $response['types'],
            "stats"=> $response['stats'],
            "sprites"=> $response['sprites'],
            "species"=> $response['species'],
            "moves"=> $response['moves']
        ];
        $return = response()->json($data);
        // $return = $response->json();
        return $return;

    }

    public function catch(Request $request){
        $id = $request->id;
        $name = $request->name;
        $chance = rand(0,1);
        $data = [
            "id"=>$id,
            "name"=>$name,
            "status" => ($chance ? true:false),
            "message" => ($chance ? "Congratulation. You Catch ".$name:"Sorry, Try to Catch ".$name." Again Later")
        ];
        $return = response()->json($data);
        return $return;
    }

    public function release(Request $request){
        $id = $request->id;
        $name = $request->name;
        $chance = $this->primeCheck(rand(1,15));
        $data = [
            "id"=>$id,
            "name"=>$name,
            "status" => ($chance ? true:false),
            "message" => ($chance ? "Your ".$name." was succesfully release":"Sorry, your ".$name." don't want to seperate with you")
        ];
        $return = response()->json($data);
        return $return;
    }

    public function primeCheck($number){
        if ($number == 1)
        return 0;
        
        for ($i = 2; $i <= sqrt($number); $i++){
            if ($number % $i == 0)
                return 0;
        }
        return 1;
    }

    public function rename(Request $request){
        $id = $request->id;
        $name = $request->name;
        $nickname = $request->nickname;
        $index = $request->index;
        $fibbonaci = $this->fibbonachiNumber($index);
        $data = [
            "id"=>$id,
            "name"=>$name,
            "nickname"=>$nickname,
            "index"=>$index+1,
            "fibbonaci" => $fibbonaci,
            "message" => "Your ".$name." rename to ".$nickname."-".$fibbonaci
        ];
        $return = response()->json($data);
        return $return;
    }

    public function fibbonachiNumber($number){
        $index = $number;
        
        if($number < 0){
            $index = 0;
        }
        $counter = 1;  
        $n1 = 0;  
        $n2 = 1;  

        $n3 = 0;  
        if($index == 0){
            return $n1;
        }else if($index == 1){
            return $n2;
        }else{
            while ($counter < $index ) {
                $n3 = $n2 + $n1;
                $n1 = $n2;  
                $n2 = $n3; 
                $counter++;
            }
            return $n3;
        }
    }
}
