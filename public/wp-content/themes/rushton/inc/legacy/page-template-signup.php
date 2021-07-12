<?php 
/************************
 Template Name: Signup
 ***********************/

function cleanParams($param) {
    // strip any html
    $param = strip_tags($param);
    $param = esc_sql($param);

    return $param;
}
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Text Fields
    $firstname = cleanParams($_POST['firstname']);
    $firstname = str_replace('\\', '', $firstname);
    if (!filter_var($firstname, FILTER_SANITIZE_STRING) || $firstname == "") {
        $error .= "<br />Please enter a valid first name.";
    }

    $lastname = cleanParams($_POST['lastname']);
    $lastname = str_replace('\\', '', $lastname);
    if (!filter_var($lastname, FILTER_SANITIZE_STRING) || $lastname == "") {
        $error .= "<br />Please enter a valid last name.";
    }

    $email = cleanParams($_POST['email']);
    $email = str_replace('\\', '', $email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $email == "") {
        $error .= "<br />Please enter a valid email address.";
    }

    $phone = cleanParams($_POST['phone']);
    $phone = str_replace('\\', '', $phone);
    if (!filter_var($phone, FILTER_SANITIZE_STRING) || $phone == "") {
        $error .= "<br />Please enter a valid phone number.";
    }

    $company = cleanParams($_POST['company']);
    $company = str_replace('\\', '', $company);
    if (!filter_var($company, FILTER_SANITIZE_STRING) || $company == "") {
        $error .= "<br />Please enter a valid company name.";
    }

    $address = cleanParams($_POST['address']);
    $address = str_replace('\\', '', $address);
    if (!filter_var($address, FILTER_SANITIZE_STRING) || $address == "") {
        $error .= "<br />Please enter a valid address.";
    }

    // Select Fields
    $information = cleanParams($_POST['information']);
    $suitesize = cleanParams($_POST['suitesize']);
    $suiteprice = cleanParams($_POST['suiteprice']);
    $howheard = cleanParams($_POST['howheard']);

    // Text Area
    $message = cleanParams($_POST['message']);
    $message = str_replace("\\r\\n", "\n", $message);
    $message = str_replace('\\', '', $message);
    if (!filter_var($message, FILTER_SANITIZE_STRING) || $message == "") {
        $error .= "<br />Please enter a message.";
    }

    if(isSet($_POST['consent'])){
        $consent = 'yes';
    } else {
         $consent = 'no';
    }
    $doi = true;
    if ($consent == 'yes') {
        $doi = false;
    }

    if (trim($error) == "") {
        $insert = $wpdb->insert( 'form_submissions', 
            array(
                "firstname" => $firstname,
                "lastname" => $lastname,
                "company" => $company,
                "address" => $address,
                "phone" => $phone,
                "email" => $email,
                "information" => $information,
                "suitesize" => $suitesize,
                "suiteprice" => $suiteprice,
                "howheard" => $howheard,
                "message" => $message,
                "consent" => $consent,
                "ipaddress" => $_SERVER['REMOTE_ADDR'],
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        if ($insert == 1) {
            $recipient = get_the_field('form_recipient_email', 'option');
            $subject = "New contact $firstname $lastname using the !!!####_COMPANY_NAME_####!!! registration form";
            $email_content  = "Name: $firstname $lastname<br>";
            $email_content .= "Company: $company<br>";
            $email_content .= "Address: $address<br>";
            $email_content .= "Email: $email<br>";
            $email_content .= "Phone: $phone<br>";;
            $email_content .= "Interested in: $information<br>";
            $email_content .= "Suitesize: $suitesize<br>";
            $email_content .= "Price range: $suiteprice<br>";
            $email_content .= "How they heard: $howheard<br>";
            $email_content .= "Questions or comments: $howheard<br>";
            $email_content .= "Message: $message<br>";
            $email_content .= "Consent?: $consent<br>";

            $email_headers = "From: !!!####_COMPANY_NAME_####!!! <!!!####_COMPANY_EMAIL_ADDRESS_####!!!>" . "\r\n";
            $email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            if( have_rows('form_recipient_emails_cc','option') ):
                  while ( have_rows('form_recipient_emails_cc','option') ) : the_row();
                    $email_headers .= 'Cc: ' . get_sub_field('email_address') . "\r\n";
                  endwhile;
              endif;  
            // $email_headers = 'Bcc: @52pick-up.com' . "\r\n";

            if (mail($recipient, $subject, $email_content, $email_headers)) { }

            $recipient = $email;
            $subject = "Thank you for joining the !!!####_COMPANY_NAME_####!!! Community";
            $email_headers = "From: !!!####_COMPANY_NAME_####!!! <!!!####_COMPANY_EMAIL_ADDRESS_####!!!>" . "\r\n"; /* <======= ALWAYS KEEP CARRIAGE RETURNS IN DOUBLE QUOTES!!! */
            $email_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $email_content = 
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <title>!!!####_COMPANY_NAME_####!!!</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1" />
                    </head>

                    <body bgcolor="#f1f1f1" style="-ms-text-size-adjust: none !important;">

                    <table width="640" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-size:0px;border-spacing: 0;-ms-text-size-adjust: none !important;">
                    <tr cellpadding="0" cellspacing="0">
                        <td style="border-spacing:0;border-collapse: collapse;">
                            <center>';
            $email_content .= '!!!####_EMAIL_CONTENT_####!!!';
            $email_content .= '</center></td></tr></table></body></html>';

            if ($consent == 'yes') { 
                if(mail($recipient, $subject, $email_content, $email_headers)) { }
            }

            // Mailchimp API to subscribe user to mailing list
            include("MailChimp.php");
            $mailChimpAPIKey = '!!! MAIL CHIMP API KEY !!!';
            $MailChimp = new \Drewm\MailChimp($mailChimpAPIKey);

            $mergevars = array(
                'FNAME'=>$firstname, 
                'LNAME'=>$lastname,
                'ADDRESS'=>$address,
                'COMPANY'=>$company,
                'EMAIL'=>$email,
                'PHONE'=>$phone,
                'INFO'=>$information,
                'SUITESIZE'=>$suitesize,
                'SUITEPRICE'=>$suiteprice,
                'HOWHEARD'=>$howheard,
                'CONSENT'=>$consent,
                'IPADDRESS'=> $_SERVER['REMOTE_ADDR'],
            );

            $result = $MailChimp->call('lists/subscribe', array(
                'id'                => '!!! MAIL CHIMP LIST ID !!!',
                'email'             => array('email'=>$email),
                'merge_vars'        => $mergevars,
                'double_optin'      => $doi,
                'update_existing'   => true,
                'replace_interests' => false,
                'send_welcome'      => false,
            ));

            if (array_key_exists('status', $result) && $result['status'] == 'error') {
                echo "Error: " . $result['error'];
            } else {
                if ($consent == 'yes') {
                    header('Location: ' . get_permalink(0));
                } else {
                    header('Location: ' . get_permalink(0) . '?con');
                }
                exit;
            }
        }
    }
}

get_header(); ?>

<section id="wrapper">

    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>

        	<div class="banner edgepad">
                <div class="row">
                    <div class="large-6 large-centered columns r">
                        <form action="" method="POST" data-abide="ajax" id="registerform" novalidate="">
                            <div class="form-elements">
                                <div class="row">
                                    <div class="large-6 medium-6 columns">
                                        <div>
                                        <label>
                                            <input type="text" name="firstname" id="firstname" value="" placeholder="First Name*" required />
                                        </label>
                                        </div>
                                    </div>
                                    <div class="large-6 medium-6 columns">
                                        <label>
                                            <input type="text" name="lastname" id="lastname" value="" placeholder="Last Name*" required />
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="large-12 medium-12 columns">
                                        <label>
                                            <input type="text" name="company" id="company" value="" placeholder="Company Name*" required />
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="large-12 medium-12 columns">
                                        <label>
                                            <input type="text" name="address" id="address" value="" placeholder="Address*" required />
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="large-6 medium-6 columns">
                                        <label>
                                            <input type="text" name="phone" id="phone" value="" placeholder="Phone*" required  />
                                        </label>
                                    </div>
                                    <div class="large-6 medium-6 columns">
                                        <label>
                                            <input type="email" name="email" id="email" value="" placeholder="Email*" required />
                                        </label>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="large-6 medium-6 columns input-row">
                                        <select name="information" id="information" class="ph">
                                            <option value="">I am interested in information about:</option>
                                            <option value="Purchasing a Diamante residence">Purchasing a Diamante residence</option>
                                            <option value="Renting a Diamante residence">Renting a Diamante residence</option>
                                            <option value="Investing in a Diamante project">Investing in a Diamante project</option>
                                        </select>
                                    </div>
                                    <div class="large-6 medium-6 columns input-row">
                                        <select name="suitesize" id="suitesize" class="ph">
                                            <option value="">Suite Size Range</option>
                                            <option value="700-900">700-900</option>
                                            <option value="900-1100">900-1100</option>
                                            <option value="1100-1300">1100-1300</option>
                                            <option value="1300-1500">1300-1500</option>
                                            <option value="1500-2000">1500-2000</option>
                                            <option value="over 2000 SF">over 2000 SF</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row input-row">
                                    <div class="large-6 medium-6 columns">
                                        <select name="suiteprice" id="suiteprice" class="ph">
                                            <option value="">Suite Price Range</option>
                                            <option value="Under $300K">Under $300K</option>
                                            <option value="$300-$400K">$300-$400K</option>
                                            <option value="$400-$500K">$400-$500K</option>
                                            <option value="$500-$600K">$500-$600K</option>
                                            <option value="$600-$700K">$600-$700K</option>
                                            <option value="Over $700K">Over $700K</option>
                                        </select>
                                    </div>
                                    <div class="large-6 medium-6 columns">
                                        <select name="howheard" id="howheard" class="ph">
                                            <option value="">How did you hear about us?</option>
                                            <option value="Toronto Life">Toronto Life</option>
                                            <option value="Condo Life">Condo Life</option>
                                            <option value="Google">Google</option>
                                            <option value="Word of mouth">Word of mouth</option>
                                            <option value="Newspaper">Newspaper</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row input-row">
                                    <div class="large-12 columns">
                                        <label>
                                            <textarea placeholder="Message" name="message"></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="row consent-row">
                                    <div class="large-12 columns check-col">
                                        <label id="consent-label" for="consent">
                                            <input type="checkbox" name="consent" id="consent" value="yes"/>
                                            <span>I would like to receive updates and info about COMPANY NAME</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row submit-row">
                                    <div class="large-12 columns">
                                        <div class="g-recaptcha" data-sitekey="!!! reCaptcha Key !!!" data-callback="reCaptchaValid"></div>
                                    </div>
                                    <div class="large-12 columns">
                                        <div class="submit-wrap">
                                            <input type="submit" name="submit-form" id="submit-form" value="Submit" />
                                            <label class="honeypot-wrap"><input type="text" name="honeypot" id="honeypot" value=""></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
                        
        <?php endwhile;?>    
    <?php else:?>
        <h2>No posts</h2>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
