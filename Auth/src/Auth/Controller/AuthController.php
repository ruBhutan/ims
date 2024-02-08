<?php

/**
 * @author eDruk ICT <edruk@edruk.com.bt>
 * @link http://web.edruk.com.bt
 */

namespace Auth\Controller;

use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\View\Model\ViewModel;
use DateTime;
use Zend\Authentication\Result;
use User\Entity\User;
use User\Entity\Applicant;
/**
 * Auth Controller
 */
class AuthController extends AbstractActionController
{
    protected $serviceLocator;
    protected $emailService;
    protected $auditTrailService;
	
    public function __construct($serviceLocator, AuditTrailServiceInterface $auditTrailService)
    {
        $this->serviceLocator = $serviceLocator;
        $this->emailService = $serviceLocator->get('Application\Service\EmailService');
        $this->auditTrailService = $auditTrailService;
    }
    
    /**
     * User viewLogin Action
     * URL : /auth/viewLogin
     */
    public function viewLoginAction()
    {
        $this->layout('layout/empty');
        return new ViewModel();
    }
    /**
     * User Login Action
     * URL : /auth/login
     */
    public function loginAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost();
                $authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');
                try{
                   /* $em = $this->serviceLocator->get('doctrine.entitymanager.orm_default');
                    $userEntity = $em->getRepository('User\Entity\User')->findOneBy(array('email' => $data['email']));
                    if ($userEntity && strlen($userEntity->activationToken) > 0) {
                        $this->auditTrailService->saveAuditTrail("Login", "USERS", null, "FAILED");
                        $tmpdata['status'] = 'error';
                        $tmpdata['message'] = 'Your account is not activated. Please click on the link sent in mail to activate your account';
                    } else { */
                        $adapter = $authService->getAdapter();
                        $adapter->setIdentityValue($data['username']);
                        $adapter->setCredentialValue(md5($data['password']));

                        $authResult = $authService->authenticate();
                        if ($authResult->isValid()) {
                            $identity = $authResult->getIdentity();
                            $authService->getStorage()->write($identity);

                            $user = $this->authPlugin()->getUserAttributes();
                            $postData['lastLogin'] = new DateTime("NOW");

                            $this->auditTrailService->saveAuditTrail("Login", "USERS", null, "SUCCESS");
			    
                            $tmpdata['status'] = 'success';
							             //return $this->redirect()->toRoute('index');
                            $tmpdata['message'] = 'Welcome, You have successfully logged in and Thank You';
			    $this->auditTrailService->saveLastLogin($data['username']);
                        } else {
                            switch ($authResult->getCode()) {
                                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                                        $tmpdata['message'] = "Sorry, Invalid Username OR Password"; //User doesn't exist in system message
                                        break;
                                    case Result::FAILURE_CREDENTIAL_INVALID:
                                        //$this->auditTrailService->saveAuditTrail("Login", "Login", null, "failed");
                                        $tmpdata['message'] = "Sorry, Invalid Username OR Password."; //Invalid Password message
                                        break;
                                    default:
                                        $tmpdata['message'] = 'Sorry, Invalid Username OR Password.'; //Invalid Username
                                        break;
                                }
                            $tmpdata['status'] = 'error';
                        }
                    //}
                } catch (\Exception $ex) {
                    $tmpdata['status'] = 'error';
                    $tmpdata['message'] = $ex->getMessage();
                    return $this->getResponse()->setContent(json_encode($tmpdata));
                }
               
