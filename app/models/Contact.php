<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 9:40 AM
 */

class Contact extends BaseModel  {

    protected $table = 'contact';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT contact.* FROM contact  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function contact_type(){

        return $this->belongsTo('ContactType','contact_type');
    }

    public function suburb(){

        return $this->belongsToMany('Suburb');
    }

    public function users(){

        return $this->belongsToMany('Users','contact_users','contact_id','users_id');
    }

}