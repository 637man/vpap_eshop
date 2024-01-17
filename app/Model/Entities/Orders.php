<?php

namespace App\Model\Entities;

use Dibi\DateTime;
use LeanMapper\Entity;

/**
 * Class Order
 * @package App\Model\Entities
 * @property int $ordersId
 * @property User $user m:hasOne
 * @property float $price
 * @property int $status
 * @property DateTime $created
 * @property int $cartId
 */
class Orders extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'Orders';
    }
}