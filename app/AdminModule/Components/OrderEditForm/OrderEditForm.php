<?php

namespace App\AdminModule\Components\OrderEditForm;

use App\AdminModule\Presenters\OrderPresenter;
use App\Model\Entities\Orders;
use App\Model\Entities\Product;
use App\Model\Entities\SizesToProducts;
use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\OrdersFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\SizeFacade;
use App\Model\Facades\SizesToProductsFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class OrderEditForm
 * @package App\AdminModule\Components\OrderEditForm
 *
 * @method onFinished(string $message = '')
 * @method onFailed(string $message = '')
 * @method onCancel()
 */
class OrderEditForm extends Form{

  use SmartObject;

  /** @var callable[] $onFinished */
  public array $onFinished = [];
  /** @var callable[] $onFailed */
  public array $onFailed = [];
  /** @var callable[] $onCancel */
  public array $onCancel = [];

  private CategoriesFacade $categoriesFacade;
  private OrdersFacade $ordersFacade;
  private SizeFacade $sizeFacade;
  private SizesToProductsFacade $sizesToProductsFacade;

    /**
     * TagEditForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param string|null $name
     * @param CategoriesFacade $categoriesFacade
     * @param OrdersFacade $ordersFacade
     * @param SizeFacade $sizeFacade
     * @param SizesToProductsFacade $sizesToProductsFacade
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, CategoriesFacade $categoriesFacade, OrdersFacade $ordersFacade, SizeFacade $sizeFacade, SizesToProductsFacade $sizesToProductsFacade){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->categoriesFacade=$categoriesFacade;
    $this->ordersFacade = $ordersFacade;
    $this->sizeFacade = $sizeFacade;
    $this->sizesToProductsFacade = $sizesToProductsFacade;
    $this->createSubcomponents();
  }

  private function createSubcomponents():void {
    $this->addHidden('ordersId');

    $this->addInteger('price','Cena')
      ->setDisabled();

    $this->addText('user', 'Uživatel')
      ->setDisabled();

    $this->addText('created', 'Vytvořeno')
        ->setDisabled();

    $this->addRadioList('status', 'Status objednávky', OrderPresenter::$orderStatuses)
        ->setRequired('Status objednávky musí být vyplněn');

    $this->addSubmit('ok','uložit')
      ->onClick[]=function(SubmitButton $button){
        $values=$this->getValues('array');
        $order = $this->ordersFacade->getOrder($values['ordersId']);
        $order->status = $values['status'];
        $this->ordersFacade->saveOrder($order);
        $this->onFinished('Objednávka byla upravena.');
      };
    $this->addSubmit('storno','zrušit')
      ->onClick[]=function(SubmitButton $button){
        $this->getPresenter()->redirect('Order:default');
      };
  }

  /**
   * Metoda pro nastavení výchozích hodnot formuláře
   * @param Product|array|object $values
   * @param bool $erase = false
   * @return $this
   */
  public function setDefaults($values, bool $erase = false):self {
    if ($values instanceof Orders){
      $values = [
        'ordersId'=>$values->ordersId,
        'price'=>$values->price,
        'user'=>$values->user->name,
        'created'=>$values->created,
        'status'=>$values->status
      ];
    }
    parent::setDefaults($values, $erase);
    return $this;
  }

}
