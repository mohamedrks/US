<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/20/15
 * Time: 11:54 AM
 */

class Order extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'order';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('Users', 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    // ...

}