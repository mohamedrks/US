<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 1:32 AM
 */


class ItemDetails extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'item_details';

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

    public function purchaseOrder(){

        return $this->belongsTo('PurchaseOrder','purchase_order_id');
    }


    // ...

}