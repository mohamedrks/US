<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 12:42 PM
 */

/**
 * Product Pictures
 */
class ProductPictures extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'product_pictures';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * Object-relational model: Vesting product
     * @return object Product
     */
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }

    // ...

}