<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 7/8/15
 * Time: 9:38 PM
 */

class RepairOutsource extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'repair_outsource';

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

}
