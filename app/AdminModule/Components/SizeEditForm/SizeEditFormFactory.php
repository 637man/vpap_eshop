<?php

namespace App\AdminModule\Components\SizeEditForm;

/**
 * Interface SizeEditFormFactory
 * @package App\AdminModule\Components\SizeEditForm
 */
interface SizeEditFormFactory{

  public function create():SizeEditForm;

}