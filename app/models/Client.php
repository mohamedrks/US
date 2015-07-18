<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/22/14
 * Time: 3:14 PM
 */

class Client extends BaseModel  {

    protected $table = 'client';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  client.* FROM client ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function user(){

        return $this->belongsToMany('User');
    }

    public function portfolio(){

        return $this->belongsToMany('Portfolio');
    }

    public function account(){

        return $this->belongsTo('Account');
    }

    public function country(){

        return $this->belongsTo('Country');
    }

    public function note()
    {
        return $this->hasMany('Note');
    }



}
