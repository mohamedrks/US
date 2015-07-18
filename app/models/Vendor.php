<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/21/15
 * Time: 6:20 PM
 */

class Vendor extends BaseModel  {

    protected $table = 'vendor';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT vendor.* FROM vendor  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }


}