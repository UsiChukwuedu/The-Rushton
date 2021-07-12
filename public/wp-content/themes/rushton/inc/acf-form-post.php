<?php
use \DrewM\MailChimp\MailChimp;
use Ctct\Components\Contacts\Contact;
use Ctct\ConstantContact;
use Ctct\Exceptions\CtctException;
use Ctct\Components\Contacts\CustomField;
use Ctct\Components\Contacts\Address;

if (isset($_GET['autoresponder']) && $_GET['autoresponder'] == 'yes') {
    while (have_rows('form', $form_post)){ the_row();
        if(get_row_layout() == 'options'){
            $autoresponder = get_sub_field('autoresponder');
        }
    }
    echo $autoresponder;
    die();
}

$database_table = '';
$rows = '';
$table_name = '';
$collate = '';
$sql = '';
$req_star = '';
$error = '';
$recipient = '';
$from_name = '';
$from_email = '';
$consent = '';
$email_content = '';
$autoresponder = '';
$autoresponder_email = '';
$autoresponder_subject_line = '';
$mailchimp_api_key = '';
$mailchimp_list_id = '';
$constant_contact_api_key = '';
$constant_contact_access_token = '';
$constant_contact_list_id = '';
$thank_you_page = '';
$input_error_message = '';
$email_notification_subject_line = '';
$external_action_code = '';
$server_side_key = '';
$mandrill_api_key = '';
$thank_you_heading = '';
$thank_you_message_with_consent = '';
$thank_you_message_without_consent = '';
$campaign_service = '';
$transactional_email_service = '';
$form_error_message = '';
$is_ajax_form = false;
$double_optin = true;
$display_field_labels = false;
$display_placeholders = false;
$display_error_labels = false;
$disable_after_submit = false;
$custom_placeholders = false;
$display_options = array();
$form_data = array();
$email_labels = array();
$bcc_list = array();
$mailchimp_mergevars = array();
$constant_contact_mergevars = array();
$spam_expressions = array();
$defaultErrorMsg = "An error has occurred";
$form_post = $_POST['formId'] ?? $form_post;
$retVal = array("success" => false, "retmsg" => $defaultErrorMsg, "consent" => false, "formID" => $form_post);
$two_step_form = '';

function send_email($email, $fromname, $fromemail, $subject, $message, $bcc_list, $headers = null)
{
    // Unique boundary
    $boundary = md5( uniqid() . microtime() );
    // If no $headers sent
    // Add From: header
    $headers = "From: " . $fromname . " <" . $fromemail . ">\r\n";

    // Specify MIME version 1.0
    $headers .= "MIME-Version: 1.0\r\n";
    // Tell e-mail client this e-mail contains alternate versions
    $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
 
    foreach($bcc_list as $bcc_email){
        $headers .= 'Bcc: ' . $bcc_email . "\r\n";
    }
 
   // Plain text version of message
    $body = "--$boundary\r\n" .
       "Content-Type: text/plain; charset=ISO-8859-1\r\n" .
       "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split( base64_encode( strip_tags($message) ) );
    // HTML version of message
    $body .= "--$boundary\r\n" .
       "Content-Type: text/html; charset=ISO-8859-1\r\n" .
       "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split( base64_encode( $message ) );
    $body .= "--$boundary--";

    mail($email, $subject, $body, $headers,"-f" . $fromemail);
}

function listSuccessCheck($isSuccess, $stepOne, $stepTwo, $is_ajax_form, $consent, $lastid, $thank_you_page, $errordetail) {
    if (!$isSuccess) {
        if ($stepOne || $stepTwo) {
            $retVal = array("success" => false, "retmsg" => "Error: " . $errordetail, "consent" => strtolower($consent), "formID" => $form_post);
        } else if ($is_ajax_form) {
            $retVal = array("success" => true, "retmsg" => "MC Error: " . $errordetail, "consent" => strtolower($consent), "formID" => $form_post);
        } else {
            echo "Error: " . $errordetail;
        }
    } else {
        if ($stepOne) {
            $retVal = array("success" => true, "retmsg" => $lastid, "consent" => strtolower($consent), "formID" => $form_post);
        } else if ($stepTwo) {
            $retVal = array("success" => true, "retmsg" => $thank_you_page, "consent" => strtolower($consent), "formID" => $form_post);
        } else if ($is_ajax_form) {
            $retVal = array("success" => true, "retmsg" => "Submitted to MailChimp.", "consent" => strtolower($consent), "formID" => $form_post);
        } else {
            if ($consent == 'Yes') {
                header('Location: ' . $thank_you_page . '?page_ID=' . get_the_ID());
            } else {
                header('Location: ' . $thank_you_page . '?page_ID=' . get_the_ID() . '&consent=false');
            }
            exit;
        }   
    }

    return $retVal;
}


