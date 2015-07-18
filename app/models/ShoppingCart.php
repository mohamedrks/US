<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 12:47 PM
 */

use \Michelf\MarkdownExtra;

/**
 * ShoppingCart
 */
class ShoppingCart extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'product_cart';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = false;

    /**
     * ORM (Modeling object relationships): Seller
     * @return object User
     */
    public function seller()
    {
        return $this->belongsTo('User', 'user_id');
    }

    // ...

}