<?php

namespace App\Http\Controllers;

use App\Actions;
use App\Characteristics;
use App\Components;
use App\Items;
use App\Journal;
use App\Properties;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getTables(Request $request)
    {
        $tables = [
            ["value" => "actions", "text" => "Действия"],
            ["value" => "characteristics", "text" => "Характеристики"],
            ["value" => "components", "text" => "Компоненты"],
            ["value" => "items", "text" => "Размерности"],
            ["value" => "journal", "text" => "Журнал"],
            ["value" => "properties", "text" => "Свойства"],
            ["value" => "sessions", "text" => "Сессии"],
            ["value" => "users", "text" => "Пользователи"],
        ];
        return response()->json($tables);
    }

    public function getTable(Request $request, $name)
    {
        $table = DB::table($name)->get();

        return response()->json($table);
    }

    public function addNewObject(Request $request)
    {
        try {
            $modeles = [
                'actions' => Actions::class,
                'characteristics' => Characteristics::class,
                'components' => Components::class,
                'items' => Items::class,
                'journal' => Journal::class,
                'properties' => Properties::class,
                'users' => User::class,
            ];

            $data = $request->all();
            $model = new $modeles[$data['table']];
            unset($data['table']);
            unset($data['id']);
            foreach ($data as $key => $value) {
                $model->{$key} = $value;
            }
            $model->save();
            return response()->json(["data"=>["msg"=>"Данные добавлены"]]);
        } catch (\Throwable $th) {
            return response()->json(["data"=>["msg"=>"Произошла ошибка при добавлени"]], 500);
        }
    }


    public function saveObject(Request $request)
    {
        $modeles = [
            'actions' => Actions::class,
            'characteristics' => Characteristics::class,
            'components' => Components::class,
            'items' => Items::class,
            'journal' => Journal::class,
            'properties' => Properties::class,
            'users' => User::class,
        ];
        $datas = $request->all();
        foreach ($datas as $key => $data) {
            $model = $modeles[$data['table']]::where('id', $data['id'])->first();
            $model->{$data['field']} = $data['value'];
            $model->save();
        }
    }

}
