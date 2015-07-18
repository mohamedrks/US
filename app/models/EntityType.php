<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/23/15
 * Time: 12:47 PM
 */



class EntityType extends Eloquent  {

    //use SoftDeletingTrait;

    protected $table = 'entity_type';
    protected $primaryKey = 'id';


    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT entity_type.* FROM entity_type  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }


}