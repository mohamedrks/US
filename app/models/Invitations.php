<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/29/15
 * Time: 8:36 PM
 */

class Invitations extends BaseModel  {

    protected $table = 'invitations';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT invitations.* FROM invitations  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function user(){

        return $this->belongsTo('Users','user_inviting_id');
    }


}