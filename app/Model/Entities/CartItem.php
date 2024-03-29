<?php


namespace App\Model\Entities;


use LeanMapper\Entity;

/**
 * Class CartItem
 * @package App\Model\Entities
 * @property int $cartItemId
 * @property Product $product m:hasOne
 * @property Cart $cart m:hasOne
 * @property int $count = 0
 * @property int|null $sizeId = null
 */
class CartItem extends Entity{

}