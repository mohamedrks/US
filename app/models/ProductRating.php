<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/15/15
 * Time: 4:17 PM
 */

/**
 * Product comments
 */
class ProductRating extends BaseModel
{
    /**
     * Database table (Not include prefix)
     * @var string
     */
    protected $table = 'product_rating';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): product owner
     * @return object Article
     */
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }

    /**
     * ORM (Modeling object relationships): comments author
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('Users', 'user_id');
    }

    // ...

}