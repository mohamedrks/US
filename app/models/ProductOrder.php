<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 12:43 PM
 */

use \Michelf\MarkdownExtra;

/**
 * ProductOrder
 */
class ProductOrder extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'product_orders';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function order()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }

    // ...

}