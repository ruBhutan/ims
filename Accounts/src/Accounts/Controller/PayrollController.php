<?php

namespace Accounts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use DOMPDFModule\View\Model\PdfModel;
use Zend\View\Model\ViewModel;
use Dompdf\Dompdf;
use Zend\Authentication\AuthenticationService;
use Accounts\Service\PayrollServiceInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Accounts\Form\EmployeePayrollSearchForm;
use Accounts\Form\PayrollDetails;
use Accounts\Form\SalaryReportForm;
use Accounts\Form\NetPayableForm;
use Accounts\Form\EmployeeEarningDeductionForm;

class PayrollController extends AbstractActionController {
    protected $_table;        // database table
    protected $service;
    protected $serviceLocator;
    protected $organisationTable = "organisation";
    protected $payrollTable = "payr_payroll";
    protected $employeeTable = "employee_details";
    protected $pay_struc_table = "payr_pay_structure";
    protected $temp_payroll_table = "payr_temp_payroll";
    protected $keyphrase = "RUB_IMS";
    protected $path;

    protected $user;
    protected $user_id;
    protected $username;
    protected $user_role;
    protected $userDetails;
    protected $userImage;
    protected $user_organisation_id;
    protected $financial_year;
    protected $employee_details_id;

    protected $GENERATED_LOGO_IMAGE_PATH  = 'public/img/logo.png';

    public function __construct(PayrollServiceInterface $service, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator) {
        $this->service = $service;
        $this->serviceLocator = $serviceLocator;
        $this->notificationService = $notificationService;
        $this->auditTrailService = $auditTrailService;
        $this->financial_year = $this->setFinancialYear();
        $this->path = getcwd() . '/data/payroll_slip/';
    }

