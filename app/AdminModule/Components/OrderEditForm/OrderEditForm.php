<?php

namespace App\AdminModule\Components\OrderEditForm;

use App\Model\Entities\Orders;
use App\Model\Entities\Product;
use App\Model\Entities\SizesToProducts;
use App\Model\Facades\CategoriesFacade;
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
  private ProductsFacade $productsFacade;
  private SizeFacade $sizeFacade;
  private SizesToProductsFacade $sizesToProductsFacade;

    /**
     * TagEditForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param string|null $name
     * @param CategoriesFacade $categoriesFacade
     * @param ProductsFacade $productsFacade
     * @param SizeFacade $sizeFacade
     * @param SizesToProductsFacade $sizesToProductsFacade
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, CategoriesFacade $categoriesFacade, ProductsFacade $productsFacade, SizeFacade $sizeFacade, SizesToProductsFacade $sizesToProductsFacade){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->categoriesFacade=$categoriesFacade;
    $this->productsFacade=$productsFacade;
    $this->sizeFacade = $sizeFacade;
    $this->sizesToProductsFacade = $sizesToProductsFacade;
    $this->createSubcomponents();
  }

  private function createSubcomponents():void {
    $productId=$this->addHidden('ordersId');

    $this->addInteger('price','Cena')
      ->setDisabled();

    $this->addText('user', 'Uživatel')
      ->setDisabled();

    $this->addText('created', 'Vytvořeno')
      ->setHtmlType('date')
        ->setDisabled();

    $this->addInteger('status', 'Status')
      ->setHtmlAttribute('title', 'Hdonoty: 1 => nová objednávka, 2 => zaplacená, 3 => odeslána')
    ->addRule(self::MIN, 'Minimální hodnota je 1', 1)
    ->addRule(self::MAX, "Maximální hodnota je 3", 3);

    $this->addSubmit('ok','uložit')
      ->onClick[]=function(SubmitButton $button){
        $values=$this->getValues('array');
        if (!empty($values['productId'])){
          try{
            $product=$this->productsFacade->getProduct($values['productId']);
          }catch (\Exception $e){
            $this->onFailed('Požadovaný produkt nebyl nalezen.');
            return;
          }
        }else{
          $product=new Product();
        }
        $product->assign($values,['title','url','description','available']);
        $product->category=$this->categoriesFacade->getCategory($values['categoryId']);
        $product->price=floatval($values['price']);
        $this->productsFacade->saveProduct($product);
        $this->setValues(['productId'=>$product->productId]);

        $this->onFinished('Produkt byl uložen.');
      };
    $this->addSubmit('storno','zrušit')
      ->setValidationScope([$productId])
      ->onClick[]=function(SubmitButton $button){
        $this->onCancel();
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
