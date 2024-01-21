<?php

namespace App\FrontModule\Components\CreateOrderForm;

/**
 * Interface CreateOrderFormFactory
 * @package App\FrontModule\Components\CreateOrderForm
 */
interface CreateOrderFormFactory{

  public function create():CreateOrderForm;

}