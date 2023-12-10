<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\SizeEditForm\SizeEditForm;
use App\AdminModule\Components\SizeEditForm\SizeEditFormFactory;
use App\Model\Facades\SizeFacade;

/**
 * Class SizePresenter
 * @package App\AdminModule\Presenters
 */
class SizePresenter extends BasePresenter{
    private SizeFacade $sizeFacade;
    private SizeEditFormFactory $sizeEditFormFactory;

    /**
     * Akce pro vykreslení seznamu velikosti
     */
    public function renderDefault():void {
        $this->template->sizes=$this->sizeFacade->findSizes();
    }

    /**
     * Akce pro úpravu jedné velikosti
     * @param int $id
     * @throws \Nette\Application\AbortException
     */
    public function renderEdit(int $id):void {
        try{
            $size=$this->sizeFacade->getSize($id);
        }catch (\Exception $e){
            $this->flashMessage('Požadovaná velikost nebyla nalezena.', 'error');
            $this->redirect('default');
        }
        $form=$this->getComponent('sizeEditForm');
        $form->setDefaults($size);
        $this->template->size=$size;
    }

    /**
     * Akce pro smazání velikosti
     * @param int $id
     * @throws \Nette\Application\AbortException
     */
    public function actionDelete(int $id):void {
        try{
            $size=$this->sizeFacade->getSize($id);
        }catch (\Exception $e){
            $this->flashMessage('Požadovaná velikost nebyla nalezena.', 'error');
            $this->redirect('default');
        }

        if (!$this->user->isAllowed($size,'delete')){
            $this->flashMessage('Tuto velikost není možné smazat.', 'error');
            $this->redirect('default');
        }

        if ($this->sizeFacade->deleteSize($size)){
            $this->flashMessage('Velikost byla smazána.', 'info');
        }else{
            $this->flashMessage('Tuto velikost není možné smazat.', 'error');
        }

        $this->redirect('default');
    }

    /**
     * Formulář na editaci velikosti
     * @return SizeEditForm
     */
    public function createComponentSizeEditForm():SizeEditForm {
        $form = $this->sizeEditFormFactory->create();
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
    public function injectSizeFacade(SizeFacade $sizeFacade):void {
        $this->sizeFacade=$sizeFacade;
    }
    public function injectSizeEditFormFactory(SizeEditFormFactory $sizeEditFormFactory):void {
        $this->sizeEditFormFactory=$sizeEditFormFactory;
    }
    #endregion injections

}
