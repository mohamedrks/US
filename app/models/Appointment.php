<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 3:31 PM
 */

class Appointment extends BaseModel  {

    protected $table = 'appointment';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT appointment.* FROM appointment  ";
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