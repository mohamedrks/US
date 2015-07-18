<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 6/3/15
 * Time: 11:02 AM
 */

class ProfessionalDetails extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'professional_details';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('Users', 'user_id');
    }


    // ...

}