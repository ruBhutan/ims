<?php

namespace StudentFeeDetails\Controller;

use StudentFeeDetails\Service\StudentFeeDetailsServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;

class StudentFeeDetailsController extends AbstractActionController {

    protected $service;
    protected $serviceLocator;
    protected $keyphrase = "RUB_IMS";
    protected $table_name = "student";
    protected $messageStatus = "Success";
    protected $login_user;
    protected $student_details;
    protected $student_fee_details;

    public function __construct(StudentFeeDetailsServiceInterface $service, $serviceLocator) {
        $this->service = $service;
        $this->serviceLocator = $serviceLocator;

        $this->login_user = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();

        if ( $this->login_user['user_type_id'] == 2 ) {
            $this->student_details = $this->service->getStudentDetailsByID($this->table_name, $this->login_user['username']);
        }
    }

    public function indexAction() {
        return array(
            'studentDetails' => $this->student_details,
            'studentFeeList' => $this->service->listStudentFeeList($this->student_details->getID()),
            'keyphrase' => $this->keyphrase,
            'message_status' => $this->messageStatus
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
}
