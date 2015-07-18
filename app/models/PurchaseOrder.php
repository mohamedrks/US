<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/1/2015
 * Time: 11:54 PM
 */


class PurchaseOrder extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'purchase_order';

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

    public function vendor()
    {
        return $this->belongsTo('Vendor', 'vendor_id');
    }

    // ...

}