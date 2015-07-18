<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 6/4/15
 * Time: 10:57 AM
 */


class Stock extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'stock';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo('Vendor', 'vendor_id');
    }

    // ...

}