<?php

namespace App\FrontModule\Components\CreateOrderForm;

use App\Model\Facades\UsersFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class CreateOrderForm
 * @package App\FrontModule\Components\CreateOrderForm
 *
 * @method onFinished($message='')
 * @method onCancel()
 */
class CreateOrderForm extends Form{

  use SmartObject;

  /** @var callable[] $onFinished */
  public array $onFinished = [];
  /** @var callable[] $onCancel */
  public array $onCancel = [];

  private UsersFacade $usersFacade;
  private Nette\Application\LinkGenerator $linkGenerator;


  /**
   * ForgottenPasswordForm constructor.
   * @param Nette\ComponentModel\IContainer|null $parent
   * @param string|null $name
   * @param UsersFacade $usersFacade
   * @param Nette\Application\LinkGenerator $linkGenerator
   */
  public function __construct(?Nette\ComponentModel\IContainer $parent = null, ?string $name = null, UsersFacade $usersFacade, Nette\Application\LinkGenerator $linkGenerator){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->usersFacade=$usersFacade;
    $this->createSubcomponents();
    $this->linkGenerator=$linkGenerator;
  }

  private function createSubcomponents():void {
    $this->addHidden('cartId');
    $this->addText('address','Doručovací adresa')
      ->setRequired('Zadejte adresu, kam se objednávka má doručit');
    $this->addText('city', 'Město')
        ->setRequired('Zadejte město pro doručení');
    $this->addText('psc', 'PSČ')
        ->setRequired('Zadejte PSČ pro doručení')
        ->addRule(self::LENGTH, 'Délka PSČ je 5 znaků', 5);
    $this->addText('phone', 'Telefonní číslo')
        ->setRequired('Zadejte vaše kontaktní telefonní číslo')
        ->addRule(self::LENGTH, 'Telefonní číslo má 9 čísel', 9);

    $this->addCheckbox('confirmation', 'Souhlasím se závazným vytvořením objednávky.')
        ->setRequired();

    $this->addSubmit('ok','Vytvořit objednávku');
  }

}