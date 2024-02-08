<?php

namespace StudentStipend\Controller;

use StudentStipend\Form\AddBulkStudentStipendForm;
use StudentStipend\Form\AddStudentStipendForm;
use StudentStipend\Form\pdfStudentStipendForm;
use StudentStipend\Model\StudentStipend;
use StudentStipend\Service\StudentStipendServiceInterface;
use StudentStipend\Form\StudentStipendSearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DOMPDFModule\View\Model\PdfModel;

class StudentStipendController extends AbstractActionController {

    protected $service;
    protected $serviceLocator;
    protected $keyphrase = "RUB_IMS";
    protected $table_name = "student_stipend";
    protected $messageStatus = "Success";
    protected $login_user;
    protected $student_details;
    protected $student_fee_details;
    protected $username;
    protected $userrole;
    protected $userregion;
    protected $userDetails;
    protected $userImage;
    protected $usertype;
    protected $check_role = "ADMIN";
    protected $organisation_id;

    public function __construct(StudentStipendServiceInterface $service, $serviceLocator) {
        $this->service = $service;

        $this->serviceLocator = $serviceLocator;

        $this->login_user = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();

        $this->username = $this->login_user['username'];

        $this->userrole = $this->login_user['role'];

        $this->usertype = $this->login_user['user_type_id'];

        $this->userregion = $this->login_user['region'];

        if ( $this->usertype == 1 ) {
            $empData = $this->service->getUserDetailsId($tableName = 'employee_details', $this->username);

            foreach ( $empData as $emp ) {
                $this->employee_details_id = $emp['id'];
            }
        } else if ( $this->usertype == 2 ) {
            $stdData = $this->service->getUserDetailsId($tableName = 'student', $this->username);

            foreach ( $stdData as $std ) {
                $this->student_id = $std['id'];
            }
        }

        if ( $this->usertype == 1 ) {
            $organisationID = $this->service->getOrganisationId($tableName = 'employee_details', $this->username);

            foreach ( $organisationID as $organisation ) {
                $this->organisation_id = $organisation['organisation_id'];
            }
        } else if ( $this->usertype == 2 ) {
            $stdOrganisationID = $this->service->getOrganisationId($tableName = 'student', $this->username);

            foreach ( $stdOrganisationID as $organisation ) {
                $this->organisation_id = $organisation['organisation_id'];
            }
        }

        $this->userDetails = $this->service->getUserDetails($this->username, $this->usertype);

        $this->userImage = $this->service->getUserImage($this->username, $this->usertype);
    }

    public function loginDetails() {
        $this->layout()->setVariable('userRole', $this->userrole);

        $this->layout()->setVariable('userRegion', $this->userregion);

        $this->layout()->setVariable('userType', $this->usertype);

        $this->layout()->setVariable('userDetails', $this->userDetails);

        $this->layout()->setVariable('userImage', $this->userImage);
    }

    public function indexAction() {
        $this->loginDetails();

        $organisationLists = [];
        $studentList = array();
        $stdName = NULL;
        $stdId = NULL;
        $organisation_id = NULL;

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $form = new StudentStipendSearchForm();

        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $form->setData($request->getPost());

            $stdName = $request->getPost('student_name');

            $stdId = $request->getPost('student_id');

            $organisation_id = $request->getPost('organisation_id');

            if ( $this->userrole === $this->check_role ) {
                $studentList = $this->service->getStudentListsToAdmin($stdName, $stdId, $organisation_id);
            } else {
                $studentList = $this->service->getStudentLists($stdName, $stdId, $this->organisation_id);
            }
        }

        $sql = "SELECT * FROM organisation";

        if ( $this->userrole != $this->check_role ) {
            $sql .= ' WHERE id = ' . $this->organisation_id;
        }

        $statement = $dbAdapter->query($sql);

        $result = $statement->execute();

        foreach ( $result as $res ) {
            $organisationLists[$res['id']] = $res['organisation_name'];
        }