function setConstantContactDetail($contact, $constant_contact_mergevars) {
    foreach ($constant_contact_mergevars as $form_key => $form_value) {
        switch (strtoupper($form_key)) {
            case "EMAIL":
                break;
            case "FIRSTNAME":
                $contact->first_name = $form_value[0];
                break;
            case "FNAME":
                $contact->first_name = $form_value[0];
                break;
            case "LASTNAME":
                $contact->last_name = $form_value[0];
                break;
            case "LNAME":
                $contact->last_name = $form_value[0];
                break;
            case "PHONE":
                $contact->cell_phone = $form_value[0];
                break;
            case "CITY":
                $contact->addAddress(Address::create( 
                    array("address_type" => 'PERSONAL', "city"=>$form_value[0]))
                );
                break;
            case "CONSENT":
                $consentcc = new CustomField();
                $consentcc->name = "custom_field_1";
                $consentcc->value = $form_value[1] . ': ' . $form_value[0];
                $contact->addCustomField($consentcc);
                break;
            default:
                if($form_value[0] !== ''){
                    $custom_field = new CustomField();
                    $custom_field->name = $form_key;
                    $custom_field->value = $form_value[1] . ': ' . $form_value[0];
                    $contact->addCustomField($custom_field);
                }
                break;
        }
    }

    return $contact;
}

function emailExistsMc($subscriberMail, $list_id){
    global $MailChimp;

    $subscriber_hash = $MailChimp->subscriberHash($subscriberMail);
    $result = $MailChimp->get("lists/$list_id/members/$subscriber_hash");

    if($result['status'] == '404') return false;
    return true;
}

function cleanParams($param) {
    $param = strip_tags($param);
    $param = esc_sql($param);
    return $param;
}

while (have_rows('form', $form_post)){ the_row();
    if(get_row_layout() == 'options'){
        $external_action_code = get_sub_field('external_action_code');
        $req_star = get_sub_field('required_field_star');
        $spam_expressions = get_sub_field('spam_expressions');
        $input_error_message = get_sub_field('input_error_message');
        $is_ajax_form = get_sub_field('is_ajax_form');
        $two_step_form = get_sub_field('two_step_form');
        $thank_you_heading = get_sub_field('thank_you_heading');
        $thank_you_message_with_consent = get_sub_field('thank_you_message_with_consent');
        $thank_you_message_without_consent = get_sub_field('thank_you_message_without_consent');
        $display_options = get_sub_field('display_options');
        $custom_placeholders = get_sub_field('custom_placeholders');
        $campaign_service = get_sub_field('campaign_service');
        $transactional_email_service = get_sub_field('transactional_email_service');
        $form_error_message = get_sub_field('form_error_message');
        if(in_array('field_labels', $display_options)){
            $display_field_labels = true;
        }
        if(in_array('placeholders', $display_options)){
            $display_placeholders = true;
        }
        if(in_array('error_labels', $display_options)){
            $display_error_labels = true;
        }
    } else if(get_row_layout() == 'submit_button'){
        $disable_after_submit = get_sub_field('disable_after_submit');
    }
    if(get_sub_field('field_name')){
        $rows .= get_sub_field('field_name') . ' varchar(255)';
        if(get_sub_field('required')):
            $rows .= ' NOT NULL';
        endif;
        $rows .= ', ';
    }
    if(get_sub_field('checkbox_field_name')){
        $rows .= get_sub_field('checkbox_field_name') . ' varchar(255)';
        if(get_sub_field('required')):
            $rows .= ' NOT NULL';
        endif;
        $rows .= ', ';
    }
    if(get_sub_field('radio_field_name')){
        $rows .= get_sub_field('radio_field_name') . ' varchar(255)';
        if(get_sub_field('required')):
            $rows .= ' NOT NULL';
        endif;
        $rows .= ', ';
    }
    if(get_sub_field('textfield_field_name')){
        $rows .= get_sub_field('textfield_field_name') . ' varchar(255)';
        if(get_sub_field('required')):
            $rows .= ' NOT NULL';
        endif;
        $rows .= ', ';
    }

    if(get_row_layout() == 'recaptcha'){
        $server_side_key = get_sub_field('server_side_key');
    } else if(get_row_layout() == 'consent'){
        $rows .= 'consent varchar(255), ';
    } else if(get_row_layout() == 'database_table_creator'):
        if(get_sub_field('table_name')):
            $table_name = get_sub_field('table_name');
        endif;
        if(get_sub_field('collate')):
            $collate = get_sub_field('collate');
        endif;
    endif;
  
    $utmSql = "USOURCE varchar(1000) NOT NULL DEFAULT '',
              UMEDIUM varchar(1000) NOT NULL DEFAULT '',
              UCAMPAIGN varchar(1000) NOT NULL DEFAULT '',
              UCONTENT varchar(1000) NOT NULL DEFAULT '',
              UTERM varchar(1000) NOT NULL DEFAULT '',
              IREFERRER varchar(2000) NOT NULL DEFAULT '',
              LREFERRER varchar(2000) NOT NULL DEFAULT '',
              ILANDPAGE varchar(1000) NOT NULL DEFAULT '',
              VISITS varchar(1000) NOT NULL DEFAULT '', ";


    $sql = 'CREATE TABLE ' . $table_name . ' ( id int(11) NOT NULL, ' . $rows . $utmSql . ' ipaddress varchar(50), timestamp timestamp, PRIMARY KEY (id) )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ';
    $sql .= 'ALTER TABLE '. $table_name .' MODIFY id int(11) NOT NULL AUTO_INCREMENT;';
}