                return $this->getResponse()->setContent(json_encode($tmpdata));
            }
            return $this->redirect()->toUrl('/');
        } else {
            $pathVar = $this->params()->fromRoute('path', 0);
            if (!empty($pathVar))
                $this->redirect()->toRoute('index', array("data" => ''), array("query" => array("path" => urldecode($pathVar))));
            else
                return $this->redirect()->toUrl('/');
        }
    }

    public function viewRegisterAction()
    {
        $this->layout('layout/empty');
        return new ViewModel();
    }
    
    public function authTokenAction()
    {
      $authToken = $this->params()->fromRoute('id');
      $em = $this->serviceLocator->get('doctrine.entitymanager.orm_default');
      $userEntity = $em->getRepository('User\Entity\Applicant')->findOneBy(array('activationToken' => $authToken));
      if ($userEntity) {
        $userEntity->activationToken = '';
        $em->persist($userEntity);
        $em->flush();
        $this->layout('layout/empty');
        return new ViewModel();
      } else {
        exit('Activation key is not matched. Please try again with exact link sent in mail.');
      }
    }
    
    public function registerAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost();
                $em = $this->serviceLocator->get('doctrine.entitymanager.orm_default');
                try {
                    $userEntity = $em->getRepository('User\Entity\Applicant')->findOneBy(array('email' => $data['email']));
                    if (!$userEntity) {
                        $userEntity1 = $em->getRepository('User\Entity\Applicant')->findOneBy(array('cid' => $data['cid']));
                        if(!$userEntity1){
                            //$data['password'] = md5($data['password']);
                          $data['activationToken'] = $activationToken = md5(rand());
                          $userEntity = new Applicant();
                          $userEntity->populate($data);
                          $em->persist($userEntity);
                          $em->flush();
                          
                          if($data['email']){
                                $randomString = $this->getRandomString(6);
                                $password = md5($randomString);
                                $applicantEntity = new \User\Entity\User;
                                $applicantEntity->username = $data['email'];
                                $applicantEntity->password = $password;
                                $applicantEntity->role = 'JOB_APPLICANT';
                                $applicantEntity->region = 1;
                                $applicantEntity->user_type_id = 4;
                                $applicantEntity->user_status_id = 0;
                                $em->persist($applicantEntity);
                                $em->flush();
                          }
                          
                          $messageTitle = 'Job Applicant Registration';
                          $messageBody = '<h3>To login, please use your provided Email ID as username and below generated value as password.</h3> <br>'. 'Password:'.'<b>'.$randomString.'</b>';
                          $response['status'] = 'success';
                          $response['redirect'] = $this->url()->fromRoute('auth', ['action'=>'view-login'], ['force_canonical'=>true]);
                          $response['message'] = 'Successfully registered. We have sent the password to login to the system. Please login to your provided email and check for password.';
                        $this->emailService->sendMailer($data['email'], $messageTitle, $messageBody);
                            }
                            else {
                              $response['status'] = 'error';
                              $response['message'] = 'CID/ Passpord already exist. Please use your own CID or Passport';
                    } 
                }
                    else {
                      $response['status'] = 'error';
                      $response['message'] = 'Email already exists. Please register with other email or valid email id';
                    }
                } catch (\Exception $ex) {
                    $response['status'] = 'error';
                    $response['message'] = $ex->getMessage();
                }
                return $this->getResponse()->setContent(json_encode($response));
            }
            return $this->redirect()->toUrl('/');
        } else {
            $pathVar = $this->params()->fromRoute('path', 0);
            if (!empty($pathVar))
                $this->redirect()->toRoute('index', array("data" => ''), array("query" => array("path" => urldecode($pathVar))));
            else
                return $this->redirect()->toUrl('/');
        }
    }


    public function forgotPasswordAction()
    {
      $this->layout('layout/empty');
      return new ViewModel();
    }


    public function passwordAction()
    { 
        if ( !$this->getRequest()->isXmlHttpRequest() ) {
            $pathVar = $this->params()->fromRoute('path', 0);

            if ( !empty($pathVar) ) {
                $this->redirect()->toRoute('index', array("data" => ''), array("query" => array("path" => urldecode($pathVar))));
            } else {
                return $this->redirect()->toUrl('/');
            }
        }

        if ( !$this->getRequest()->isPost() ) {
            return $this->redirect()->toUrl('/');
        }

        $data = $this->getRequest()->getPost();
        $em = $this->serviceLocator->get('doctrine.entitymanager.orm_default');

        try {

            $randomString = $this->getRandomString(8);
            $newPassword = md5($randomString);

            $userEntity = $em->getRepository('User\Entity\User')->findOneBy(
                array(
                    'username' => $data['username'],
                    'user_type_id' => $data['user_type']
                )
            );

            if ( !$userEntity ) {
                throw new \Exception('Username is invalid.');
            }

            $usenNameValue = $userEntity->username;

            if ( $data['user_type'] == 1 ) {
                $employeeEntity = $em->getRepository('User\Entity\EmployeeDetails')->findOneBy(
                    array(
                        'emp_id' => $data['username'],
                        'email' => $data['email']
                    )
                );

                if ( !$employeeEntity ) {
                    throw new \Exception('Entered email id does not belong to entered username.');
                }

                if ( is_null($employeeEntity->email) ) {
                    throw new \Exception('Contact admin, should not find your mail id?');
                }

                $emailID = $employeeEntity->email;
            } elseif ( $data['user_type'] == 2 ) {
                $studentEntity = $em->getRepository('User\Entity\StudentDetails')->findOneBy(
                    array(
                        'student_id' => $data['username'],
                        'email' => $data['email']
                    )
                );

                if ( !$studentEntity ) {
                    throw new \Exception('Entered email id does not belong to entered username.');
                }

                if ( is_null($studentEntity->email) ) {
                    throw new \Exception('Contact to your admin, mail id not found.');
                }

                $emailID = $studentEntity->email;
            }elseif ( $data['user_type'] == 4 ) {
                $applicantEntity = $em->getRepository('User\Entity\Applicant')->findOneBy(
                    array(
                        'email' => $data['username'],
                        'email' => $data['email']
                    )
                );

                if ( !$applicantEntity ) {
                    throw new \Exception('Entered email id does not belong to entered username.');
                }

                if ( is_null($applicantEntity->email) ) {
                    throw new \Exception('Contact to your admin, mail id not found.');
                }

                $emailID = $applicantEntity->email;
            } else {
                throw new \Exception('User details not found.');
            }

            $sendMail = $this->forgotPasswordTemplate($emailID, $randomString);

            if ( isset($sendMail['error']) ) {
                throw new \Exception('Message could not be sent');
            }

            /* Update user password field */
            $userEntityCheck = $em->getRepository('User\Entity\User')->findOneBy(
                array(
                    'username' => $usenNameValue
                )
            );
            $userEntityCheck->password = $newPassword;
	    $userEntityCheck->user_status_id = 0;
            $em->persist($userEntityCheck);
            $em->flush();

            $response['status'] = 'success';
            $response['redirect'] = $this->url()->fromRoute('auth', ['action' => 'view-login'], ['force_canonical' => true]);
            $response['message'] = "Password has been reset. Please check your inbox or if not spam folder in email";

        } catch ( \Exception $ex ) {
            $response['status'] = 'error';
            $response['message'] = $ex->getMessage();
        }

        return $this->getResponse()->setContent(json_encode($response));
    }


    public function forgotPasswordTemplate($emailID, $randomString) {
        $messageTitle = 'User Password Reset';

        $messageBody = '<h2>To login to your account,</h2><p>Please use below credential.</p>';
        $messageBody .= '<p><b>Passwpord:</b> <span style="color: #00A000">' . $randomString . "</span></p>";

        $response['status'] = 'success';
        $response['redirect'] = $this->url()->fromRoute('auth', ['action' => 'view-login'], ['force_canonical' => true]);
        $response['message'] = 'Successfully send mail. To login your account please check password sent to your mail';

        //$sendMail = $this->sendResetPasswordLinkToMail($emailID, $messageTitle, $messageBody);

        $sendMail = $this->emailService->sendMailer($emailID, $messageTitle, $messageBody);

        return $sendMail;
    }


    function getRandomString($length) {
        $random = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet);

        for ( $i = 0; $i < $length; $i++ ) {
            $random .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return str_shuffle($random);
    }

    
    /**
     * Logout Action
     * URL : /auth/logout
     */
    public function logoutAction()
    {
        error_reporting(0);

        $this->authPlugin()->logout();
        return $this->redirect()->toUrl($this->getRequest()->getServer('REDIRECT_BASE'));
    }
    
    /**
     * Logout Action
     * URL : /auth/index
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
