<?php

namespace Accounts\Controller;

use DOMPDFModule\View\Model\PdfModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Accounts\Service\GenerateTdsReportServiceInterface;

/**
 * Class GenerateTdsReportController
 * @package Accounts\Controller
 */
class GenerateTdsReportController extends AbstractActionController {

    /**
     * @var integer
     */
    protected $_id;
    /**
     * @var integer
     */
    protected $e_id;
    /**
     * @var string
     */
    protected $user_name;
    /**
     * @var string
     */
    protected $user_role;
    /**
     * @var string
     */
    protected $user_type;
    /**
     * @var string
     */
    protected $user_region;
    /**
     * @var array
     */
    protected $userDetails;
    /**
     * @var integer
     */
    protected $employee_details_id;
    /**
     * @var integer
     */
    protected $organisation_id;
    /**
     * @var integer
     */
    protected $user_organisation_id;
    /**
     * @var GenerateTdsReportServiceInterface
     */
    protected $service;
    /**
     * @var object
     */
    protected $serviceLocator;
    /**
     * @var string
     */
    protected $_created;
    /**
     * @var string
     */
    protected $_modified;
    /**
     * @var string
     */
    protected $keyphrase = "RUB_IMS";

    /**
     * GenerateTdsReportController constructor.
     * @param GenerateTdsReportServiceInterface $service
     * @param $serviceLocator
     */
    public function __construct(GenerateTdsReportServiceInterface $service, $serviceLocator) {
        $this->service = $service;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Initialization to set variables value
     */
    public function init() {
        $authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
        $this->user_role = $authPlugin['role'];
        $this->user_name = $authPlugin['username'];
        $this->user_type = $authPlugin['user_type_id'];
        $this->user_region = $authPlugin['region'];

        $emp = $this->service->getLoginEmpDetailfrmUsername($this->user_name);
        $this->employee_details_id = $emp['id'];
        $this->user_organisation_id = $emp['organisation_id'];
        $this->organisation_id = $emp['organisation_id'];
        $this->userDetails = $emp['first_name'] . ' ' . $emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ( $id_from_route )
            $this->_id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s');

        $this->layout()->setVariable('userRole', $this->user_role);
        $this->layout()->setVariable('userRegion', $this->user_organisation_id);
        $this->layout()->setVariable('userType', $this->user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
    }

    /**
     * This action to use a default load function
     * @return array|ViewModel
     */
    public function indexAction() {
        $this->init();

        $tds_group = '';
        $tds_report = '';
        $organisation = $this->user_organisation_id;
        $department_id = '';
        $employee_id = '';
        $year = date('Y');
        $employeePayRollData = [];
        $employerList = [];
        $start_month = '';
        $end_month = '';

        if ( $this->user_role == 'ADMIN' ):
            $organisationList = $this->service->getTableData('organisation');
        else:
            $organisationList = $this->service->getDatabyParam('organisation', array('id' => $this->organisation_id), null);
        endif;

        if ( $this->getRequest()->isPost() ):

            $request = $this->getRequest()->getPost();

            $tds_group = $request['tds_group'];
            $tds_report = $request['tds_report'];
            $organisation_id = $request['organisation_id'];
            $department_id = $request['department_id'];
            $employee_id = $request['employee_id'];
            $year = $request['year'];
            $start_month = $request['start_month'];
            $end_month = $request['end_month'];

            if ( $tds_report == 'tds_report_salary' ) :
                $employeePayRollData = $this->service->getPayrollTableData(
                    array(
                        'where' => [
                            'pr.organisation_id' => $organisation_id,
                            'pr.employee_details' => $employee_id,
                            'year' => $year
                        ],
                        'between_month' => [
                            'start' => $start_month,
                            'end' => $end_month
                        ]
                    )
                );

                $departmentData = $this->getDepartmentAction($organisation_id);
                $departmentList = $departmentData->data;

                $employerData = $this->getEmployeeAction($department_id);
                $employerList = $employerData->data;

            elseif ( $tds_report == 'tds_report_supplier' ) :

                $employeePayRollData = $this->service->getSupplierTdsRecordsTableData(
                    array(
                        'where' => [
                            'a.supplier_id' => $employee_id,
                            'a.year' => $year
                        ],
                        'between_month' => [
                            'start' => $start_month,
                            'end' => $end_month
                        ]
                    )
                );

                $departmentList = [];

                $employerData = $this->getSupplierListAction($organisation_id);
                $employerList = $employerData->data;
            endif;

        else:
            $departmentData = $this->getDepartmentAction($this->organisation_id);
            $departmentList = $departmentData->data;

            if ( $tds_report == 'tds_report_supplier' ) :
                $employerData = $this->getSupplierListAction($this->organisation_id);
                $employerList = $employerData->data;
            endif;
        endif;

        return new ViewModel(array(
            'title' => 'Salary Slip',
            'keyphrase' => $this->keyphrase,
            'tdsGroup' => $tds_group,
            'tdsReport' => $tds_report,
            'year' => $year,
            'start_month' => $start_month,
            'end_month' => $end_month,
            'userorg' => $this->user_organisation_id,
            'organisation_id' => $organisation,
            'department_id' => $department_id,
            'employee_id' => $employee_id,
            'employeePayrollData' => $employeePayRollData,
            'payrollService' => $this->service,
            'organisationList' => $organisationList,
            'departmentList' => $departmentList,
            'employerList' => $employerList
        ));
    }

    

    /**
     * This action to use fetch all department lists by given ID
     * @param null $organisation_id
     * @return JsonModel
     */
    public function getDepartmentAction($organisation_id = null) {
        $this->init();

        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $organisation_id = $form['organisation_id'];
        endif;

        $departmentList = $this->service->getDatabyParam('departments', array('organisation_id' => $organisation_id), null);

        $departmentList = array_column($departmentList, 'department_name', 'id');

        return $this->generateAjaxResponse(true, 'All Department Lists', $departmentList);
    }

    /**
     * This action to use fetch all employer lists by given ID
     * @param null $department_id
     * @return JsonModel
     */
    public function getEmployeeAction($department_id = null) {
        $this->init();

        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $department_id = $form['department_id'];
        endif;

        $employees = $this->service->getEmployeeDetailsData(array('ed.departments_id' => $department_id));

        $arrayColumn = [];

        foreach ( $employees as $key => $value ) {
            $arrayColumn[$value['id']] = $value['first_name'] . " " . $value['middle_name'] . " " . $value['last_name'];
        }

        return $this->generateAjaxResponse(true, 'All Employer Lists', $arrayColumn);
    }

    /**
     * This action to use fetch all supplier lists by given ID
     * @param null $organisation_id
     * @return JsonModel
     */
    public function getSupplierListAction($organisation_id = null) {
        $this->init();

        if ( $this->getRequest()->isPost() ):
            $form = $this->getRequest()->getPost();
            $organisation_id = $form['organisation_id'];
        endif;

        $employees = $this->service->getDatabyParam('supplier_details', array('organisation_id' => $organisation_id), null);

        $arrayColumn = [];

        foreach ( $employees as $key => $value ) {
            $arrayColumn[$value['id']] = $value['supplier_name'];
        }

        return $this->generateAjaxResponse(true, 'All Supplier Lists', $arrayColumn);
    }

    /**
     * Common ajax response
     * @param bool $status
     * @param $msg
     * @param array $data
     * @param array $error
     * @return JsonModel
     */
    public function generateAjaxResponse($status = false, $msg, $data = [], $error = []) {
        return new JsonModel(
            array(
                'status' => $status,
                'message' => $msg,
                'data' => $data,
                'error' => $error
            )
        );
    }

    /**
     * This action to use generate PDF for salary TDS
     * @return PdfModel
     */
    public function generateTdsSalaryPdfAction() {

        $redirectRoute = 'generate-tds-report';

        $queryParams = $this->params()->fromQuery();

        if ( empty($queryParams) ):
            $this->redirect()->toRoute($redirectRoute);
        endif;

        list($organisation_id, $employee_id, $year, $start_month, $end_month, $tds_report) = $this->checkParams($queryParams, $redirectRoute);

        if ( $tds_report == 'tds_report_salary' ) :

            $employeePayRollData = $this->service->getPayrollTableData(
                array(
                    'where' => [
                        'pr.organisation_id' => $organisation_id,
                        'pr.employee_details' => $employee_id,
                        'year' => $year
                    ],
                    'between_month' => [
                        'start' => $start_month,
                        'end' => $end_month
                    ]
                )
            );

            return $this->checkEmployeeOrSuppliesDataAndCallPdf($employeePayRollData, $year, $queryParams, $tds_report);

        endif;
    }

    /**
     * This action to use generate PDF for salary TDS
     * @return PdfModel
     */
    public function generateTdsSuppliesPdfAction() {

        $redirectRoute = 'generate-tds-report';

        $queryParams = $this->params()->fromQuery();

        if ( empty($queryParams) ):
            $this->redirect()->toRoute($redirectRoute);
        endif;

        list($organisation_id, $employee_id, $year, $start_month, $end_month, $tds_report) = $this->checkParams($queryParams, $redirectRoute);

        if ( $tds_report == 'tds_report_supplier' ) :

            $employeePayRollData = $this->service->getSupplierTdsRecordsTableData(
                array(
                    'where' => [
                        'a.supplier_id' => $employee_id,
                        'a.year' => $year
                    ],
                    'between_month' => [
                        'start' => $start_month,
                        'end' => $end_month
                    ]
                )
            );

            return $this->checkEmployeeOrSuppliesDataAndCallPdf($employeePayRollData, $year, $queryParams, $tds_report);

        endif;
    }

    /**
     * @param $queryParams
     * @param $redirectRoute
     * @return array
     */
    public function checkParams($queryParams, $redirectRoute) {
        $tds_group = $this->arrayKeyExits('tds_group', $queryParams);

        $tds_report = $this->arrayKeyExits('tds_report', $queryParams);

        $organisation_id = $this->arrayKeyExits('organisation_id', $queryParams);

        $department_id = $this->arrayKeyExits('department_id', $queryParams);

        $employee_id = $this->arrayKeyExits('employee_id', $queryParams);

        $year = $this->arrayKeyExits('year', $queryParams);

        $start_month = $this->arrayKeyExits('start', $queryParams);

        $end_month = $this->arrayKeyExits('end', $queryParams);

        if ( empty($tds_group) && empty($tds_report) && empty($organisation_id) && empty($department_id)
            && empty($employee_id) && empty($year) && empty($start_month) && empty($end_month) ):
            $this->redirect()->toRoute($redirectRoute);
        endif;

        return array($organisation_id, $employee_id, $year, $start_month, $end_month, $tds_report);
    }

    /**
     * @param $employeePayRollData
     * @param $year
     * @param $queryParams
     * @param $tds_report
     * @return PdfModel
     */
    public function checkEmployeeOrSuppliesDataAndCallPdf($employeePayRollData, $year, $queryParams, $tds_report) {

        if ( !empty($employeePayRollData) ):
            $employeeName = "";
            $template_path = "";

            if ( $tds_report == "tds_report_salary" ) {
                $employeeName = $employeePayRollData[0]['first_name'] . " " . $employeePayRollData[0]['middle_name'] . " " . $employeePayRollData[0]['last_name'];

                $template_path = "generate-tds-salary-pdf.phtml";
            }

            if ( $tds_report == 'tds_report_supplier' ) {
                $employeeName = $employeePayRollData[0]['supplier_name'];

                $template_path = "generate-tds-supplies-pdf.phtml";
            }

            $filename = $this->createUrlSlug("tds " . $year . " " . $employeeName . " " . time());

            return $this->generateDownloadablePdf($filename, $employeePayRollData, $queryParams, $template_path);
        endif;
    }

    /**
     * Generate the downloadable PDF with custom data
     * @param $filename
     * @param $data
     * @param $option
     * @param $template_path
     * @return PdfModel
     */
    public function generateDownloadablePdf($filename, $data, $option, $template_path) {

        $pdf = new PdfModel();
        $pdf->setOption('fileName', $filename);
        $pdf->setOption('display', PdfModel::DISPLAY_ATTACHMENT);
        $pdf->setOption('paperSize', 'a4');
        $pdf->setOption('paperOrientation', 'landscape');

        $pdf->setTemplate('accounts/generate-tds-report/' . $template_path);

        $pdf->setVariables(array(
            'data' => $data,
            'options' => $option
        ));

        return $pdf;
    }

    /**
     * Find given key is exits or not from array and set nullable by default
     * @param $key
     * @param $array
     * @return string
     */
    public function arrayKeyExits($key, $array) {
        if ( array_key_exists("$key", $array) ) {
            return $array[$key];
        }

        return '';
    }

    /**
     * Create a slug by string
     * @param $string
     * @return string|string[]|null
     */
    public function createUrlSlug($string) {
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $string));
    }
}
