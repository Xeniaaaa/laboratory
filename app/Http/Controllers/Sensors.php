<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Client;

class Sensors extends Controller
{

    public function get()
    {
        $data = [
            [
                "value" => 30,
                "name"=> "Температура"
            ],
            [
                "value" => 25,
                "name"=> "Влажность"
            ],
            [
                "value" => 761,
                "name"=> "Давление"
            ]
        ];
        return response()->json(["data" => $data]);
    }

    public function OnOff(Request $request){
        $data = $request->all();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://82.137.162.129:1722?'.$data['sensorname']."=".$data['act']);
        $resp = (string)$res->getBody();
        return response()->json(["data"=> $resp]);
    }

    public function speed(Request $request, $value){
        $data = $request->all();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://82.137.162.129:1722?ms='.$value);
        $resp = (string)$res->getBody();
        return response()->json(["data"=> $resp]);
    }
}
