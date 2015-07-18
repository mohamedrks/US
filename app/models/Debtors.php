<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 6/22/15
 * Time: 10:59 PM
 */

class Debtors extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'debtors';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function invoice()
    {
        return $this->belongsTo('Invoice', 'invoice_id');
    }


}