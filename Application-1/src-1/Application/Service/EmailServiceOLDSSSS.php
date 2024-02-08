<?php

namespace Application\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
  
  protected $mail;

  public function __construct() {
    $this->mail = new PHPMailer(true);
    
    // Server Configurations
    $this->mail->SMTPDebug = 2; // Used to debug(check) errors
    $this->mail->isSMTP();
    $this->mail->Host ='smtp.gmail.com';
    $this->mail->SMTPAuth = true;
    $this->mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
    $this->mail->Username = 'rubims.rub@rub.edu.bt';
    $this->mail->Password = 'Dawa@1234';
    $this->mail->SMTPSecure = 'ssl';
    $this->mail->Port = 465;
    $this->mail->isHTML(true);
    $this->mail->setFrom('rubims.rub@rub.edu.bt', 'Royal University of Bhutan');

  }

  public function sendMailer($emailTo, $emailSubject, $emailMessage, $emailReplyTo = null, $emailAttachment = null, $emailCc = null, $emailBcc = null) {
    try {
      // Email Recipient(s)
      if (!$this->addEmailTo($emailTo)) {
        return array('error' => 'Email Recepient can not be empty');
      }
      $this->addEmailReplyTo($emailReplyTo);
      $this->addEmailCC($emailCc);
      $this->addEmailBCC($emailBcc);

      // Email Attachment(s)
      $this->addEmailAttachments($emailAttachment);
      
      // Email Content
      if (!$this->setEmailSubject($emailSubject)) {
        return array('error' => 'Email Subject can not be empty');
      }
      if (!$this->setEmailBody($emailMessage)) {
        return array('error' => 'Email Message can not be empty');
      }

      $this->mail->send();
      $this->mail->smtpClose();
      return array('success' => 'Message has been sent');
    } catch (Exception $e) {
      return array('error' => 'Message could not be sent. Mailer Error: '. $this->mail->ErrorInfo);
    }
  }
  
  public function addEmailTo($emailTo) {
    if (!is_array($emailTo)) {
      if (!empty($emailTo) && $emailTo !== null) {
        $this->mail->addAddress($emailTo);
        return true;
      }
    } else {
      if (count($emailTo) > 0) {
        foreach ($emailTo as $email) {
          $this->mail->addAddress($email);
        }
        return true;
      }
    }
    return false;
  }
  
  public function addEmailReplyTo($emailReplyTo) {
    if ($emailReplyTo !== null) {
      $this->mail->addReplyTo($emailReplyTo);
    }
  }
  
  public function addEmailCC($emailCc) {
    if ($emailCc !== null) {
      if (!is_array($emailCc)) {
        $this->mail->addCC($emailCc);
      } else {
        foreach ($emailCc as $email) {
          $this->mail->addCC($email);
        }
      }
    }
  }
  
  public function addEmailBCC($emailBcc) {
    if ($emailBcc !== null) {
      if (!is_array($emailBcc)) {
        $this->mail->addBCC($emailBcc);
      } else {
        foreach ($emailBcc as $email) {
          $this->mail->addBCC($emailBcc);
        }
      }
    }
  }
  
  public function setEmailSubject($emailSubject) {
    if (!empty($emailSubject) && $emailSubject !== null) {
      $this->mail->Subject = $emailSubject;
      return true;
    }
    return false;
  }
  
  public function setEmailBody($emailMessage) {
    if (!empty($emailMessage) && $emailMessage !== null) {
      $this->mail->Body = $emailMessage;
      return true;
    }
    return false;
  }

  public function addEmailAttachments($emailAttachment) {
    if ($emailAttachment !== null) {
      if (!is_array($emailAttachment)) {
        $this->mail->addAttachment($emailAttachment);
      } else {
        foreach ($emailAttachment as $attachment) {
          $this->mail->addAttachment($attachment);
        }
      }
    }
  }
  
}