        return new ViewModel(array(
            'form' => $form,
            'stdName' => $stdName,
            'stdId' => $stdId,
            'studentList' => $studentList,
            'organisation_id' => $this->organisation_id,
            'organisation_lists' => $organisationLists,
            'usertype' => $this->usertype,
            'keyphrase' => $this->keyphrase,
        ));
    }

    public function addAction() {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);

        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( is_numeric($id) ) {
            $form = new AddStudentStipendForm();

            $studentStipendModel = new StudentStipend();

            $form->bind($studentStipendModel);

            $studentDetails = $this->service->getStdPersonalDetails($id);

            $studentStipendList = $this->service->listStudentStipendList($id, null);

            $message_status = null;

            for ( $i = (date('Y') - 5); $i <= (date('Y') + 5); $i++ ) {
                $year[$i] = $i;
            }

            for ( $i = 1; $i <= 12; $i++ ) {
                $month[$i] = date('F', strtotime("2020-" . $i));
            }

            $request = $this->getRequest();

            if ( $request->isPost() ) {
                $form->setData($request->getPost());

                if ( $form->isValid() ) {
                    try {
                        $data = $request->getPost()->toArray();

                        $isExists = $this->service->isStudentStipendsPaid($data['AddStudentStipend']);

                        if ( !$isExists ) {
                            $this->service->saveStudentStipendDetails($studentStipendModel);

                            $message_status = 'Success';

                            $this->flashMessenger()->addMessage(' Student stipend successfully added');

                            $studentStipendList = $this->service->listStudentStipendList($id, null);
                            $form->setData(array('AddStudentStipend' => array(
                                    'year' => '',
                                    'month' => '',
                                    'stipend' => '',
                                    'h_r' => '',
                                    'ebill' => '',
                                    'net_amount' => '',
                                ))
                            );

                        } else {
                            $message_status = 'Failure';
                            $this->flashMessenger()->addMessage(' Student stipend already exists');
                        }
                    } catch ( \Exception $e ) {
                        $message_status = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                    }
                }
            }

            return array(
                'form' => $form,
                'studentDetails' => current($studentDetails->toArray()),
                'year' => $year,
                'month' => $month,
                'studentStipendList' => $studentStipendList,
                'organisation_id' => $this->organisation_id,
                'student_id' => $id,
                'keyphrase' => $this->keyphrase,
                'message_status' => $message_status,
            );
        }

        return $this->redirect()->toRoute('student-stipend');
    }

    public function editAction() {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);

        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( is_numeric($id) ) {

            $studentStipendDetail = current($this->service->listStudentStipendList(null, $id)->toArray());

            if ( empty($studentStipendDetail) ) {
                return $this->notFoundAction();
            }

            $form = new AddStudentStipendForm();

            $studentStipendModel = new StudentStipend();

            $form->bind($studentStipendModel);

            $studentDetails = $this->service->getStdPersonalDetails($studentStipendDetail['student_id']);

            $message_status = null;

            for ( $i = (date('Y') - 5); $i <= (date('Y') + 5); $i++ ) {
                $year[$i] = $i;
            }

            for ( $i = 1; $i <= 12; $i++ ) {
                $month[$i] = date('F', strtotime("2020-" . $i));
            }

            $request = $this->getRequest();

            if ( $request->isPost() ) {
                $form->setData($request->getPost());

                if ( $form->isValid() ) {
                    try {
                        $data = $request->getPost()->toArray();

                        $isExists = $this->service->isStudentStipendsPaid($data['AddStudentStipend']);

                        if ( !$isExists ) {
                            $this->service->saveStudentStipendDetails($studentStipendModel);

                            $message_status = 'Success';

                            $this->flashMessenger()->addMessage(' Student stipend successfully updated');

                            $studentStipendDetail = current($this->service->listStudentStipendList(null, $id)->toArray());

                        } else {
                            $message_status = 'Failure';
                            $this->flashMessenger()->addMessage(' Student stipend already exists');
                        }
                    } catch ( \Exception $e ) {
                        $message_status = 'Failure';
                        $this->flashMessenger()->addMessage($e->getMessage());
                    }
                }
            }

            return array(
                'form' => $form,
                'studentDetails' => current($studentDetails->toArray()),
                'year' => $year,
                'month' => $month,
                'organisation_id' => $this->organisation_id,
                'student_id' => $studentStipendDetail['student_id'],
                'id' => $id,
                'keyphrase' => $this->keyphrase,
                'message_status' => $message_status,
                'studentStipendDetail' => $studentStipendDetail
            );
        }

        return $this->redirect()->toRoute('student-stipend');
    }

    public function deleteAction() {
        $this->loginDetails();

        $id_from_route = $this->params()->fromRoute('id', 0);
        $id = $this->my_decrypt($id_from_route, $this->keyphrase);

        if ( is_numeric($id) ) {

            $studentStipendDetail = current($this->service->listStudentStipendList(null, $id)->toArray());

            if ( empty($studentStipendDetail) ) {
                return $this->notFoundAction();
            }

            try {

                $this->service->deleteStudentStipend($id);

                $this->flashMessenger()->addMessage(' Student Stipend successfully deleted');

                return $this->redirect()->toRoute('student-stipend', array(
                    'action' => 'add',
                    'id' => $this->my_encrypt($studentStipendDetail['student_id'], $this->keyphrase)
                ));
            } catch ( \Exception $e ) {
                $message_status = 'Failure';
                $this->flashMessenger()->addMessage($e->getMessage());
            }
        }

        return $this->redirect()->toRoute('student-stipend');
    }

    public function bulkAction() {
        $this->loginDetails();

        $organizationSelectData = [];

        $form = new AddBulkStudentStipendForm();

        $message = NULL;

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql = "SELECT * FROM organisation";
        if ( $this->userrole != $this->check_role ) {
            $sql .= ' WHERE id = ' . $this->organisation_id;
        }
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        foreach ( $result as $res ) {
            $organizationSelectData[$res['id']] = $res['organisation_name'];
        }

        for ( $i = (date('Y') - 5); $i <= (date('Y') + 5); $i++ ) {
            $year[$i] = $i;
        }

        for ( $i = 1; $i <= 12; $i++ ) {
            $month[$i] = date('F', strtotime("2020-" . $i));
        }

        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $form->setData($request->getPost());

            if ( $form->isValid() ) {

                $data = $request->getPost()->toArray();

                try {
                    $results = $this->service->generateBulkStudentStipend($data);

                    if ( $results ) {
                        $this->flashMessenger()->addMessage(' Students stipend are successfully added');
                    } else if ( $results === 0 ) {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage(' Stipend are already added in all students');
                    } else {
                        $message = 'Failure';
                        $this->flashMessenger()->addMessage(' Some of the students Stipend are already added');
                    }
                } catch ( \Exception $ex ) {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage($ex->getMessage());
                }
            }
        }

        return array(
            'form' => $form,
            'message' => $message,
            'organizationSelectData' => $organizationSelectData,
            'year' => $year,
            'month' => $month,
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

    public function pdfAction() {
        $this->loginDetails();

        $organizationSelectData = [];

        $form = new pdfStudentStipendForm();

        $message = NULL;

        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $sql = "SELECT * FROM organisation";
        if ( $this->userrole != $this->check_role ) {
            $sql .= ' WHERE id = ' . $this->organisation_id;
        }
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        foreach ( $result as $res ) {
            $organizationSelectData[$res['id']] = $res['organisation_name'];
        }

        for ( $i = (date('Y') - 5); $i <= (date('Y') + 5); $i++ ) {
            $year[$i] = $i;
        }

        for ( $i = 1; $i <= 12; $i++ ) {
            $month[$i] = date('F', strtotime("2020-" . $i));
        }

        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $form->setData($request->getPost());

            if ( $form->isValid() ) {

                $data = $request->getPost()->toArray();

                $studentStipend = $this->service->getStudentStipendListByFilter($data);

                if ( $studentStipend ) {
                    $filename = time() . '-' . $data['organisation_id'] . '-' . $data['year'] . '-' . $data['month'];

                    //TODO:: Only pdf generation
                    ini_set('memory_limit', '2048M');
                    ini_set('max_execution_time', '1000');

                    $htmlViewPart = new ViewModel();
                    $htmlViewPart->setTemplate('student-stipend/student-stipend/pdf-table.phtml')
                        ->setTerminal(true)
                        ->setVariables([
                            'studentStipend' => $studentStipend,
                            'organisation_name' => $organizationSelectData[$data['organisation_id']],
                            'year' => $data['year'],
                            'month' => $data['month'],
                        ]);
                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                    $html = $viewRender->render($htmlViewPart);

//                    print_r($html);
//                    exit();

                    /* TCPDF use to generate PDF file and download */

                    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                    $pdf->AddPage();
                    $pdf->writeHTML($html);
                    $pdf->Output($filename . '.pdf', 'D');

                } else {
                    $message = 'Failure';
                    $this->flashMessenger()->addMessage('No record found to generate PDF');
                }
            }
        }

        return array(
            'form' => $form,
            'message' => $message,
            'organizationSelectData' => $organizationSelectData,
            'year' => $year,
            'month' => $month,
        );
    }
}
