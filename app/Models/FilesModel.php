<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/3/17
 * Time: 11:18 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FilesModel extends Model
{
    protected $table = 'files';

    /**
     * The roles that belong to the user.
     */
    public function user()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('short_name', 'local_path');
    }

}