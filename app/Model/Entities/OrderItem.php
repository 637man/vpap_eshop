<?php


namespace App\Model\Entities;


use LeanMapper\Entity;

/**
 * Class OrderItem
 * @package App\Model\Entities
 * @property int $orderItemId
 * @property int $productId
 * @property int $orderId
 * @property float $price
 * @property int $count = 0
 * @property int|null $sizeId = null
 */
class OrderItem extends Entity{

}