<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 6/28/2015
 * Time: 1:59 AM
 */


class RepairDebtors extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'repair_debtors';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function repair()
    {
        return $this->belongsTo('Repair', 'repair_id');
    }


}