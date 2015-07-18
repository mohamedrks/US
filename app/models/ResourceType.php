<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 12:02 PM
 */

class ResourceType extends BaseModel  {

    protected $table = 'resource_type';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT resource_type.* FROM resource_type  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }


}