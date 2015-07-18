<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 6/11/2015
 * Time: 6:50 PM
 */

class Repair extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'repair';

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
        return $this->belongsTo('Customer', 'customer_id');
    }

}
