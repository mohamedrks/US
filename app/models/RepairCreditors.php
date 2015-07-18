<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 7/8/15
 * Time: 9:43 PM
 */

class RepairCreditors extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'repair_creditors';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function repairOutsource()
    {
        return $this->belongsTo('RepairOutsource', 'repair_outsource_id');
    }


}