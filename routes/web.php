<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    $config = $app->make(\App\Helpers\ConfigHelper::class);
    $config->set("private", !$config->get('private'));
    return json_encode($config->get());
});



$app->post('/user/register', function (\Illuminate\Http\Request $request, \Illuminate\Http\Response $response) use ($app) {

    $userModel = new \App\Models\User();
    $exists = $userModel->where("email", 'like', $request->input("email"))->get()->count();

    if ($exists > 0) {
        $response->setStatusCode(400);
        return $response->setContent(['error'=>"Email already registered"]);
    }

    $userModel->email = $request->input("email");
    $userModel->name = $request->input("name");
    $userModel->password = hash_pw($request->input("password"));

    $userModel->save();

    return $response->setContent($userModel);
});


$app->post('/auth/login', "Auth\\AuthController@postLogin");


$app->get('/user/profile', ['middleware'=>'auth', function() {
    return "hi!";
}]);