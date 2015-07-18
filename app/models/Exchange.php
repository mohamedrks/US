<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 7/13/15
 * Time: 8:32 PM
 */

class Exchange extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'exchange';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function salesItem(){

        return $this->belongsTo('ItemDetails','sales_imei');
    }

    public function purchaseItem(){

        return $this->belongsTo('ItemDetails','purchase_imei');
    }



}

