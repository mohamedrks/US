<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 12:25 AM
 */


class PurchaseInvoice extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'purchase_invoice';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function vendor()
    {
        return $this->belongsTo('Vendor', 'vendor_id');
    }

    public function purchaseOrder(){

        return $this->belongsTo('PurchaseOrder','purchase_order_id');
    }


    // ...

}