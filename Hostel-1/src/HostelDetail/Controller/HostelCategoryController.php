<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HostelDetail\Controller;


//use Blog\Form\Add;
//use Blog\InputFilter\AddPost;
//use Blog\Entity\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use HostelDetail\Form\AddHostelCategoryForm;




/**
 * Description of IndexController
 *
 * @author Mendrel
 */
class HostelCategoryController extends AbstractActionController
{
    
    public function addhostelcategoryAction()
    {
        $form = new AddHostelCategoryForm();
        
        return new ViewModel(array(
            'form' => $form,
            ));
        
        
        
        
    }
    
    
    

    /* public function addAction()
            {
        $form = new Add();
        
        if($this->request->isPost()){
            $blogPost = new Post();
            $form->bind($blogPost);
            $form->setInputFilter(new AddPost());
            $form->setData($this->request->getPost());
            
            if($form->isValid()){
                /** TODO Save Post here */
                
                
           /* }
        }
        
        return new ViewModel(array(
            'form' => $form,
        ));
    }*/
}
