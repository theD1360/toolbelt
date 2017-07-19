<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/3/17
 * Time: 11:18 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    /**
     * The roles that belong to the user.
     */
    public function user()
    {
        return $this->belongsToMany('App\Models\User');
    }

}