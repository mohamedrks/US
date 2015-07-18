<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/19/15
 * Time: 3:34 PM
 */

use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Email extends Eloquent  {

    //use SoftDeletingTrait;

    protected $table = 'email';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT email.* FROM email  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }


}