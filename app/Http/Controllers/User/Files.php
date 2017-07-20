<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/3/17
 * Time: 11:49 PM
 */

namespace App\Http\Controllers\User;


use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Files
{

    /**
     * @var \App\Models\File;
     */
    protected $File;

    public function __construct(File $files)
    {
        $this->File = $files;
    }

    public function index(Response $response, Request $request) {
        $user = $request->user();

        $files = $user->files->each(function($file){
            $file->file;
        });

        return $response->setContent($files);
    }

    public function create(Response $response, Request $request) {
        $user = $request->user();


        $file = new File();
        $file->user_id = $user->id;
        $file->directory_id = $request->input('directory_id', $user->directory->first()->id);
        $file->filename =  $request->input('name');
        $file->origin_path = $request->input('path');
        $file->ipfs_hash = $request->input('hash');
        $file->type = $request->input('type');
        $file->filesize =  $request->input('size');
        $file->save();



        return $response->setContent($file);

    }

    public function read() {
        $this->File->find();
    }

    public function update() {

    }

    public function delete() {

    }

}