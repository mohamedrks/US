<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 6/1/15
 * Time: 9:59 AM
 */


class Invoice extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'invoice';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function customer()
    {
        return $this->belongsTo('Customer', 'user_id');
    }

    public function order(){

        return $this->belongsTo('Order');
    }


    // ...

}