$stepOne = false;
$stepTwo = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbid = -1;
    
    if (isset($_POST['stepnum']) && $_POST['stepnum'] == 1) {
        $stepOne = true;
    }
    
    if (isset($_POST['stepnum']) && $_POST['stepnum'] == 2) {
        $stepTwo = true;
        
        // get email steptwo to verify against dbid
        $autoresponder_email = cleanParams($_POST['emailsteptwo']);
        $autoresponder_email = str_replace('\\', '', $autoresponder_email);
        $autoresponder_email = stripslashes($autoresponder_email);
        $autoresponder_email = trim($autoresponder_email);
        
        if (!filter_var($autoresponder_email, FILTER_VALIDATE_EMAIL) || $autoresponder_email == '') {
            $error .= '<br />Please enter a valid email address.';
        }
        
        $dbid = cleanParams($_POST['dbid']);
        $dbid = str_replace('\\', '', $dbid);
        $dbid = stripslashes($dbid);
        $dbid = trim($dbid);
        
        // prevent hacks if not a number
        if (!is_numeric($dbid)) {
            $dbid = -1;
            $error .= '<br />Updating entry error.';
        }
        
    }
    
    if(isset($_POST['USOURCE']) && $_POST['USOURCE']){
        $mailchimp_mergevars['USOURCE'] = $_POST['USOURCE'];
        $form_data['USOURCE'] = $_POST['USOURCE'];
    }
    if(isset($_POST['UMEDIUM']) && $_POST['UMEDIUM']){
        $mailchimp_mergevars['UMEDIUM'] = $_POST['UMEDIUM'];
        $form_data['UMEDIUM'] = $_POST['UMEDIUM'];
    }
    if(isset($_POST['UCAMPAIGN']) && $_POST['UCAMPAIGN']){
        $mailchimp_mergevars['UCAMPAIGN'] = $_POST['UCAMPAIGN'];
        $form_data['UCAMPAIGN'] = $_POST['UCAMPAIGN'];
    }
    if(isset($_POST['UCONTENT']) && $_POST['UCONTENT']){
        $mailchimp_mergevars['UCONTENT'] = $_POST['UCONTENT'];
        $form_data['UCONTENT'] = $_POST['UCONTENT'];
    }
    if(isset($_POST['UTERM']) && $_POST['UTERM']){
        $mailchimp_mergevars['UTERM'] = $_POST['UTERM'];
        $form_data['UTERM'] = $_POST['UTERM'];
    }
    if(isset($_POST['IREFERRER']) && $_POST['IREFERRER']){
        $mailchimp_mergevars['IREFERRER'] = $_POST['IREFERRER'];
        $form_data['IREFERRER'] = $_POST['IREFERRER'];
    }
    if(isset($_POST['LREFERRER']) && $_POST['LREFERRER']){
        $mailchimp_mergevars['LREFERRER'] = $_POST['LREFERRER'];
        $form_data['LREFERRER'] = $_POST['LREFERRER'];
    }
    if(isset($_POST['ILANDPAGE']) && $_POST['ILANDPAGE']){
        $mailchimp_mergevars['ILANDPAGE'] = $_POST['ILANDPAGE'];
        $form_data['ILANDPAGE'] = $_POST['ILANDPAGE'];
    }
    if(isset($_POST['VISITS']) && $_POST['VISITS']){
        $mailchimp_mergevars['VISITS'] = $_POST['VISITS'];
        $form_data['VISITS'] = $_POST['VISITS'];
    }
    $inStepOne = true;
    while (have_rows('form', $form_post)){ the_row();
        if (($stepOne || $stepTwo) && get_row_layout() == 'start_step_two') {
            $inStepOne = false;
        }
        
        // if step one && field not marked step_one, then ignore
        if ($stepOne && !$inStepOne) {
            if (get_row_layout() !== 'options') {
                continue;
            }
        }
        
        // if step two && field marked step_one, then ignore
        if ($stepTwo && $inStepOne) {
            if (get_row_layout() !== 'options') {
                continue;
            }
        }
        
        if(get_sub_field('field_name')){
            
            if(get_row_layout() == 'single_checkbox'){
                $checkbox_field_name = get_sub_field('field_name');
                if(isset($_POST[$checkbox_field_name])){
                    $form_data[$checkbox_field_name] = $_POST[$checkbox_field_name];
                } else {
                    $form_data[$checkbox_field_name] = get_sub_field('unchecked_value');
                }
                
                $email_labels[$checkbox_field_name] = get_sub_field('email_label');
                $mailchimp_mergevars[get_sub_field('campaign_field_name')] = $form_data[$checkbox_field_name];
                $constant_contact_mergevars[get_sub_field('campaign_field_name')] = array($form_data[$checkbox_field_name], get_sub_field('email_label'));
                $email_content .= get_sub_field('email_label') . ': ' . $form_data[$checkbox_field_name] . '<br>';
            } else {
                $field_name = get_sub_field('field_name');
                $field_data = $_POST[$field_name];
                $field_data = cleanParams($field_data);
                $field_data = str_replace('\\', '', $field_data);
                $field_data = stripslashes($field_data);
                $field_data = trim($field_data);
                if(!empty($spam_expressions)){
                    foreach($spam_expressions as $as){
                        if($as['form_field_name'] === $field_name){
                            if(preg_match('|' . preg_quote($as['expression'], '|') . '|', $field_data) > 0){
                                die('SPAM DETECTED');
                            }
                        }
                    }
                }
                $form_data[$field_name] = $field_data;
                $email_labels[$field_name ] = get_sub_field('email_label');
                $mailchimp_mergevars[get_sub_field('campaign_field_name')] = $field_data;
                $constant_contact_mergevars[get_sub_field('campaign_field_name')] = array($field_data, get_sub_field('email_label'));
                $email_content .= get_sub_field('email_label') . ': ' . $field_data . '<br>';
                if(get_sub_field('type') === 'email'){
                    if(get_sub_field('required')){
                        if (!filter_var($field_data, FILTER_VALIDATE_EMAIL) || $field_data == '') {
                            $error .= '<br />Please enter a valid email address.';
                        }
                    }
                    $autoresponder_email = $field_data;
                } else {
                    if(get_sub_field('required')){
                        if (!filter_var($field_data, FILTER_SANITIZE_STRING) || $field_data == '') {
                            $error .= '<br />Please enter a valid ' . $field_name  . '.';
                        }
                    }
                }
                if(get_row_layout() == 'select_field'){
                    if(get_sub_field('required')){
                        if (!isSet($field_name)) {
                            $error .= '<br />Please select a ' . $select . '.';
                        }
                    }
                }
            }
        }
        
        if(get_sub_field('checkbox_field_name')){
            $checkbox_field_name = get_sub_field('checkbox_field_name');
            if(isset($_POST[$checkbox_field_name])){
                $field_data = $_POST[$checkbox_field_name];
                $field_data_string = '';
                $form_data[$checkbox_field_name] = '';
                $count = 1;
                foreach($field_data as $check) {
                    $form_data[$checkbox_field_name] .= $check;
                    $field_data_string .= $check;
                    if(sizeof($field_data) > 1 && $count < sizeof($field_data)){
                        $form_data[$checkbox_field_name] .= ', ';
                        $field_data_string .= ', ';
                        $count++;
                    }
                }
                $email_labels[$checkbox_field_name] = get_sub_field('radio_email_label');
                $mailchimp_mergevars[get_sub_field('campaign_field_name')] = $field_data_string;
                $constant_contact_mergevars[get_sub_field('campaign_field_name')] = array($field_data_string, get_sub_field('email_label'));
                $email_content .= get_sub_field('email_label') . ': ' . $field_data_string . '<br>';
            } else {
                $form_data[get_sub_field('checkbox_field_name')] = '--';
            }
        }
        
        if(get_sub_field('radio_field_name')){
            if(isset($_POST[get_sub_field('radio_field_name')])){
                if(!empty($_POST[get_sub_field('radio_field_name')])){
                    $field_data = $_POST[get_sub_field('radio_field_name')];
                    $field_data = cleanParams($field_data);
                    $field_data = str_replace('\\', '', $field_data);
                    $field_data = stripslashes($field_data);
                    $field_data = trim($field_data);
                    $form_data[get_sub_field('radio_field_name')] = $field_data;
                    $email_labels[get_sub_field('radio_field_name')] = get_sub_field('radio_email_label');
                    $mailchimp_mergevars[get_sub_field('radio_campaign_field_name')] = $field_data;
                    $constant_contact_mergevars[get_sub_field('radio_campaign_field_name')] = array($field_data, get_sub_field('email_label'));
                    $email_content .= get_sub_field('radio_email_label') . ': ' . $field_data . '<br>';
                    if(get_sub_field('required_radio')){
                        if (!filter_var($field_data, FILTER_SANITIZE_STRING) || $field_data == '') {
                            $error .= '<br />Please enter a valid ' . get_sub_field('radio_field_name') . '.';
                        }
                    }
                }
            } else {
                $form_data[get_sub_field('radio_field_name')] = '--';
            }
        }
        
        if(get_sub_field('textfield_field_name')){
            if(!empty($_POST[get_sub_field('textfield_field_name')])){
                $field_data = $_POST[get_sub_field('textfield_field_name')];
                $field_data = cleanParams($field_data);
                $field_data = str_replace('\\', '', $field_data);
                $field_data = stripslashes($field_data);
                $field_data = trim($field_data);
                $form_data[get_sub_field('textfield_field_name')] = $field_data;
                $email_labels[get_sub_field('textfield_field_name')] = get_sub_field('textfield_email_label');
                $mailchimp_mergevars[get_sub_field('textfield_campaign_field_name')] = $field_data;
                $constant_contact_mergevars[get_sub_field('textfield_campaign_field_name')] = array($field_data, get_sub_field('textfield_field_name'));
                $email_content .= get_sub_field('textfield_email_label') . ': ' . $field_data . '<br>';
                if(get_sub_field('required_textfield')){
                    if (!filter_var($field_data, FILTER_SANITIZE_STRING) || $field_data == '') {
                        $error .= '<br />Please enter a valid ' . get_sub_field('textfield_field_name') . '.';
                    }
                }
            } else {
                $form_data[get_sub_field('textfield_field_name')] = '--';
            }
        }
        
        if(get_row_layout() == 'options'){
            $database_table  = get_sub_field('database_table');
            $recipient = get_sub_field('email_notification_recipient');
            $from_name = get_sub_field('from_name');
            $from_email = get_sub_field('from_email');
            $autoresponder = get_sub_field('autoresponder');
            $autoresponder_subject_line = get_sub_field('autoresponder_subject_line');
            $mailchimp_api_key = get_sub_field('mailchimp_api_key');
            $mailchimp_list_id = get_sub_field('mailchimp_list_id');
            $constant_contact_api_key = get_sub_field('constant_contact_api_key');
            $constant_contact_access_token = get_sub_field('constant_contact_access_token');
            $constant_contact_list_id = get_sub_field('constant_contact_list_id');
            $mandrill_api_key = get_sub_field('mandrill_api_key');
            $thank_you_page = get_sub_field('thank_you_page');
            $email_notification_subject_line = get_sub_field('email_notification_subject_line');
            if(have_rows('email_notification_bcc_list')){
                $count = 0;
                while (have_rows('email_notification_bcc_list')){
                    the_row();
                    $bcc_list[$count++] = get_sub_field('email');
                }
            }
        } elseif(get_row_layout() == 'recaptcha'){
            $data = array(
                'secret' => $server_side_key,
                'response' => $_POST['g-recaptcha-response']
            );
            
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($verify, CURLOPT_POST, true);
                curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($verify);
                
                $res= json_decode($response, true);
                if($res['success']) {
                    //echo "passed";
                    // success -- leave error variable as is and pass through
                } else {
                    $error .= "<br />Please enter a valid captcha.";
                }
            }
        }
        
        if(isSet($_POST['consent'])){
            $consent = 'Yes';
            $double_optin = false;
        } else {
            $consent = 'No';
        }
        
        $mailchimp_mergevars['CONSENT'] = $consent;
        if($consent == 'Yes'){
            $form_data['consent'] = $consent;
        }
        $constant_contact_mergevars['CONSENT'] = array($consent, 'Consent');
        
        $form_data['ipaddress'] = $_SERVER['REMOTE_ADDR'];
        function sendToSpark($sparkUrl, $fields) {
            $fieldsPost = http_build_query($fields);
            
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $sparkUrl);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsPost);
            
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            //execute post
            $result = curl_exec($ch);
            
            //close connection
            curl_close($ch);
            
            if ($result)
            if (strpos($result, "thank-you-success.com") === false) {
                return false;
            } else {
                return true;
            }
        }
        if (trim($error) == '') {
            $fields = array(
                'contact[first_name]' => $form_data['firstname'],
                'contact[last_name]' => $form_data['lastname'],
                'contact[email]' => $form_data['email'],
                'contact[postcode]' => $form_data['postalcode'],
                'source' => 'Sterling Junction Registration Website',
                'redirect_success' => 'http://thank-you-success.com/',
                'redirect_error' => 'http://thank-you-error.com/'
            );
            
            $sparkError = false;
            
            $sparkurl = "https://spark.re/marlin-spring/sterling-junction/register/sterling-junction";
            if ($_SERVER['HTTP_HOST']!=="aws.52beta.ca") {
                if (!sendToSpark($sparkurl, $fields)) {
                    $sparkError = true;
                }
                
                if ($sparkError) {
                    send_email("nate@52pick-up.com", "Sterling Junction", "web@52pick-up.com", "Sterling Junction: Spark Contact Failure", "The Following Registrant Failed to be added: <br /><br />" . implode("<br />", $fields), array());
                    $error = "An error has occurred adding you to our Mailing List Service.";
                }    
            }   
        }
        if(trim($error) == ''){
            $wpdb->hide_errors();
            $dbupdate = false;
            if ($stepTwo) {
                $update = $wpdb->update($database_table, $form_data, array("id" => $dbid), null, array('%d'));
                if ($update === false) {
                } else {
                    $dbupdate = true;
                }
            } else {
                $insert = $wpdb->insert($database_table, $form_data);
                if($insert == 1){
                    $dbupdate = true;
                }
            }
            if($dbupdate){
                $lastid = $wpdb->insert_id;
                
                $subject = 'New contact using the ' . $email_notification_subject_line . ' registration form';
                $email_content .= 'Consent: ' . $consent;
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $email_content .= '<br />Submitted from: ' . $actual_link;
                $email_content = "<html><body>" . $email_content . "</body></html>";
                $email_headers = 'From: ' . $from_name . ' <' . $from_email . '>' . "\r\n";
                
                foreach($bcc_list as $bcc_email){
                    $email_headers .= 'Bcc: ' . $bcc_email . "\r\n";
                }
                
                $email_headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                
                if($transactional_email_service === 'mandrill'){
                    include(dirname(__DIR__).'/php/Mandrill.php');
                    
                    try {
                        $mandrill = new Mandrill($mandrill_api_key);
                        
                        $message = array(
                            'html' => stripslashes($email_content),
                            'text' => stripslashes(strip_tags($email_content)),
                            'subject' => $subject,
                            'from_email' => $from_email,
                            'from_name' => $from_name,
                            'to' => array(
                                array(
                                    'email' => $recipient,
                                    'name' => '',
                                    'type' => 'to'
                                    )
                                ),
                                'headers' => array('Reply-To' => $from_email)
                            );
                            foreach($bcc_list as $bcc_email){
                                $message['bcc_address'] = $bcc_email;
                            }
                            
                            $async = false;
                            $result = $mandrill->messages->send($message, $async);
                        } catch(Mandrill_Error $e) {
                            // php notification email
                            if(mail($recipient, $subject, $email_content, $email_headers)) { }
                        }
                        
                        // send autoresponder with mandrill
                        try {
                            $mandrill = new Mandrill($mandrill_api_key);
                            
                    $message = array(
                        'html' => stripslashes($autoresponder),
                        'text' => stripslashes(strip_tags($autoresponder)),
                        'subject' => $autoresponder_subject_line,
                        'from_email' => $from_email,
                        'from_name' => $from_name,
                        'to' => array(
                            array(
                                'email' => $autoresponder_email,
                                'name' => '',
                                'type' => 'to'
                            )
                        ),
                        'headers' => array('Reply-To' => $from_email)
                    );
                    
                    $async = false;
                    $result = $mandrill->messages->send($message, $async);
                } catch(Mandrill_Error $e) {
                    // autoresponder
                    $email_headers = 'From: ' . $from_name . ' <' . $from_email . '>' . "\r\n";
                    $email_headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

                    // php notification email
                    if(mail($autoresponder_email, $autoresponder_subject_line, $autoresponder, $email_headers)) { }
                }

            } else if($transactional_email_service === 'php_mail') {
                // php notification email
                //if(mail($recipient, $subject, $email_content, $email_headers)) { }
                send_email($recipient, $from_name, $from_email, $subject, $email_content, $bcc_list);


                // autoresponder
                $email_headers = 'From: ' . $from_name . ' <' . $from_email . '>' . "\r\n";
                $email_headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

                if($consent == 'Yes' && $autoresponder != '' && $autoresponder_email != ''){
                    //if(mail($autoresponder_email, $autoresponder_subject_line, $autoresponder, $email_headers)) { }
                    send_email($autoresponder_email, $from_name, $from_email, $autoresponder_subject_line, $autoresponder, array());
                }
            }

            // iTrac
            if ($campaign_service === 'itrac') {
                $itracSuccess = false;
                $itracErrorMessage = "";

                // error page - http://pub.itmems4.com/1/generic_error.html
                $fields = $mailchimp_mergevars;

                $fields = array(
                    "firstname" => "M",
                    "lastname" => "K",
                    //"email" => "michael@52pick-up.com",
                    "phonenumber" => "9055551234",
                    "mailing" => "672 Dupont Street - One last Test",
                    "city" => "Toronto",
                    "postalcode" => "M4M4M4",
                    "howdidyouhearaboutus" => "Collingwood Connection",
                    "householdincome" => "80kto100k",
                    "pricerange" => "$350,000 - $400,000",
                    "finance" => "no",
                    "housingstatus" => "rent",
                    "homesize" => "1000-1150sqft",
                    "bedrooms" => "2bedrooms",
                    "hometype" => "detached",
                    "whatisyouragecategory" => "36-45",
                    "purchaseas" => "Permanent-Residence",
                    "purchasewithin" => "6-9mths",
                    "movewithin" => "12-18mths",
                    "areyoucurrently" => "Unemployed",
                    "casl" => "yes",
                    );

                $fieldsPost = http_build_query($fields);

                $itrac_url = $external_action_code;
                $itrac_url = "https://beaches.itracmediav4.com/post?uuid=cce5f5c0-d1b9-4458-82f2-b3747d9ee2c3";
                
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $itrac_url);
                curl_setopt($ch,CURLOPT_POST, count($fields));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsPost);
                //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                //execute post
                $result = curl_exec($ch);

                //close connection
                curl_close($ch);
                
                if (strpos($result, $thank_you_page) === false) {
                    //echo "something failed";
                    $itracErrorMessage = "iTrac Error";
                } else {
                    $itracSuccess = true;
                }

                $retVal = listSuccessCheck($itracSuccess, $stepOne, $stepTwo, $is_ajax_form, $consent, $lastid, $thank_you_page, $itracErrorMessage);
            // MailChimp API
            } else if($campaign_service === 'mailchimp'){
                include(dirname(__DIR__).'/php/MailChimp.php');

                $MailChimp = new MailChimp($mailchimp_api_key);
                $mcStatus = 'subscribed';
                // if double opt in is true.. set status to 'pending' to force double opt in email to be sent
                if ($double_optin) {
                    $mcStatus = 'pending';
                }

                $mcSuccess = false;

                if (emailExistsMc($autoresponder_email, $mailchimp_list_id)) {
                    $subscriber_hash = $MailChimp->subscriberHash($autoresponder_email);

                    $patchArray = array(
                                        'status'        => $mcStatus,
                                        'merge_fields'  => $mailchimp_mergevars,
                                        'interests'     => $groupingsArr
                                    );
                    if ($stepTwo) {
                        $patchArray = array(
                                        'merge_fields'  => $mailchimp_mergevars,
                                        'interests'     => $groupingsArr
                                    );
                    }

                    $result = $MailChimp->patch("lists/$mailchimp_list_id/members/$subscriber_hash", $patchArray);

                    if ($MailChimp->success()) {
                        $mcSuccess = true;
                    } else {
                        $mcSuccess = false;
                    }
                } else {
                    $result = $MailChimp->post("lists/$mailchimp_list_id/members", array(
                        'email_address' => $autoresponder_email,
                        'status'        => $mcStatus,
                        'merge_fields'  => $mailchimp_mergevars
                    ));

                    if ($MailChimp->success()) {
                        $mcSuccess = true;
                    } else {
                        $mcSuccess = false;
                    }
                }

                $retVal = listSuccessCheck($mcSuccess, $stepOne, $stepTwo, $is_ajax_form, $consent, $lastid, $thank_you_page, $result['detail']);
            } else if($campaign_service === 'constant_contact') {
                include(dirname(__DIR__).'/php/ConstantContact/src/Ctct/autoload.php');
                include(dirname(__DIR__).'/php/ConstantContact/vendor/autoload.php');

                $ccSuccess = false;
                $ccErrorMessage = "";

                // Enter your Constant Contact APIKEY and ACCESS_TOKEN
                define("APIKEY", $constant_contact_api_key);
                define("ACCESS_TOKEN", $constant_contact_access_token);

                $cc = new ConstantContact(APIKEY);

                $action = "Getting Contact By Email Address";
                try {
                    // check to see if a contact with the email address already exists in the account
                    $response = $cc->contactService->getContacts(ACCESS_TOKEN, array("email" => $_POST['email']));
                    // !!!
                    // create a new contact if one does not exist
                    if (empty($response->results)) {
                        $action = "Creating Contact";

                        $contact = new Contact();
                        $contact->addList($constant_contact_list_id);

                        $contact = setConstantContactDetail($contact, $constant_contact_mergevars);

                        if (isset($form_data['email'])) {
                            $contact->addEmail($_POST['email']);
                        }

                        $returnContact = $cc->contactService->addContact(ACCESS_TOKEN, $contact, true);

                        $ccSuccess = true;
                    } else {
                        $action = "Updating Contact";

                        $contact = $response->results[0];
                        if ($contact instanceof Contact) {
                            $contact->addList($constant_contact_list_id);
                            
                            $contact = setConstantContactDetail($contact, $constant_contact_mergevars);

                            $returnContact = $cc->contactService->updateContact(ACCESS_TOKEN, $contact, true);

                            $ccSuccess = true;
                        } else {
                            $e = new CtctException();
                            $e->setErrors(array("type", "Contact type not returned"));
                            $ccErrorMessage = "Contact type not returned";
                            //throw $e;
                        }
                    }
                    // catch any exceptions thrown during the process and print the errors to screen

                } catch (CtctException $ex) {
                    //echo '<span class="label label-important">Error ' . $action . '</span>';
                    //echo '<div class="container alert-error"><pre class="failure-pre">';
                    $ccErrorMessage = print_r($ex->getErrors(), true);
                    //echo '</pre></div>';
                    //die();
                }

                $retVal = listSuccessCheck($ccSuccess, $stepOne, $stepTwo, $is_ajax_form, $consent, $lastid, $thank_you_page, $ccErrorMessage);
            } else {
                if ($stepOne) {
                    $retVal = array("success" => true, "retmsg" => $lastid, "consent" => strtolower($consent), "formID" => $form_post);
                } else if ($stepTwo) {
                    $retVal = array("success" => true, "retmsg" => $thank_you_page, "consent" => strtolower($consent), "formID" => $form_post);
                } else if ($is_ajax_form) {
                    $retVal = array("success" => true, "retmsg" => "Submitted.", "consent" => strtolower($consent), "formID" => $form_post);
                } else {
                    header('Location: ' . $thank_you_page);
                }
            }
        } else {
            // send email to dev's that a failure happened inserting into db
            $error_content = "An error has occurred submitting the following form:<br /><br />";
            $error_content .= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $error_content .= "<br /><br />Wordpress DB Error: " . $wpdb->last_error;
            $error_content .= "<br /><br />" . print_r($form_data, true);

            send_email("web@52pick-up.com", "52 Pickup Web", "web@52pick-up.com", "Error Submitting ACF Form", $error_content, array());
            if ($is_ajax_form) {
                //$retVal = array("success" => false, "retmsg" => "Database Error " . $wpdb->last_error, "consent" => strtolower($consent));
                $retVal = array("success" => false, "retmsg" => $form_error_message, "consent" => strtolower($consent), "formID" => $form_post);    
            } else {        
                echo "Database Error " . $wpdb->last_error;
            }
        }
    }
    if ($is_ajax_form) {
        echo json_encode($retVal);
        exit;
    }

    if ($stepOne || $stepTwo) {
        echo json_encode($retVal);
        exit;
    }

}
// die($is_ajax_form ? 'true' : 'false');

?>