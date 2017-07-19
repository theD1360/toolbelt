<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/3/17
 * Time: 11:18 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;


class Directory extends Model
{
    use NodeTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    protected $hidden = ['user_id', '_lft', '_rgt'];

    /**
     * The User associated to the directory.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function files() {
        return $this->hasMany('App\Models\File');
    }

}