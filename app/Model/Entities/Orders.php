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
 * @property int $psc
 * @property string $city
 * @property string $address
 * @property int $telephone
 * @property string $email
 */
class Orders extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'Orders';
    }
}