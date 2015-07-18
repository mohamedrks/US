<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/19/15
 * Time: 11:53 AM
 */
class Sms extends BaseModel  {

    protected $table = 'sms';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT sms.* FROM sms  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }


}