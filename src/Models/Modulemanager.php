<?php
namespace ExtensionsValley\Modulemanager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulemanager extends Model
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'module_positions';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id','module_title','pages','ordering','module_name', 'vendor', 'layout', 'params','position','created_by','updated_by','is_all_page','custom_html'];


    public static function getItemwithPosition($position){

        return self::WhereNull('deleted_at')
                ->Where('position',$position)
                ->Orderby('ordering','ASC')
                ->get();
    }

}
