<?php

namespace GoodsDepreciation\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use GoodsDepreciation\Service\GoodsDepreciationServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use GoodsDepreciation\Model\GoodsDepreciation;
use GoodsDepreciation\Model\FixedAsset;
use GoodsDepreciation\Form\FixedAssetForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


//RBACL
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager; 

//AJAX
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;
 
  
class GoodsDepreciationController extends AbstractActionController
{
    protected $goodsDepreciationService;
    protected $notificationService;
    protected $auditTrailService;
    protected $emailService;
    protected $serviceLocator;
    protected $username;
    protected $userrole;
    protected $usertype;
    protected $userDetails;
    protected $userImage;
    protected $employee_details_id;
    protected $organisation_id;
    protected $userregion;

    protected $keyphrase = "RUB_IMS";

    protected $parentValue;
    protected $parentValue1;
	
	public function __construct(GoodsDepreciationServiceInterface $goodsDepreciationService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->goodsDepreciationService = $goodsDepreciationService;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->serviceLocator = $serviceLocator;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
        
        /*
         * To retrieve the user name from the session
        */
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        // Use service locator to get the authPlugin
        $this->username = $authPlugin['username'];
        $this->usertype = $authPlugin['user_type_id'];
        $this->userrole = $authPlugin['role'];
        $this->userregion = $authPlugin['region'];      

        //get the employee details id
        $empData = $this->goodsDepreciationService->getUserDetailsId($this->username);
        foreach($empData as $emp){
            $this->employee_details_id = $emp['id'];
            $this->departments_units_id = $emp['departments_units_id'];
            $this->departments_id = $emp['departments_id'];
        }

        //get the organisation id
        $organisationID = $this->goodsDepreciationService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $this->organisation_id = $organisation['organisation_id'];
        }

        $this->userDetails = $this->goodsDepreciationService->getUserDetails($this->username, $this->usertype);
        $this->userImage = $this->goodsDepreciationService->getUserImage($this->username, $this->usertype);

	}

    public function loginDetails()
    {
        $this->layout()->setVariable('userRole', $this->userrole);
        $this->layout()->setVariable('userRegion', $this->userregion);
        $this->layout()->setVariable('userType', $this->usertype);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function ajaxdataAction()
    {
        $parentValue = $_POST['value'];
      //  $parentValue = "test";

        /**
         * Here, once again, you want to query your database for the values!
         * Something like
         * $selectTwoData = $goodsTransactionService->findValuesForSelectOne($parentValue);
         */
       // $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $dbAdapter = $this->adapter;
       
        $sql       = "SELECT id, sub_category_type FROM item_sub_category where item_category_type='$parentValue'";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectTwoData = array();
        $selectTwoData = array('0' => 'Select Item Sub Category');
        //$selectTwoData['------>']="---------";
       foreach ($result as $res) {
        
            $selectTwoData[$res['id']] = $res['sub_category_type'];
        }

        return new JsonModel([
            'data' => $selectTwoData
        ]);  
    }

    public function ajaxdatathreeAction()
    {
        $parentValue1 = $_POST['value'];
      //  $parentValue = "test";

        /**
         * Here, once again, you want to query your database for the values!
         * Something like
         * $selectTwoData = $goodsTransactionService->findValuesForSelectOne($parentValue);
         */
        $dbAdapter1 = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        // $sql       = "SELECT rolename FROM user_role where details='$parentValue1'";
       
        $sql       = "SELECT details FROM user_acl where route='$parentValue1'";
        $statement = $dbAdapter1->query($sql);
        $result    = $statement->execute();
        $selectThreeData = array();

       foreach ($result as $res) {
            $selectThreeData[$res['details']] = $res['details'];
        }

        return new JsonModel([
            'data1' => $selectThreeData
        ]);
    }
 

    

    //To view or display list of Item with Department Action

    public function viewFixedAssetListAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'allFixedAssets' => $this->goodsDepreciationService->findAllFixedAssets()
            ));
    }


    // To enter Fixed Asset Depreciation Value

    public function enterFixedAssetDeprValAction()
    {
        $this->loginDetails();

        $fixedAssetDetails = $this->goodsDepreciationService->findFixedAssetDetails($this->params('id'));



        $form = new FixedAssetForm();
        $goodsDepreciationModel = new FixedAsset();
        $form->bind($goodsDepreciationModel);             

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
                     $this->goodsDepreciationService->saveDepreciationValue($goodsDepreciationModel);

                     return $this->redirect()->toRoute('view-depreciation-value');
                 }
                 catch(\Exception $e) {
                         die($e->getMessage());
                         // Some DB Error happened, log it and let the user know
                 }
             }
         }
         return array(
             'form' => $form,
             'fixedAssetDetails' => $fixedAssetDetails,             
         );
    }


    //To view or display list of Item with Department Action

    public function viewDepreciationValueAction()
    {
        $this->loginDetails();

        return new ViewModel(array(
            'depreciationValueTable' => $this->goodsDepreciationService->findAllDepreciationValue()
            ));
    }

}
             