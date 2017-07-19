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

    $dir = \App\Models\Directory::create([
        'name' => 'home',
        'children' => [
            [
                'name' => 'Photos',
                'children' => [
                    [
                        'name' => 'Vacation',
                    ],
                ]
            ],
            [
                'name' => 'Videos',
            ],
            [
                'name' => 'Music',
            ],
            [
                'name' => 'Documents',
            ],
        ],
    ]);

    $dir->user_id = $userModel->id;
    $dir->save();

    return $response->setContent($userModel);
});


$app->post('/auth/login', "Auth\\AuthController@postLogin");



$app->group(['prefix' => 'user', "middleware" => "auth"], function() use ($app) {

    $app->group(['prefix' => 'files'], function() use ($app) {
        $app->get("/", "User\\Files@index");
        $app->post("/", "User\\Files@create");
    });


    $app->group(['prefix' => 'directories'], function() use ($app) {
        $app->get("/", "User\\Directories@index");
        $app->get("/{id}", "User\\Directories@read");

    });



    $app->get('/profile', ['middleware'=>'auth', function() {
        return "hi!";
    }]);

});