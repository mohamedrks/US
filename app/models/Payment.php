<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 5/11/15
 * Time: 4:20 PM
 */

class Payment extends BaseModel  {

    protected $table = 'payment';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT payment.* FROM payment  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

}