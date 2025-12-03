<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class ResponseApp extends Model
{
    use HasFactory;

    /*
     *  Function de respuesta JSON Success
    */
    static function success($data=[],$message=[],$code=200){
        $response['data'] = $data;
        $response['message'] = $message;
        $response['success'] = true;
        return response($response,$code)->header('Content-Type', 'application/json');
    }
    /*
     *  Function de respuesta JSON Error
    */
    static function error($data=[],$messages=[],$code=400){
        if($code == 0){
            $code = 400;
        }
        $response['data'] = $data;
        $response['message'] = $messages;
        $response['success'] = false;
        return response($response,$code)->header('Content-Type', 'application/json');
    }
    /**
     * Validamos datos
     */
    public static function Validator($request,$rules,$messages){
        $response['message']['error'] = array();
        $response['message']['success'] = array();
        $response['success'] = false;
        $response['data'] = array();
        $validator = Validator::make($request,$rules,$messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach($errors as $key => $e){
                array_push($response['message']['error'],$e);
            }
        }else{
            $response['message']['success'] = array('ok');
            $response['success'] = true;
        }
        return $response;
    }
}
