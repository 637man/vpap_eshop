<?php

namespace App\AdminModule\Components\SizeEditForm;

use App\Model\Entities\Size;
use App\Model\Facades\SizeFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class SizeEditForm
 * @package App\AdminModule\Components\SizeEditForm
 *
 * @method onFinished(string $message = '')
 * @method onFailed(string $message = '')
 * @method onCancel()
 */
class SizeEditForm extends Form{

  use SmartObject;

  /** @var callable[] $onFinished */
  public array $onFinished = [];
  /** @var callable[] $onFailed */
  public array $onFailed = [];
  /** @var callable[] $onCancel */
  public array $onCancel = [];

  private SizeFacade $sizeFacade;

    /**
     * TagEditForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param string|null $name
     * @param SizeFacade $sizeFacade
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, SizeFacade $sizeFacade){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->sizeFacade=$sizeFacade;
    $this->createSubcomponents();
  }

  private function createSubcomponents():void {
    $sizeId=$this->addHidden('sizeId');
    $this->addText('size','Velikost')
      ->setRequired('Musíte zadat velikost');
    $this->addSubmit('ok','uložit')
      ->onClick[]=function(SubmitButton $button){
        $values=$this->getValues('array');
        if (!empty($values['sizeId'])){
          try{
            $size=$this->sizeFacade->getSize($values['sizeId']);
          }catch (\Exception $e){
            $this->onFailed('Požadovaná velikost nebyla nalezena.');
            return;
          }
        }else{
          $size=new Size();
        }
        $size->assign($values,['size']);
        $this->sizeFacade->saveSize($size);
        $this->setValues(['sizeId'=>$size->sizeId]);
        $this->onFinished('Velikost byla uložena.');
      };
    $this->addSubmit('storno','zrušit')
      ->setValidationScope([$sizeId])
      ->onClick[]=function(SubmitButton $button){
        $this->onCancel();
      };
  }

  /**
   * Metoda pro nastavení výchozích hodnot formuláře
   * @param Size|array|object $values
   * @param bool $erase = false
   * @return $this
   */
  public function setDefaults($values, bool $erase = false):self {
    if ($values instanceof Size){
      $values = [
        'sizeId'=>$values->sizeId,
        'size'=>$values->size
      ];
    }
    parent::setDefaults($values, $erase);
    return $this;
  }

}