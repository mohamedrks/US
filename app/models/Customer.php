<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 6/11/2015
 * Time: 6:21 PM
 */

class Customer extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'customer';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */


}

