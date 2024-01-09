<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\ProductEditForm\ProductEditForm;
use App\AdminModule\Components\ProductEditForm\ProductEditFormFactory;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\SizesToProductsFacade;

/**
 * Class ProductPresenter
 * @package App\AdminModule\Presenters
 */
class ProductPresenter extends BasePresenter{
  private ProductsFacade $productsFacade;
  private ProductEditFormFactory $productEditFormFactory;
  private SizesToProductsFacade $sizesToProductsFacade;

  /**
   * Akce pro vykreslení seznamu produktů
   */
  public function renderDefault():void {
    $this->template->products=$this->productsFacade->findProducts(['order'=>'title']);
  }

  /**
   * Akce pro úpravu jednoho produktu
   * @param int $id
   * @throws \Nette\Application\AbortException
   */
  public function renderEdit(int $id):void {
    try{
      $product=$this->productsFacade->getProduct($id);
    }catch (\Exception $e){
      $this->flashMessage('Požadovaný produkt nebyl nalezen.', 'error');
      $this->redirect('default');
    }
    if (!$this->user->isAllowed($product,'edit')){
      $this->flashMessage('Požadovaný produkt nemůžete upravovat.', 'error');
      $this->redirect('default');
    }

    $form=$this->getComponent('productEditForm');
    $defaults = $product->getData();
    $sizes = $this->sizesToProductsFacade->findSizes(['product_id' => $product->productId]);
    $defSizes = [];
    foreach ($sizes as $size) {
        $defSizes[$size->sizeId] = true;
    }
    $defaults['sizes'] = $defSizes;
    $form->setDefaults($defaults);
    $this->template->product=$product;
  }

  /**
   * Formulář na editaci produktů
   * @return ProductEditForm
   */
  public function createComponentProductEditForm():ProductEditForm {
    $form = $this->productEditFormFactory->create();
    $form->onCancel[]=function(){
      $this->redirect('default');
    };
    $form->onFinished[]=function($message=null){
      if (!empty($message)){
        $this->flashMessage($message);
      }
      $this->redirect('default');
    };
    $form->onFailed[]=function($message=null){
      if (!empty($message)){
        $this->flashMessage($message,'error');
      }
      $this->redirect('default');
    };
    return $form;
  }

  #region injections
  public function injectProductsFacade(ProductsFacade $productsFacade):void {
    $this->productsFacade=$productsFacade;
  }
  public function injectProductEditFormFactory(ProductEditFormFactory $productEditFormFactory):void {
    $this->productEditFormFactory=$productEditFormFactory;
  }
    public function injectsizesToProductsFacade(SizesToProductsFacade $sizesToProductsFacade):void {
        $this->sizesToProductsFacade=$sizesToProductsFacade;
    }
  #endregion injections

}
