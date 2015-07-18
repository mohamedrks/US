<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/20/15
 * Time: 3:38 PM
 */

class UserDetails extends BaseModel  {

    protected $table = 'user_details';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT user_details.* FROM user_details  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function user(){

        return $this->belongsTo('Users');
    }

}