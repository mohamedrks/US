<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/14/15
 * Time: 5:49 PM
 */


class Country extends BaseModel
{

    protected $table = 'country';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT country.* FROM country  ";
    }

    public static function queryWhere()
    {

        return "";
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public function user()
    {
        return $this->hasManyThrough('User', 'Client');
    }

    public function client()
    {
        return $this->hasMany('Client');
    }

}