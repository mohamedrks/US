<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/31/15
 * Time: 10:36 AM
 */

class Notification extends BaseModel  {

    protected $table = 'notification';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT notification.* FROM notification  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function subject(){

        return $this->belongsTo('Users','subject_id');
    }

    public function object(){

        return $this->belongsTo('Users','object_id');
    }


}