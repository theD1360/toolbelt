<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/17/17
 * Time: 10:50 PM
 */

namespace App\Http\Controllers\User;


use App\Models\Directory;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class Directories
{

    /**
     * @var \App\Models\Directory;
     */
    protected $directory;

    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    public function index(Response $response, Request $request) {
        $user = $request->user();

        $dir = $user->directory;

        $dir->children;
        $dir->files;


        return $response->setContent($dir);
    }

    public function create(Response $response, Request $request) {

    }

    public function read($id, Response $response, Request $request) {

        $user = $request->user();


        $dir = Directory::findOrFail($id);
        $valid = $dir->isDescendantOf( $user->directory);

        if (!$valid) {
            return $response->setStatusCode(401);
        }

        $dir->children;
        $dir->files;

        return $response->setContent($dir);
    }

    public function update() {

    }

    public function delete() {

    }

}