    /**
     * initial set up
     * general variables are defined here
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
        $this->userDetails = $emp['first_name'] . ' ' . $emp['middle_name'].' '.$emp['last_name'];
        $this->userImage = $emp['profile_picture'];

        $id_from_route = $this->params()->fromRoute('id');
        $this->e_id = $id_from_route;
        if ($id_from_route)
            $this->_id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s');

        $this->layout()->setVariable('userRole', $this->user_role);
        $this->layout()->setVariable('userRegion', $this->user_organisation_id);
        $this->layout()->setVariable('userType', $this->user_type);
        $this->layout()->setVariable('userDetails', $this->userDetails);
        $this->layout()->setVariable('userImage', $this->userImage);
       /* if ( !isset($this->user) ) {
            $this->user = $this->identity();
            $this->user_id = $this->user->id;
            $this->username = $this->user->username;
            $this->user_role = $this->user->role;
            $this->user_organisation_id = $this->user->region;
            $emp = $this->service->getLoginEmpDetailfrmUsername($this->username);
            $this->employee_details_id = $emp['id'];
            $this->layout()->setVariable('userRole', $this->user_role);
            $this->layout()->setVariable('userRegion', $this->user_organisation_id);
        }

        $this->_created = date('Y-m-d H:i:s');
        $this->_modified = date('Y-m-d H:i:s'); */
    }

    /**
     *  Monthly pay index action
     */
    public function indexAction() {

        $form = new EmployeePayrollSearchForm();

        $this->init();

        if ( $this->user_role == 'ADMIN' ) {
            $organisationListsArray = $this->service->listAll($this->organisationTable)->toArray();
        } else {
            $organisationListsArray = $this->service->findDetails($this->organisationTable, $this->user_organisation_id)->toArray();
        }
        $employeeReport = [];
        $organisationLists = array_combine(
            array_column($organisationListsArray, 'id'),
            array_column($organisationListsArray, 'organisation_name')
        );

        $request = $this->getRequest();

        if ( $request->isGet() && $this->params()->fromQuery('submit') ) {
            $form->setData($request->getQuery());
            $post_data = $this->params()->fromQuery();
            $employeeReport = $this->service->getEmployeeList($this->employeeTable, $post_data);
        }

        return new ViewModel(array(
            'title' => 'Payroll',
            'form' => $form,
            'employeelist' => $employeeReport,
            'organisation_lists' => $organisationLists,
            'keyphrase' => $this->keyphrase,
        ));
    }


    public function payrollemployeeAction()
    {
        $form = new EmployeePayrollSearchForm();

        $this->init();

        if ( $this->user_role == 'ADMIN' ) {
            $organisationListsArray = $this->service->listAll($this->organisationTable)->toArray();
        } else {
            $organisationListsArray = $this->service->findDetails($this->organisationTable, $this->user_organisation_id)->toArray();
        }
        $employeeReport = [];
        $organisationLists = array_combine(
            array_column($organisationListsArray, 'id'),
            array_column($organisationListsArray, 'organisation_name')
        );

        $request = $this->getRequest();

        if ( $request->isGet() && $this->params()->fromQuery('submit') ) {
            $form->setData($request->getQuery());
            $post_data = $this->params()->fromQuery();
            $employeeReport = $this->service->getEmployeeList($this->employeeTable, $post_data);
        }

        return new ViewModel(array(
            'title' => 'Staff and Pay Structure',
            'form' => $form,
            'employeelist' => $employeeReport,
            'organisation_lists' => $organisationLists,
            'keyphrase' => $this->keyphrase,
        ));
    }

    public function viewemployeedetailAction() {
        $message_status = "";

        $this->init(); 

        $id_from_route = $this->params()->fromRoute('id', 0);

        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( is_numeric($id) ) {
            $empl_job_profile = current($this->service->getJobProfileData(array('jp.employee_details' => $id)));

            $empl_payroll_job_profile_id = isset($empl_job_profile['id']) ? $empl_job_profile['id'] : 0;

            $employeeDetail = $this->service->getEmpPersonalDetail($id);

            $employeePayrollStructureData = $this->service->getPayrollStrucutreData(
                array('sd.employee_details' => $id), false
            );

            $deductionData = [];
            $earningData = [];

            if ( !empty($employeePayrollStructureData) ) {
                $deductionData = array_filter($employeePayrollStructureData, function($data) {
                    return $data['deduction'] == 1;
                });

                $earningData = array_filter($employeePayrollStructureData, function($data) {
                    return $data['deduction'] == 0;
                });
            }

            if ( $this->getRequest()->isPost() ) {
                $form = $this->getRequest()->getPost(); 
                $total_earning = 0;
                $total_deduction = 0;
                $total_actual_earning = 0;
                $total_actual_deduction = 0;
                $e_dlwp = 0;
                $d_dlwp = 0;
                foreach ( $this->service->getPayrollStrucutreData(array('employee_details' => $id)) as $paydetails ):
                    if ( $paydetails['deduction'] == "1" ) {
                        if ( $paydetails['dlwp'] == 1 ):
                            $amount = $paydetails['amount'] - ($paydetails['amount'] / $form['working_days']) * $form['leave_without_pay'];
                        else:
                            $amount = $paydetails['amount'];
                        endif;
                        $final_amt = $amount = $paydetails['amount'] - $amount;
                        $final_amt = round($final_amt, 2);
                        $d_dlwp += $final_amt;
                        if ( $paydetails['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;
                        $total_deduction = $total_deduction + $amount;
                        $total_actual_deduction = $total_actual_deduction + $paydetails['amount'];
                    } else {
                        if ( $paydetails['dlwp'] == 1 ):
                            $amount = $paydetails['amount'] - ($paydetails['amount'] / $form['working_days']) * $form['leave_without_pay'];
                        else:
                            $amount = $paydetails['amount'];
                        endif;
                        $final_amt = $amount = $paydetails['amount'] - $amount;
                        $final_amt = round($final_amt, 2);
                        $e_dlwp += $final_amt;
                        if ( $paydetails['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;
                        $total_earning = $total_earning + $amount;
                        $total_actual_earning = $total_actual_earning + $paydetails['amount']; 
                    }
                endforeach;
                $leave_encashment = $form['leave_encashment'];
                $bonus = $form['bonus'];
                $net_pay = $total_actual_earning + $leave_encashment + $bonus - $total_actual_deduction - $e_dlwp - $d_dlwp;
                
                //$earning_dlwp = $total_actual_earning - $total_earning;
                //$deduction_dlwp = $total_actual_deduction - $total_deduction;
                $earning_dlwp = $e_dlwp;
                $deduction_dlwp = $d_dlwp;

                $data = array(
                    //'id' => $id,
                    'employee_details' => $id,
                    'year' => $form['year'],
                    'month' => $form['month'],
                    'working_days' => $form['working_days'],
                    'leave_without_pay' => $form['leave_without_pay'],
                    'gross' => $total_actual_earning,
                    'total_deduction' => $total_actual_deduction,
                    'bonus' => $form['bonus'],
                    'leave_encashment' => $leave_encashment,
                    'net_pay' => $net_pay,
                    'earning_dlwp' => $earning_dlwp,
                    'deduction_dlwp' => $deduction_dlwp,
                    'organisation_id' => $this->user_organisation_id,
                    'status' => isset($form['status']) ? $form['status'] : 0,
                    'author' => $this->employee_details_id,
                    'empl_payroll' => $empl_payroll_job_profile_id
                );
                
                $checkPayroll = $this->service->checkPayRollExistingOrNot($data);

                if ( !$checkPayroll ) {

                    if($data['month'] > intval(date('m')) && date('Y')){
                        $message_status = 'Failure';
                        $this->flashMessenger()->addMessage("Can't generate payroll!");
                    }else{
                        $result = $this->service->savePayrollTableData($data);

                        if ( $result > 0 ):
                            // Save "payr_pay_details" as per "payr_payroll" ID

                            $payrollDetails = $this->service->savePayrollDetail(
                                array(
                                    'data' => $employeePayrollStructureData,
                                    'payroll_id' => $result,
                                    'author' => $this->employee_details_id,
                                    'organisation_id' => $this->user_organisation_id,
                                )
                            );

                            // Create and store PDF
                            // Get data from payroll table for filename
                            $emp_data = $this->service->getEmpPersonalDetail($data['employee_details']);
                            $filename = $emp_data['emp_id'] . "-" . $data['year'] . date("F", strtotime(date("1-" . $data['month'] . "-" . $data['year']))) . '.pdf';
                            if ( !file_exists($this->path . $filename) ) {
                                $htmlViewPart = new ViewModel();
                                $htmlViewPart->setTerminal(true)
                                    ->setTemplate('accounts/payroll/generate-payroll-pdf')
                                    ->setVariables(array(
                                        'payrollService' => $this->service,
                                        'userorg' => $this->user_organisation_id,
                                        'payroll' => $this->service->getPayrollTableData($result),
                                        'logo' =>  $this->GENERATED_LOGO_IMAGE_PATH
                                    ));

                                $htmlOutput = $this->serviceLocator->get('viewrenderer')->render($htmlViewPart);
                                $dompdf = new Dompdf();
                                $dompdf->load_html($htmlOutput);
                                $dompdf->render();
                                file_put_contents($this->path . $filename, $dompdf->output());
                            }
                            $message_status = 'Success';
                            $this->flashMessenger()->addMessage(" Payroll succesfully added");
                        else:
                            $message_status = 'Failure';
                            $this->flashMessenger()->addMessage(" Error occured, Please try again!");
                        endif;
                    }
                } else {
                    $message_status = 'Failure';
                    $this->flashMessenger()->addMessage(" Payroll already exist");
                }
            }

            $employeePayrollDetail = $this->service->getEmpPayrollDetail($id, null);

            return new ViewModel(array(
                'title' => 'Add Payroll',
                'employeeDetail' => $employeeDetail,
                'payrollDetail' => $employeePayrollDetail,
                'keyphrase' => $this->keyphrase,
                'message_status' => $message_status,
                //'payroll' => $this->service->getPayrollTableData(array('pr.id' => $id, 'pr.organisation_id' => $this->user_organisation_id)),
                'payrollService' => $this->service,
                'organisation_id' => $this->user_organisation_id,
                'employee_deduction_data' => $deductionData,
                'employee_earning_data' => $earningData,
            ));
        }

        return $this->redirect()->toRoute('payroll');
    }

    public function viewsalaryreportAction() {
        $form = new SalaryReportForm();

        // $this->loginDetails();
        $employeeDetail = $employeePayrollDetail = $emtpy = [];

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $employeeDetail = $this->service->getEmpPersonalDetail($id);

        $request = $this->getRequest();
        if ( $request->isGet() && $this->params()->fromQuery('submit') ) {
            $form->setData($request->getQuery());
            $post_data = $this->params()->fromQuery();
            $employeePayrollDetail = $this->service->getEmpPayrollDetail($id, $post_data);
        } else if ( is_numeric($id) ) {
            $employeePayrollDetail = $this->service->getEmpPayrollDetail($id, $emtpy);

        } else {
            return $this->redirect()->toRoute('payroll');
        }
        return new ViewModel(array(
            'title' => 'Salary Report',
            'employeeDetail' => $employeeDetail,
            'form' => $form,
            'payrollDetail' => $employeePayrollDetail,
            'financial_year' => $this->financial_year,
            'keyphrase' => $this->keyphrase,
        ));
    }

    public function viewnetpayablereportAction() {
        $this->init();

        $form = new NetPayableForm();

        $yearlist = $this->setYear();

        $message_status = "";

        if ( $this->user_role == 'ADMIN' ) {
            $organisationListsArray = $this->service->listAll($this->organisationTable)->toArray();
        } else {
            $organisationListsArray = $this->service->findDetails($this->organisationTable, $this->user_organisation_id)->toArray();
        }

        $netPayableDetail = [];

        $organisationLists = array_combine(
            array_column($organisationListsArray, 'id'),
            array_column($organisationListsArray, 'organisation_name')
        );

        $request = $this->getRequest();

        $is_paid = "";

        if ( $request->isGet() && ($this->params()->fromQuery('submit') || $this->params()->fromQuery('mass_paid')) ) {
            $form->setData($request->getQuery());

            $post_data = $this->params()->fromQuery();

            if ( $this->params()->fromQuery('mass_paid') == true ) {
                $params = array();

                if ( isset($post_data['organisation_id']) && !empty($post_data['organisation_id']) ) {
                    $params['organisation_id'] = $post_data['organisation_id'];
                }

                if ( isset($post_data['year']) && !empty($post_data['year']) ) {
                    $params['year'] = $post_data['year'];
                }

                if ( isset($post_data['month']) && !empty($post_data['month']) ) {
                    $params['month'] = $post_data['month'];
                }

                $result = $this->service->updatePayrollStatus($params);

                if ( $result > 0 ):
                    $message_status = 'Success';
                    $this->flashMessenger()->addMessage(" Bulk payroll status updated successfully");
                else:
                    $message_status = 'Failure';
                    $this->flashMessenger()->addMessage(" Failed to bulk update payroll status");
                endif;
            }

            $netPayableDetail = $this->service->getEmpNetPayable($post_data);

            $checkStatus = array_keys(array_column($netPayableDetail, 'status'), '0');

            $is_paid = count($checkStatus) === 0 ? "true" : "false";
        }

        return new ViewModel(array(
            'title' => 'Net Payable',
            'form' => $form,
            'yearlist' => $yearlist,
            'organisation_lists' => $organisationLists,
            'netPayableDetail' => $netPayableDetail,
            'keyphrase' => $this->keyphrase,
            'is_paid' => $is_paid,
            'message_status' => $message_status
        ));
    }

    public function addempearningdeductionAction() 
    {
        $this->init();
        $form = new EmployeeEarningDeductionForm();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        $getPayHeads = $typehead_list = $data_type = $emp_pay_structure_data = [];

        $request = $this->getRequest();
        if ( $request->isPost() ) {
            $form->setData($request->getPost());
            if ( $form->isValid() ) {
                $data = $this->params()->fromPost();

                $roundup = $data['roundup'];
                if ( $roundup == 1 ):
                    $data['amount'] = round($data['amount']);
                endif;
                $dlwp = ($data['is_dlwp'] > 0) ? $data['is_dlwp'] : '0';

                $savedata = array(
                    'employee_details' => $id,
                    'pay_head' => $data['payhead'],
                    'percent' => $data['percent'],
                    'amount' => $data['amount'],
                    'dlwp' => $dlwp,
                    'ref_no' => $data['ref_no'],
                    'organisation_id' => $this->user_organisation_id,
                    'remarks' => $data['remark'],
                    'author' => $this->employee_details_id,
                    'created' => $this->_created,
                    'modified' => $this->_modified,
                );
               
                $result_lastid = $this->service->savePayStructure($savedata, $this->pay_struc_table);
                if ( $result_lastid > 0 ):
                    //changes in paystructure should affect other payheads
                    $row_data = $this->service->getPayStructureDetail($result_lastid);
                    $result1 = $this->calculatePayheadAmount($row_data);
                    if ( $result1 > 0 ):
                        $this->flashMessenger()->addMessage("New Pay head successfully added to Pay Structure");
                    else:
                        $this->flashMessenger()->addMessage("Failed to add new pay head");
                    endif;
                else:
                    $this->flashMessenger()->addMessage("Failed to add new pay head");
                endif;

                $redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
                return $this->redirect()->toUrl($redirectUrl);
            }
        }

        if ( is_numeric($id) ) {
            $employeeDetail = $this->service->getEmpPersonalDetail($id);
            $employeeDetail_id = $employeeDetail['id'];
            $jobProfileDetail = $this->service->getEmpJobProfile($employeeDetail_id);
            if(!empty($jobProfileDetail)){
                $position_title = $jobProfileDetail['position_title_id'];
                $organisation_id = $jobProfileDetail['organisation_id'];
            }else{
                $position_title = NULL;
                $organisation_id = NULL;
            }
            
            if ( $this->user_role == 'ADMIN' ) {
                $getPayHeads = $this->service->getEmpPayHeads($this->pay_struc_table, $employeeDetail_id, null);
            } else if ( $position_title == '80' ) {
                $getPayHeads = $this->service->getEmpPayHeads($this->pay_struc_table, $employeeDetail_id, '0');
            } else if ( $position_title == '95' ) {
                $getPayHeads = $this->service->getEmpPayHeads($this->pay_struc_table, $employeeDetail_id, '1');
            }

            $emp_pay_structure_data = $this->service->getPayrollStrucutreData(array('sd.employee_details' => $employeeDetail_id, 'sd.organisation_id' => $this->user_organisation_id));

            if ( isset($getPayHeads) ) {
                $type_head_id = array_column($getPayHeads, 'id');
                $type_head_name = array_column($getPayHeads, 'pay_head');
                $type_head_type = array_column($getPayHeads, 'type');
                $typehead_list = array_combine($type_head_id, $type_head_name);
                $data_type = array_combine($type_head_id, $type_head_type);
            }

            return new ViewModel(array(
                'title' => 'Net Payable',
                'payheads' => $typehead_list,
                'organisation_id' => $organisation_id,
                'employee_details' => $employeeDetail_id,
                'data_type' => $data_type,
                'emp_earn_deduct' => $emp_pay_structure_data,
                'form' => $form,
                'keyphrase' => $this->keyphrase,
            ));
        }

        return $this->redirect()->toRoute('payroll');
    }


    /*public function editempearningdeductionAction() 
    {
        $this->init();
        $form = new EmployeeEarningDeductionForm();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase); 
        $getPayHeads = $typehead_list = $data_type = $emp_pay_structure_data = [];

        $request = $this->getRequest();
        if ( $request->isPost() ) {
            $form->setData($request->getPost());
            if ( $form->isValid() ) {
                $data = $this->params()->fromPost();

                $roundup = $data['roundup'];
                if ( $roundup == 1 ):
                    $data['amount'] = round($data['amount']);
                endif;
                $dlwp = ($data['is_dlwp'] > 0) ? $data['is_dlwp'] : '0';

                $savedata = array(
                    'employee_details' => $id,
                    'pay_head' => $data['payhead'],
                    'percent' => $data['percent'],
                    'amount' => $data['amount'],
                    'dlwp' => $dlwp,
                    'ref_no' => $data['ref_no'],
                    'organisation_id' => $this->user_organisation_id,
                    'remarks' => $data['remark'],
                    'author' => $this->employee_details_id,
                    'created' => $this->_created,
                    'modified' => $this->_modified,
                );

                $result_lastid = $this->service->savePayStructure($savedata, $this->pay_struc_table);
                if ( $result_lastid > 0 ):
                    //changes in paystructure should affect other payheads
                    $row_data = $this->service->getPayStructureDetail($result_lastid);
                    $result1 = $this->calculatePayheadAmount($row_data);
                    if ( $result1 > 0 ):
                        $this->flashMessenger()->addMessage("New Pay head successfully added to Pay Structure");
                    else:
                        $this->flashMessenger()->addMessage("Failed to add new pay head");
                    endif;
                else:
                    $this->flashMessenger()->addMessage("Failed to add new pay head");
                endif;

                $redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
                return $this->redirect()->toUrl($redirectUrl);
            }
        }

        if ( is_numeric($id) ) {
            $employeeDetail = $this->service->getEmpPersonalDetail($id);
            $employeeDetail_id = $employeeDetail['id'];
            $jobProfileDetail = $this->service->getEmpJobProfile($employeeDetail_id);
            $position_title = $jobProfileDetail['position_title_id'];
            $organisation_id = $jobProfileDetail['organisation_id'];
            if ( $this->user_role == 'ADMIN' ) {
                $getPayHeads = $this->service->getEmpPayHeads($this->pay_struc_table, $employeeDetail_id, null);
            } else if ( $position_title == '80' ) {
                $getPayHeads = $this->service->getEmpPayHeads($this->pay_struc_table, $employeeDetail_id, '0');
            } else if ( $position_title == '95' ) {
                $getPayHeads = $this->service->getEmpPayHeads($this->pay_struc_table, $employeeDetail_id, '1');
            }

            $emp_pay_structure_data = $this->service->getPayrollStrucutreData(array('sd.employee_details' => $employeeDetail_id, 'sd.organisation_id' => $this->user_organisation_id));

            if ( isset($getPayHeads) ) {
                $type_head_id = array_column($getPayHeads, 'id');
                $type_head_name = array_column($getPayHeads, 'pay_head');
                $type_head_type = array_column($getPayHeads, 'type');
                $typehead_list = array_combine($type_head_id, $type_head_name);
                $data_type = array_combine($type_head_id, $type_head_type);
            }

            return new ViewModel(array(
                'title' => 'Net Payable',
                'payheads' => $typehead_list,
                'organisation_id' => $organisation_id,
                'employee_details' => $employeeDetail_id,
                'data_type' => $data_type,
                'emp_earn_deduct' => $emp_pay_structure_data,
                'form' => $form,
                'keyphrase' => $this->keyphrase,
            ));
        }

        return $this->redirect()->toRoute('payroll');
    }   */ 


    public function getslabtypeAction() {
        $this->init();
        if ( $this->getRequest()->isPost() ):
            $request = $this->getRequest()->getPost();
            $pay_head_id = $request['pay_head'];
            $employee_details = $request['employee_details']; 
            $organisation_id = $request['organisation_id'];
            $payhead_detail = $this->service->getPayHeadbyId($pay_head_id);
            $pay_head_name = $payhead_detail['pay_head'];
            $type = $payhead_detail['type'];
            $against = $payhead_detail['against'];
            $paygrup_list = $pay_slab_list = [];
            $baseamount = $minimum_pay_scale = $pay_scale = 0;

            if ( $payhead_detail['type'] == '1' && $pay_head_id == '1' ) {
                $getEmpPayScale = $this->service->getEmpJobProfile($employee_details);
                $pay_scale = $getEmpPayScale['pay_scale'];
                $position_level_id = $getEmpPayScale['position_level_id'];
                $payScaleDetail = $this->service->getPayScaleDetail($position_level_id);
                $minimum_pay_scale = str_replace(',', '', $payScaleDetail['minimum_pay_scale']);
            } elseif ( $type == 2 ) {
                if ( $against > '0' ):
                    $baseamount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id, 'pay_head' => $against), 'amount', $this->pay_struc_table);
                    if(!empty($baseamount)){
                        $baseamount = $baseamount['amount'];
                    }else{
                        $baseamount = 'Basic Pay Not Yet Defined';
                    }
                     
                elseif ( $against == '-1' ):
                    $pay_head_name = 'Gross Pay';
                    //base amount form temp table
                    $baseamount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id), 'gross', $this->temp_payroll_table);
                    if(!empty($baseamount)){
                        $baseamount = $baseamount['gross'];
                    }else{
                        $baseamount = 'Not yet defined';
                    }
                    
                elseif ( $against == '-2' ):
                    $pay_head_name = 'PIT Net Pay';
                    $gross_amount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id), 'gross', $this->temp_payroll_table);
                    $PFDed = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id, 'pay_head' => 12), 'amount', $this->pay_struc_table);
                    $GISDed = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id, 'pay_head' => 13), 'amount', $this->pay_struc_table);
                    $baseamount = $gross_amount['gross'] - $PFDed - $GISDed;
                endif;
            } elseif ( $type == 3 ) {
                if ( $against > '0' ):
                    $baseamount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id, 'pay_head' => $against), 'amount', $this->pay_struc_table);
                    $baseamount = $baseamount['amount'];
                elseif ( $against == '-1' ):
                    $pay_head_name = 'Gross Pay';
                    //base amount form temp table
                    $baseamount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id), 'gross', $this->temp_payroll_table);
                    $baseamount = $baseamount['gross'];
                elseif ( $against == '-2' ):
                    $pay_head_name = 'PIT Net Pay';
                    $gross_amount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id), 'gross', $this->temp_payroll_table); 
                    $PFDed = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id, 'pay_head' => 12), 'amount', $this->pay_struc_table); 
                    $GISDed = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $organisation_id, 'pay_head' => 13), 'amount', $this->pay_struc_table); 
                    if(!empty($gross_amount) && !empty($PFDed) && !empty($GISDed)){
                        $baseamount = $gross_amount['gross'] - ($PFDed['amount'] + $GISDed['amount']); 
                    }else{
                        $baseamount = 'No yet defined';
                    }
                    
                    $pay_slab_list = $this->service->getPaySlabList($pay_head_id);
                endif;
            } elseif ( $type == 4 ) {
                $paygrup_list = $this->service->getPayGroup($pay_head_id);
            }

            $ViewModel = new ViewModel(array(
                'employee_details' => $employee_details,
                'organisation_id' => $organisation_id,
                'payhead_detail' => $payhead_detail,
                'againstName' => $pay_head_name,
                'pay_scale' => $pay_scale,
                'minimum_pay_scale' => $minimum_pay_scale,
                'baseamount' => $baseamount,
                'pay_slab_list' => $pay_slab_list,
                'paygrup_list' => $paygrup_list,
                'type' => $type,
                'pay_head' => $pay_head_id,
            ));
            $ViewModel->setTerminal(True);
            return $ViewModel;
        endif;
        exit;
    }

    public function calculatePayheadAmount($paystructure) {
        $payhead_id = $paystructure['pay_head_id'];
        $employee_details = $paystructure['employee_details'];
        $against = $paystructure['against'];

        $payhead_detail = $this->service->getPayHeadbyId($payhead_id);

        $deduction = $payhead_detail['deduction'];
        if ( $deduction == 1 ):
            $affected_ps = $this->service->getPayStructureDetail(array('pps.employee_details' => $employee_details, 'pps.organisation_id' => $this->user_organisation_id, 'ph.against' => $against));
        else:
            $affected_ps = $this->service->getPayStructureDetail(array('pps.employee_details' => $employee_details, 'pps.organisation_id' => $this->user_organisation_id, 'ph.against' => array($against, '-1', '-2')));
        endif;

        $againstGrossPH = array();
        $againstPitNet = array();
        if ( isset($affected_ps) ):
            if ( $affected_ps['against'] == '-1' ):
                array_push($againstGrossPH, $affected_ps);
            elseif ( $affected_ps['against'] == '-2' ):
                array_push($againstPitNet, $affected_ps);
            else:
                $base_amount_ar = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $this->user_organisation_id, 'pay_head' => $affected_ps['pay_head_id']), 'amount', $this->pay_struc_table);
                $base_amount = $base_amount_ar['amount'];
            endif;

            if ( $affected_ps['type'] == 2 && $affected_ps['against'] != '-1' && $affected_ps['against'] != '-2' ):// type = 2 percentage
                $amount = ($base_amount * $affected_ps['percent']) / 100;

                if ( $affected_ps['roundup'] == 1 ):
                    $amount = round($amount);
                endif;

                $data = array(
                    'id' => $affected_ps['id'],
                    'amount' => $amount,
                    'author' => $this->employee_details_id,
                    'modified' => $this->_modified,
                );
                $result_lastid = $this->service->savePayStructure($data, $this->pay_struc_table);
            elseif ( $affected_ps['type'] == 3 && $affected_ps['against'] != '-1' && $affected_ps['against'] != '-2' ):// type = 3 slab
                $rate = 0;
                $base = 0;
                $value = 0;
                $min = 0;
                $pay_slab_list = $this->service->getPaySlabList($affected_ps['pay_head_id']);

                foreach ( $pay_slab_list as $payslab ):
                    if ( $base_amount >= $payslab['from_range'] && $base_amount <= $payslab['to_range'] ):
                        break;
                    endif;
                endforeach;

                if ( $payslab['formula'] == 1 ):
                    $rate = $payslab['rate'];
                    $base = $payslab['base'];
                    $min = $payslab['from_range'];
                    if ( $base_amount > 158701 ):
                        $amount = ((($base_amount - 83338) / 100) * $rate) + $base;
                    else:
                        $amount = (intval(($base_amount - $min) / 100) * $rate) + $base;
                    endif;
                else:
                    $amount = $payslab['value'];
                endif;

                if ( $affected_ps['roundup'] == 1 ):
                    $amount = round($amount);
                endif;

                $data = array(
                    'id' => $affected_ps['id'],
                    'amount' => $amount,
                    'author' => $this->employee_details_id,
                    'modified' => $this->_modified,
                );
                $result_lastid = $this->service->savePayStructure($data, $this->pay_struc_table);
            endif;
        endif;

        //making changes to temp payroll
        foreach ( $this->service->getTempPayrollData(array('pr.employee_details' => $employee_details, 'pr.organisation_id' => $this->user_organisation_id)) as $temp_payroll ) ;
        $total_earning = 0;
        $total_deduction = 0;
        $total_actual_earning = 0;
        $total_actual_deduction = 0;

        $paydetails_1 = $this->service->getPayStructureDetail(array('pps.employee_details' => $employee_details, 'pps.organisation_id' => $this->user_organisation_id, 'ph.deduction' => '1'));
        if ( isset($paydetails_1) ) {
            if ( $paydetails_1['dlwp'] == 1 ):
                $amount = $paydetails_1['amount'] - ($paydetails_1['amount'] / $temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
            else:
                $amount = $paydetails_1['amount'];
            endif;
            $total_deduction = $total_deduction + $amount;
            $total_actual_deduction = $total_actual_deduction + $paydetails_1['amount'];
        }

        $paydetails_0 = $this->service->getPayStructureDetail(array('pps.employee_details' => $employee_details, 'pps.organisation_id' => $this->user_organisation_id, 'ph.deduction' => '0'));
        if ( isset($paydetails_0) ) {
            if ( $paydetails_0['dlwp'] == 1 ):
                $amount = $paydetails_0['amount'] - ($paydetails_0['amount'] / $temp_payroll['working_days']) * $temp_payroll['leave_without_pay'];
            else:
                $amount = $paydetails_0['amount'];
            endif;
            $total_earning = $total_earning + $amount;
            $total_actual_earning = $total_actual_earning + $paydetails_0['amount'];
        }

        $leave_encashment = $temp_payroll['leave_encashment'];
        $bonus = $temp_payroll['bonus'];
        $net_pay = $total_earning + $leave_encashment + $bonus - $total_deduction;
        $earning_dlwp = $total_actual_earning - $total_earning;
        $deduction_dlwp = $total_actual_deduction - $total_deduction;

        $data1 = array(
            'id' => $temp_payroll['id'],
            'gross' => $total_actual_earning,
            'total_deduction' => $total_actual_deduction,
            'net_pay' => $net_pay,
            'earning_dlwp' => $earning_dlwp,
            'deduction_dlwp' => $deduction_dlwp,
            'status' => '1', // initiated
            'author' => $this->employee_details_id,
            'modified' => $this->_modified,
        );

        //Save in temp payroll table
        $result1 = $this->service->savePayStructure($data1, $this->temp_payroll_table);

        if ( $result1 ):
            if ( sizeof($againstGrossPH) > 0 ) {
                foreach ( $againstGrossPH as $aff_ps ):
                    $base_amount_data = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $this->user_organisation_id), 'gross', $this->temp_payroll_table);
                    $base_amount = $base_amount_data['gross'];
                    if ( $aff_ps['type'] == 2 ) {
                        $amount = ($base_amount * $aff_ps['percent']) / 100;
                        if ( $aff_ps['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;

                        $data = array(
                            'id' => $aff_ps['id'],
                            'amount' => $amount,
                            'author' => $this->employee_details_id,
                            'modified' => $this->_modified,
                        );
                        $result = $this->service->savePayStructure($data, $this->pay_struc_table);
                    } elseif ( $aff_ps['type'] == 3 ) {
                        $rate = 0;
                        $base = 0;
                        $value = 0;
                        $min = 0;
                        foreach ( $this->service->getPaySlabList($aff_ps['pay_head_id']) as $payslab ):
                            if ( $base_amount >= $payslab['from_range'] && $base_amount <= $payslab['to_range'] ):
                                break;
                            endif;
                        endforeach;

                        if ( $payslab['formula'] == 1 ):
                            $rate = $payslab['rate'];
                            $base = $payslab['base'];
                            $min = $payslab['from_range'];
                            if ( $base_amount > 158701 ):
                                $amount = ((($base_amount - 83338) / 100) * $rate) + $base;
                            else:
                                $amount = (intval(($base_amount - $min) / 100) * $rate) + $base;
                            endif;
                        else:
                            $amount = $payslab['value'];
                        endif;

                        if ( $aff_ps['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;

                        $data = array(
                            'id' => $aff_ps['id'],
                            'amount' => $amount,
                            'author' => $this->employee_details_id,
                            'modified' => $this->_modified,
                        );
                        $result = $this->service->savePayStructure($data, $this->pay_struc_table);
                    }
                endforeach;
            }
            if ( sizeof($againstPitNet) > 0 ) {
                foreach ( $againstPitNet as $aff_ps ):
                    $Gross_amount = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $this->user_organisation_id), 'gross', $this->temp_payroll_table);
                    $PFDed = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $this->user_organisation_id, 'pay_head' => 13), 'amount', $this->pay_struc_table);
                    $GISDed = $this->service->getEmpBaseAmount(array('employee_details' => $employee_details, 'organisation_id' => $this->user_organisation_id, 'pay_head' => 17), 'amount', $this->pay_struc_table);
                    $base_amount = $Gross_amount['gross'] - $PFDed['amount'] - $GISDed['amount'];
                    if ( $aff_ps['type'] == 2 ) {
                        $amount = ($base_amount * $aff_ps['percent']) / 100;

                        if ( $aff_ps['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;

                        $data = array(
                            'id' => $aff_ps['id'],
                            'amount' => $amount,
                            'author' => $this->employee_details_id,
                            'modified' => $this->_modified,
                        );
                        $result = $this->service->savePayStructure($data, $this->pay_struc_table);
                    } elseif ( $aff_ps['type'] == 3 ) {
                        $rate = 0;
                        $base = 0;
                        $value = 0;
                        $min = 0;
                        foreach ( $this->service->getPaySlabList($aff_ps['pay_head_id']) as $payslab ):
                            if ( $base_amount >= $payslab['from_range'] && $base_amount <= $payslab['to_range'] ):
                                break;
                            endif;
                        endforeach;

                        if ( $payslab['formula'] == 1 ):
                            $rate = $payslab['rate'];
                            $base = $payslab['base'];
                            $min = $payslab['from_range'];
                            if ( $base_amount > 158701 ):
                                $amount = ((($base_amount - 83338) / 100) * $rate) + $base;
                            else:
                                $amount = (intval(($base_amount - $min) / 100) * $rate) + $base;
                            endif;
                        else:
                            $amount = $payslab['value'];
                        endif;

                        if ( $aff_ps['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;

                        $data = array(
                            'id' => $aff_ps['id'],
                            'amount' => $amount,
                            'author' => $this->employee_details_id,
                            'modified' => $this->_modified,
                        );
                        $result = $this->service->savePayStructure($data, $this->pay_struc_table);
                    }
                endforeach;
            }
            return $result1;
        endif;
    }

    public function deletepayheadAction() {
        $this->init();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $pay_head_data = $this->service->getPayStructureDetail(array('pps.id' => $id, 'pps.organisation_id' => $this->user_organisation_id));

        $result = $this->service->deletePayhead($id);

        if ( $result > 0 ):
            $result1 = $this->calculatePayheadAmount($pay_head_data);
            if ( $result1 > 0 ):
                $this->flashMessenger()->addMessage("Payhead deleted successfully");
            else:
                $this->flashMessenger()->addMessage("Failed to delete Payhead");
            endif;
        else:
            $this->flashMessenger()->addMessage("Failed to delete Payhead");
        endif;
        $redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($redirectUrl);
    }

    public function deletepayrollAction() {
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        $employee_details = $this->service->getPayrollTableDataColumn($id, 'employee_details');
        if ( is_numeric($id) ) {
            $affectedRows = $this->service->deletePayrollData($id);
            if ( $affectedRows > 0 ) {
                $this->flashMessenger()->addMessage(" Payroll of employee deleted successfully");
            }
        }
        return $this->redirect()->toRoute('payroll', array('action' => 'viewemployeedetail', 'id' => $this->my_encrypt($employee_details, $this->keyphrase)));
    }

    public function editpayrollAction() {
        $this->init();
        $message_status = 'Success';
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( is_numeric($id) ) { 

            $payroll_detail = $this->service->getPayrollTableData(array('pr.id' => $id, 'pr.organisation_id' => $this->user_organisation_id));

            $employee_details = $this->service->getPayrollTableDataColumn($id, 'employee_details');

            if ( $payroll_detail[0]['status'] == '1' ) {
                return $this->redirect()->toRoute('payroll', array('action' => 'viewemployeedetail', 'id' => $this->my_encrypt($employee_details, $this->keyphrase)));
            }

            if ( $this->getRequest()->isPost() ) {
                $form = $this->getRequest()->getPost();
                $total_earning = 0;
                $total_deduction = 0;
                $total_actual_earning = 0;
                $total_actual_deduction = 0;
                $e_dlwp = 0;
                $d_dlwp = 0;
                foreach ( $this->service->getPayrollStrucutreData(array('employee_details' => $employee_details)) as $paydetails ):
                    if ( $paydetails['deduction'] == "1" ) {
                        if ( $paydetails['dlwp'] == 1 ):
                            $amount = $paydetails['amount'] - ($paydetails['amount'] / $form['working_days']) * $form['leave_without_pay'];
                        else:
                            $amount = $paydetails['amount'];
                        endif;
                        $final_amt = $amount = $paydetails['amount'] - $amount;
                        $final_amt = round($final_amt, 2);
                        $d_dlwp += $final_amt;
                        if ( $paydetails['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;
                        $total_deduction = $total_deduction + $amount;
                        $total_actual_deduction = $total_actual_deduction + $paydetails['amount'];
                    } else {
                        if ( $paydetails['dlwp'] == 1 ):
                            $amount = $paydetails['amount'] - ($paydetails['amount'] / $form['working_days']) * $form['leave_without_pay'];
                        else:
                            $amount = $paydetails['amount'];
                        endif;
                        $final_amt = $amount = $paydetails['amount'] - $amount;
                        $final_amt = round($final_amt, 2);
                        $e_dlwp += $final_amt;
                        if ( $paydetails['roundup'] == 1 ):
                            $amount = round($amount);
                        endif;
                        $total_earning = $total_earning + $amount;
                        $total_actual_earning = $total_actual_earning + $paydetails['amount'];
                    }
                endforeach;
                $leave_encashment = $form['leave_encashment'];
                $bonus = $form['bonus'];
                $net_pay = $total_actual_earning + $leave_encashment + $bonus - $total_actual_deduction - $e_dlwp - $d_dlwp;
                $status = isset($form['status']) ? $form['status'] : 0; 
                //$earning_dlwp = $total_actual_earning - $total_earning;
                //$deduction_dlwp = $total_actual_deduction - $total_deduction;
                $earning_dlwp = $e_dlwp;
                $deduction_dlwp = $d_dlwp;
                $data = array(
                    'id' => $id,
                    'employee_details' => $employee_details,
                    'year' => $form['year'],
                    'month' => $form['month'],
                    'working_days' => $form['working_days'],
                    'leave_without_pay' => $form['leave_without_pay'],
                    'gross' => $total_actual_earning,
                    'total_deduction' => $total_actual_deduction,
                    'bonus' => $form['bonus'],
                    'leave_encashment' => $leave_encashment,
                    'net_pay' => $net_pay,
                    'earning_dlwp' => $earning_dlwp,
                    'deduction_dlwp' => $deduction_dlwp,
                    'organisation_id' => $this->user_organisation_id,
                    'status' => $status,
                    'author' => $this->employee_details_id,
                    'empl_payroll' => $payroll_detail[0]['empl_payroll']
                );
                $result = $this->service->savePayrollTableData($data);

                if ( $result > 0 ):

                    $employeePayrollStructureData = $this->service->getPayrollStrucutreData(
                        array('sd.employee_details' => $payroll_detail[0]['employee_details']), false
                    );

                    $payrollDetails = $this->service->savePayrollDetail(
                        array(
                            'data' => $employeePayrollStructureData,
                            'payroll_id' => $id,
                            'author' => $this->employee_details_id,
                            'organisation_id' => $this->user_organisation_id,
                        )
                    );

                    $this->flashMessenger()->addMessage(" Payroll succesfully updated");
                else:
                    $message_status = 'Failure';
                    $this->flashMessenger()->addMessage(" Failed to update Payroll");
                endif;
            }

            return new ViewModel(array(
                'title' => 'Edit Pay roll',
                'message_status' => $message_status,
                'id_from_route' => $id_from_route,
                'payroll' => $payroll_detail,
                'payrollService' => $this->service,
                'organisation_id' => $this->user_organisation_id,
                'keyphrase' => $this->keyphrase,
            ));
        }
        return $this->redirect()->toRoute('payroll');
    }

    public function viewpayrollreportAction() {
        $this->init();
        $message_status = 'Success';
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( is_numeric($id) ) {

            return array(
                'message_status' => $message_status
            );
        }
        return $this->redirect()->toRoute('payroll');
    }

    public function payslipAction() {
        $this->init();
        if ( $this->getRequest()->isPost() ):
            $request = $this->getRequest()->getPost();
            $year = $request['year'];
            $month = $request['month'];
            $organisation = $request['organisation'];
            $employee_details = $request['employee_details'];
        else:
            $employee_details = ''; //set default employee to -1 meaning all employee
            $organisation = $this->user_organisation_id; //set default organisation to -1 meaning all employee
            $month = date('m');
            $year = date('Y');
        endif;
        return new ViewModel(array(
            'title' => 'Salary Slip',
            'keyphrase' => $this->keyphrase,
            'year' => $year,
            'month' => $month,
            'userorg' => $this->user_organisation_id,
            'organisation_id' => $organisation,
            'employee_details' => $employee_details,
            'payrollService' => $this->service
        ));
    }

    public function generatePayrollPDFAction() {
        $this->init();
        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);
        
        $payroll = $this->service->getPayrollTableData($id);

        if ( $payroll ) { 
            $filename = $payroll[0]['emp_id'] . "-" . $payroll[0]['year'] . date("F", strtotime(date("1-" . $payroll[0]['month'] . "-" . $payroll[0]['year']))) . '.pdf';

            if ( !file_exists($this->path . $filename) ) {
                $htmlViewPart = new ViewModel();
                $htmlViewPart->setTerminal(true)
                    ->setTemplate('accounts/payroll/generate-payroll-pdf')
                    ->setVariables(array(
                        'id' => $id,
                        'payrollService' => $this->service,
                        'userorg' => $this->user_organisation_id,
                        'payroll' => $payroll,
                        'logo' =>  $this->GENERATED_LOGO_IMAGE_PATH
                    ));

                $htmlOutput = $this->serviceLocator->get('viewrenderer')->render($htmlViewPart);
                $dompdf = new Dompdf();
                $dompdf->load_html($htmlOutput);
                $dompdf->render();
                file_put_contents($this->path . $filename, $dompdf->output());
            }

            $file = $this->path . $filename;
            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new \Zend\Http\Headers();
            $headers->addHeaders(array(
                'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => filesize($file),
                'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
                'Cache-Control' => 'must-revalidate',
                'Pragma' => 'public'
            ));
            $response->setHeaders($headers);

            return $response;
        }
        return $this->redirect()->toRoute('payroll', array('action' => 'payslip'));
    }

    public function getemployeeAction() {
        $this->init();
        $form = $this->getRequest()->getPost();

        $organisation_id = $form['organisation'];
        //$employees = $this->service->getEmployeeDetailsData(array('ed.organisation_id' => $organisation_id, 'ed.status' => array(1,4,5)));
        $employees = $this->service->getEmployeeDetailsData(array('ed.organisation_id' => $organisation_id));

        $emp = "<option value='-1'>All</option>";
        foreach ( $employees as $employee ) {
            $emp .= "<option value='" . $employee['id'] . "'>" . $employee['first_name'] . " " . $employee['middle_name'] . " " . $employee['last_name'] . " (" . $employee['emp_id'] . ")</option>";
        }

        echo json_encode(array(
            'emp' => $emp,
        ));
        exit;
    }


    public function bulkaddAction() 
    {
        $message_status = 'Success'; 

        $this->init(); 
        $payroll_ids = $added_payroll_list = $ids_added = $ids_fail = array();
        $no_pay_struct = 0;
        if ($this->getRequest()->isPost()) {
            $form = $this->getRequest()->getPost();
            $year = $form['year'];
            $month = $form['month'];
            $organisation = $form['organisation'];
            $working_days = $form['working_days'];
            $allEmployees = $this->service->getEmployeeDetailsData(array('organisation_id' => intval($form['organisation'])));

            if($month > intval(date('m')) && date('Y')){
                $message_status = 'Failure';
                $this->flashMessenger()->addMessage("Can't generate payroll");
            }
            
            else if (count($allEmployees) === 0) {
                $message_status = 'Failure';
                $this->flashMessenger()->addMessage(" No employees found under this organisation");
            } else {
                $totalInsertedPayrolls = [];
                foreach ($allEmployees as $employee_details) {
                    $total_earning = $total_deduction = $total_actual_earning = $total_actual_deduction = $e_dlwp = $d_dlwp = 0;
                    $pay_structure_detail = $this->service->getPayrollStrucutreData(array('employee_details' => $employee_details['id']));

                    if ($pay_structure_detail):
                        foreach ($pay_structure_detail as $paydetails):
                            if ($paydetails['deduction'] == "1") {
                                if ($paydetails['dlwp'] == 1):
                                    $amount = $paydetails['amount'] - ($paydetails['amount'] / $form['working_days']) * $form['leave_without_pay'];
                                else:
                                    $amount = $paydetails['amount'];
                                endif;
                                $final_amt = $amount = $paydetails['amount'] - $amount;
                                $final_amt = round($final_amt, 2);
                                $d_dlwp += $final_amt;
                                if ($paydetails['roundup'] == 1):
                                    $amount = round($amount);
                                endif;
                                $total_deduction = $total_deduction + $amount;
                                $total_actual_deduction = $total_actual_deduction + $paydetails['amount'];
                            } else {
                                if ($paydetails['dlwp'] == 1):
                                    $amount = $paydetails['amount'] - ($paydetails['amount'] / $form['working_days']) * $form['leave_without_pay'];
                                else:
                                    $amount = $paydetails['amount'];
                                endif;
                                $final_amt = $amount = $paydetails['amount'] - $amount;
                                $final_amt = round($final_amt, 2);
                                $e_dlwp += $final_amt;
                                if ($paydetails['roundup'] == 1):
                                    $amount = round($amount);
                                endif;
                                $total_earning = $total_earning + $amount;
                                $total_actual_earning = $total_actual_earning + $paydetails['amount'];
                            }
                        endforeach;
                        $leave_without_pay = $leave_encashment = $bonus = 0; // Set leave encashment and bonus to default value ie. 0
                        //$working_days = cal_days_in_month(CAL_GREGORIAN, $form['month'], $form['year']);
                        $net_pay = $total_actual_earning + $leave_encashment + $bonus - $total_actual_deduction - $e_dlwp - $d_dlwp;
                        //$earning_dlwp = $total_actual_earning - $total_earning;
                        //$deduction_dlwp = $total_actual_deduction - $total_deduction;
                        $earning_dlwp = $e_dlwp;
                        $deduction_dlwp = $d_dlwp;

                        $employeeJobProfile = current($this->service->getJobProfileData(array('jp.employee_details' => $employee_details['id'])));
                        $jobProfileId = isset($employeeJobProfile['id']) ? $employeeJobProfile['id'] : 0;
                        if ($jobProfileId != 0) {
                            $data = array(
                                'employee_details' => $employee_details['id'],
                                'year' => $form['year'],
                                'month' => $form['month'],
                                'working_days' => $working_days,
                                'leave_without_pay' => $leave_without_pay,
                                'gross' => $total_actual_earning,
                                'total_deduction' => $total_actual_deduction,
                                'bonus' => $bonus,
                                'leave_encashment' => $leave_encashment,
                                'net_pay' => $net_pay,
                                'earning_dlwp' => $earning_dlwp,
                                'deduction_dlwp' => $deduction_dlwp,
                                'organisation_id' => $form['organisation'],
                                'status' => 0,
                                'author' => $this->employee_details_id,
                                'empl_payroll' => $jobProfileId
                            );

                            $checkPayroll = $this->service->checkPayRollExistingOrNot($data);
                            if (!$checkPayroll) {
                                $result = $this->service->savePayrollTableData($data);
                                $emp_data = $this->service->getEmpPersonalDetail($data['employee_details']);
                                $filename = $emp_data['emp_id'] . "-" . $data['year'] . date("F", strtotime(date("1-" . $data['month'] . "-" . $data['year']))) . '.pdf';
                                if (!file_exists($this->path . $filename)) {
                                    $htmlViewPart = new ViewModel();
                                    $htmlViewPart->setTerminal(true)
                                            ->setTemplate('accounts/payroll/generate-payroll-pdf')
                                            ->setVariables(array(
                                                'payrollService' => $this->service,
                                                'userorg' => $this->user_organisation_id,
                                                'payroll' => $this->service->getPayrollTableData($result),
                                                'logo' =>  $this->GENERATED_LOGO_IMAGE_PATH
                                    ));

                                    $htmlOutput = $this->serviceLocator->get('viewrenderer')->render($htmlViewPart);
                                    $dompdf = new Dompdf();
                                    $dompdf->load_html($htmlOutput);
                                    $dompdf->render();
                                    file_put_contents($this->path . $filename, $dompdf->output());
                                }

                                array_push($payroll_ids, $result);
                                array_push($ids_added, $employee_details['id']);
                                if ($result > 0) {
                                    $totalInsertedPayrolls[] = $result;
                                }
                            } else {
                                array_push($ids_fail, $employee_details['id']);
                            }
                        } else {
                            array_push($ids_fail, $employee_details['id']);
                        }
                    else :
                        $no_pay_struct ++;
                    endif;
                }
                if ($payroll_ids) {
                    $added_payroll_list = $this->service->getPayrollTableData(array('pr.id' => $payroll_ids));
                }
                $message_status = (count($totalInsertedPayrolls) > 0) ? 'Success' : 'Failure';
                if(count($allEmployees) == $no_pay_struct){
                    $message = 'Information missing for certain employees.';
                }else{
                    $message = (count($totalInsertedPayrolls) === 0) ? " Employee Payroll of " . date('F', mktime(0, 0, 0, $form['month'], 1)) . "-{$form['year']} was already added" : (($message_status === 'Success' && count($allEmployees) === count($totalInsertedPayrolls)) ? ' Payroll of all employees are added succesfully' : ' Payroll added for ' . count($totalInsertedPayrolls) . ' employee\'s out of ' . count($allEmployees));
                    $message .= ($no_pay_struct > 0) ? ' and Information missing for certain employees.' : '' ;
                }
                $this->flashMessenger()->addMessage($message);
            }
        } else {
            $year = date('Y');
            $month = date('m');
            $organisation = 1;
            $working_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        return array(
            'message_status' => $message_status,
            'payrollService' => $this->service,
            'month' => $month,
            'year' => $year,
            'organisation' => $organisation,
            'working_days' => $working_days,
            'organisation_id' => $this->user_organisation_id,
            'added_payroll_list' => $added_payroll_list,
            'keyphrase' => $this->keyphrase,
            'ids_added' => $ids_added,
            'ids_fail' => $ids_fail,
        );
    }



    function my_encrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('BF-CFB'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'BF-CFB', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return bin2hex(base64_encode($encrypted . '::' . $iv));
    }

    public function my_decrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);

        $len = strlen($data);
        if ( $len % 2 ) {
            return "ERROR";
        } else {
            // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
            list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
            return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
        }
    }

    public function setFinancialYear() {
        $financialYear = [];

        $currentYear = date('Y') - 1;

        for ( $i = 0; $i < 5; $i++ ) {
            $financialYear[($currentYear + $i - 1) . "-" . ($currentYear + $i)] = ($currentYear + $i - 1) . "-" . ($currentYear + $i);
        }

        return $financialYear;
    }

    public function setYear() {
        $year = [];

        $currentYear = date('Y') + 2;
        $earliest_year = 1990;

        foreach ( range($currentYear, $earliest_year) as $i ) {
            $year[$i] = $i;
        }

        return $year;
    }
}
