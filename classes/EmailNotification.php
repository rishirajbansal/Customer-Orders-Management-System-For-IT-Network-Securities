<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'swift'.DIRECTORY_SEPARATOR.'swift_required.php';
//include_once '/home/content/22/10609722/html' .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'swift'.DIRECTORY_SEPARATOR.'swift_required.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
//include_once '/home/content/22/10609722/html' .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';

/**
 * Description of EmailNotification
 *
 * @author Rishi
 */
class EmailNotification {
    
    
    function __construct() {
        
    }
    
    function sendRegistrationEmail($mailInfo){

	$body = $this->formatRegistrationBody($mailInfo);
        
        $transport = Swift_SmtpTransport::newInstance(Config::$smtp_server, Config::$smtp_port) 
                    ->setUsername(Config::$smtp_username)
                    ->setPassword(Config::$smtp_password); 
        $mailer = Swift_Mailer::newInstance($transport);
        
	$message = Swift_Message::newInstance();
	$message->setSubject(Config::$registration_subject);
	$message->setFrom(array(Config::$from => Config::$from_name));
	$message->setTo(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
        
	$message->addPart($body, 'text/html');
			
	$result = $mailer->send($message);
	
	return $result;
	
    }
    
    function sendWelcomeMessageEmail($mailInfo){

	$body = $this->formatWelcomeMessageBody($mailInfo);
        
        $transport = Swift_SmtpTransport::newInstance(Config::$smtp_server, Config::$smtp_port) 
                    ->setUsername(Config::$smtp_username)
                    ->setPassword(Config::$smtp_password); 
        $mailer = Swift_Mailer::newInstance($transport);
        
	$message = Swift_Message::newInstance();
	$message->setSubject(Config::$welcome_subject);
	$message->setFrom(array(Config::$from => Config::$from_name));
	$message->setTo(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
        
	$message->addPart($body, 'text/html');
			
	$result = $mailer->send($message);
	
	return $result;
	
    }
    
    function sendForgotPasswordEmail($mailInfo){

	$body = $this->formatForgotPasswordBody($mailInfo);
        
        $transport = Swift_SmtpTransport::newInstance(Config::$smtp_server, Config::$smtp_port) 
                    ->setUsername(Config::$smtp_username)
                    ->setPassword(Config::$smtp_password); 
        $mailer = Swift_Mailer::newInstance($transport);
        
	$message = Swift_Message::newInstance();
	$message->setSubject(Config::$forgotPassword_subject);
	$message->setFrom(array(Config::$from => Config::$from_name));
	$message->setTo(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
        
	$message->addPart($body, 'text/html');
			
	$result = $mailer->send($message);
	
	return $result;
	
    }
    
    function sendContactUsEmail($mailInfo){

	$body = $this->formatContactUsBody($mailInfo);
        
        $transport = Swift_SmtpTransport::newInstance(Config::$smtp_server, Config::$smtp_port) 
                    ->setUsername(Config::$smtp_username)
                    ->setPassword(Config::$smtp_password); 
        $mailer = Swift_Mailer::newInstance($transport);
        
	$message = Swift_Message::newInstance();
	$message->setSubject(Config::$contactUs_subject);
	$message->setFrom(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
	$message->setTo(array(Config::$contactus_mail => Config::$contactus_mail_name));
        $message->setReplyTo(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
        
	$message->addPart($body, 'text/html');
			
	$result = $mailer->send($message);
	
	return $result;
	
    }
    
    function sendStatusEmail($mailInfo, $status){
        
        $transport = Swift_SmtpTransport::newInstance(Config::$smtp_server, Config::$smtp_port) 
                    ->setUsername(Config::$smtp_username)
                    ->setPassword(Config::$smtp_password); 
        
        $mailer = Swift_Mailer::newInstance($transport);
        
        $message = Swift_Message::newInstance();
        
        $message->setFrom(array(Config::$from => Config::$from_name));
	$message->setTo(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
        
        switch ($status) {
            case Constants::ORDER_STATUS_PLACED:
                $template = file_get_contents(Config::getStatus1_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status1_subject); 
                $message->setCc(array(Config::$from => Config::$from_name));
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAYMENT_PENDING:
                $template = file_get_contents(Config::getStatus1_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status1_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_APPROVING:
                $template = file_get_contents(Config::getStatus2_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status2_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROCESSING:
                $template = file_get_contents(Config::getStatus3_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status3_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_READY_20DAYS:
                $template = file_get_contents(Config::getStatus4_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status4_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS:
                $template = file_get_contents(Config::getStatus4A_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status4A_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID:
                $template = file_get_contents(Config::getStatus4B_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status4B_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_READY_10DAYS:
                $template = file_get_contents(Config::getStatus5_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status5_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_READY_5DAYS:
                $template = file_get_contents(Config::getStatus6_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status6_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_READY_1DAY:
                $template = file_get_contents(Config::getStatus7_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status7_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS:
                $template = file_get_contents(Config::getStatus5B_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status5B_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS:
                $template = file_get_contents(Config::getStatus6B_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status6B_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY:
                $template = file_get_contents(Config::getStatus7B_body_template());
                $body = $this->formatStatusMessagesBody($mailInfo, $template);
                $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$status7B_subject); 
                $message->setSubject($subject);
                $message->addPart($body, 'text/html');                
                break;
            
            default:
                break;
        }
        
        $result = $mailer->send($message);
	
	return $result;
        
    }
    
    function sendCancelOrderEmail($mailInfo){

	$body = $this->formatCancelOrderMessageBody($mailInfo);
        $subject = preg_replace('{ORDERNO}', $mailInfo['orderno'], Config::$orderCancel_subject); 
        
        $transport = Swift_SmtpTransport::newInstance(Config::$smtp_server, Config::$smtp_port) 
                    ->setUsername(Config::$smtp_username)
                    ->setPassword(Config::$smtp_password); 
        $mailer = Swift_Mailer::newInstance($transport);
        
	$message = Swift_Message::newInstance();
	$message->setSubject($subject);
	$message->setFrom(array(Config::$from => Config::$from_name));
	$message->setTo(array($mailInfo['email'] => $mailInfo['fname'].' '.$mailInfo['lname']));
        
	$message->addPart($body, 'text/html');
			
	$result = $mailer->send($message);
	
	return $result;
	
    }
    
    
    public function formatRegistrationBody($mailInfo) {
        $template = file_get_contents(Config::getRegistration_body_templatete());
        
        $template = preg_replace('{FISRTNAME}', $mailInfo['fname'], $template);
        $template = preg_replace('{LASTNAME}', $mailInfo['lname'], $template);
	$template = preg_replace('{EMAIL}', $mailInfo['email'], $template);
	$template = preg_replace('{CLIENTID}', $mailInfo['key'], $template);
	$template = preg_replace('{PATH}', Config::getSitePath(), $template);
        
        return $template;
        
    }
    
    public function formatWelcomeMessageBody($mailInfo) {
        $template = file_get_contents(Config::getWelcome_body_templatete());
        
        $template = preg_replace('{FISRTNAME}', $mailInfo['fname'], $template);
        $template = preg_replace('{LASTNAME}', $mailInfo['lname'], $template);
	$template = preg_replace('{EMAIL}', $mailInfo['email'], $template);
	$template = preg_replace('{PASSWORD}', $mailInfo['password'], $template);
        
        return $template;
        
    }
    
    public function formatForgotPasswordBody($mailInfo) {
        $template = file_get_contents(Config::getForgotPassword_body_template());
        
        $template = preg_replace('{FISRTNAME}', $mailInfo['fname'], $template);
        $template = preg_replace('{LASTNAME}', $mailInfo['lname'], $template);
        $template = preg_replace('{EMAIL}', $mailInfo['email'], $template);
        $template = preg_replace('{PASSWORD}', $mailInfo['password'], $template);
        
        return $template;
        
    }
    
    public function formatContactUsBody($mailInfo) {
        $template = file_get_contents(Config::getContactUs_body_template());
        
        $template = preg_replace('{FISRTNAME}', $mailInfo['fname'], $template);
        $template = preg_replace('{LASTNAME}', $mailInfo['lname'], $template);
	$template = preg_replace('{EMAIL}', $mailInfo['email'], $template);
	$template = preg_replace('{MESSAGE}', $mailInfo['message'], $template);
        $template = preg_replace('{CALLERIP}', $mailInfo['ip'], $template);
        $template = preg_replace('{CLIENTID}', $mailInfo['clientid'], $template);
        
        return $template;
        
    }
    
    public function formatStatusMessagesBody($mailInfo, $template) {
        
        $template = preg_replace('{FISRTNAME}', $mailInfo['fname'], $template);
        $template = preg_replace('{LASTNAME}', $mailInfo['lname'], $template);
	$template = preg_replace('{EMAIL}', $mailInfo['email'], $template);
	$template = preg_replace('{ORDERNO}', $mailInfo['orderno'], $template);
        $template = preg_replace('{PROJECT}', $mailInfo['project'], $template);
        if (isset($mailInfo['noofemails'])){
            $template = preg_replace('{NOOFRECORDS}', $mailInfo['noofemails'], $template);
        }
        
        return $template;
        
    }
    
     public function formatCancelOrderMessageBody($mailInfo) {
        $template = file_get_contents(Config::getOrderCancel_body_template());
        
        $template = preg_replace('{FISRTNAME}', $mailInfo['fname'], $template);
        $template = preg_replace('{LASTNAME}', $mailInfo['lname'], $template);
	$template = preg_replace('{EMAIL}', $mailInfo['email'], $template);
	$template = preg_replace('{ORDERNO}', $mailInfo['orderno'], $template);
        $template = preg_replace('{PROJECT}', $mailInfo['project'], $template);
        $template = preg_replace('{CREDITAMOUNT}', $mailInfo['creditamount'], $template);
        $template = preg_replace('{REASON}', $mailInfo['cancelreason'], $template);
        
        return $template;
        
    }
}

?>
