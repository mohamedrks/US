<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 11:06 AM
 */

class Creditors extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'creditors';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function purchaseInvoice()
    {
        return $this->belongsTo('PurchaseInvoice', 'purchase_invoice_id');
    }


}