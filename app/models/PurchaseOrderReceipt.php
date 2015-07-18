<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/2/2015
 * Time: 12:08 AM
 */

class PurchaseOrderReceipt extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'purchase_order_reciept';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function stock()
    {
        return $this->belongsTo('Stock', 'stock_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('PurchaseOrder', 'purchase_order_id');
    }

    // ...

}