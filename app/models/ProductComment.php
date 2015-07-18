<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 12:41 PM
 */

/**
 * Product comments
 */
class ProductComment extends BaseModel
{
    /**
     * Database table (Not include prefix)
     * @var string
     */
    protected $table = 'product_comments';

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