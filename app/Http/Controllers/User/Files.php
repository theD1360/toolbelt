<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/3/17
 * Time: 11:49 PM
 */

namespace App\Http\Controllers\User;


use App\Models\FilesModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Files
{

    /**
     * @var \App\Models\FilesModel;
     */
    protected $filesModel;

    public function __construct(FilesModel $files)
    {
        $this->filesModel = $files;
    }

    public function index(Response $response, Request $request) {
        $user = $request->user();

        return $response->setContent($user->files);
    }

    public function create(Response $response, Request $request) {
        $user = $request->user();


        $file = new FilesModel();
        $file->ipfs_hash = $request->input('ipfs_hash');
        $file->type = $request->input('type');
        $file->filesize =  $request->input('filesize');

        $user->files()->save($file, ['short_name' => $request->input('short_name'), "local_path" => $request->input('local_path')]);

        return $response->setContent($file);

    }

    public function read() {
        $this->filesModel->find();
    }

    public function update() {

    }

    public function delete() {

    }

}