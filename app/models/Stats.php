<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 3:42 PM
 */

class Stats extends BaseModel  {

    protected $table = 'stats';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT stats.* FROM stats  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function users(){

        return $this->belongsTo('Users');
    }

    public function client_user(){

        return $this->belongsTo('Users','client_user_id');
    }


}