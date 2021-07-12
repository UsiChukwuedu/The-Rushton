<?php
$custom_form_class = '';

$numSteps = 1;
// die($is_ajax_form ? 'true' : 'false');

if ($two_step_form) {
    $numSteps = 2;    
    $custom_form_class = "form-two-step";

    ?>
    <style>
    #registerform-step2 {
        display:none;        
    }
    </style>
    <?php

} else if ($is_ajax_form) {
    $custom_form_class = " is-ajax-form ";
}


$formStepId = "";
for ($steps = 1; $steps <= $numSteps; $steps++) {
if ($two_step_form) {
    $formStepId = "-step" . $steps;
}
if($custom_placeholders){ ?>
<style type="text/css" rel="stylesheet">
  /* @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
    /* IE10+ CSS here 
    .select-field .placeholder{display:none;} div.select-wrap.custom-placeholders select.ph {color:rgba(0, 0, 0, 0.35);} div.select-wrap.custom-placeholders select {color:#000;}
  }
  @supports (-ms-ime-align:auto) {
    /* IE EDGE SUPPORT 
    .select-field .placeholder{display:none;} div.select-wrap.custom-placeholders select.ph {color:rgba(0, 0, 0, 0.35);} div.select-wrap.custom-placeholders select {color:#000;}
  } */

  <style type="text/css" rel="stylesheet">
    @supports (-ms-ime-align:auto) {
      /* IE EDGE SUPPORT */
      div.select-wrap.custom-placeholders select:focus,
      div.select-wrap.custom-placeholders select:active {color:#000;-ms-transition:none}
    }
    @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
      /* IE10+ CSS here */
      .select-field .select-arrow{ z-index: 0;} .select-field .placeholder{display:none;} div.select-wrap.custom-placeholders select {color:#fff !important; transition: none !important;} div.select-wrap.custom-placeholders select:focus {color:#000 !important;}
    }
  </style>
  <!--[if IE 9]><style type="text/css" rel="stylesheet">.select-field .placeholder, .select-field .select-arrow{display:none;} div.select-wrap.custom-placeholders select {color:#fff !important;} div.select-wrap.custom-placeholders select:focus {color:#000 !important;}</style><![endif]-->

</style>

<?php } ?>

<form data-id="<?php echo $form_post; ?>" class="edgepad acf-form <?php if($custom_form_class): echo ' ' . $custom_form_class; endif; if($custom_placeholders): echo ' custom-placeholders'; endif; ?>" action="<?php if($external_action_code): echo trim($external_action_code); endif; ?>" method="POST" data-abide="ajax" id="registerform<?php echo $formStepId; ?>" novalidate="">
<?php 
    if(!isSet($form_post)):
        $form_post = $post->ID;
        // die("ID: $form_post");
    endif;
    ?>
     <input type="hidden" name="formId" value="<?php echo $form_post; ?>">
    <?php

    if ($two_step_form && $steps == 2) {
        ?>
        <div style="display:none;">
            <input type="text" name="dbid" id="dbid" value="" />
            <input type="email" name="emailsteptwo" id="emailsteptwo" value="" />
        </div>
        <?php
    }

    $campaign_source = false;
    if (isset($_GET['src']) && trim($_GET['src']) != "") {
        $campaign_source = true;
    } elseif (isset($_GET['utm_source']) && trim($_GET['utm_source']) != "") {
        $campaign_source = true;
    } elseif (isset($_GET['utm_campaign']) && trim($_GET['utm_campaign']) != "") {
        $campaign_source = true;
    } else if (isset($_SERVER['HTTP_REFERER']) && trim($_SERVER['HTTP_REFERER']) !== "") {
        $campaign_source = true;
    }
    while (have_rows('form', $form_post)): the_row();
        if ($two_step_form && $steps == 1 && get_row_layout() == 'start_step_two') {
                break;
        }

        if ($two_step_form && $steps == 1) {
            if( get_row_layout() == 'step_one_submit' ) { ?>
                <div class="<?php the_sub_field('css_classes'); ?>">
                    <input type="submit" class="block-button<?php if($disable_after_submit): echo ' auto-disable'; endif; ?>" name="step_one_submit" id="step_one_submit" value="<?php the_sub_field('button_text'); ?>" />
                </div>
            <?php
            }
        }

        if ($two_step_form && $steps == 1) {
        } else if ($two_step_form && $steps == 2) {
        } else if ($two_step_form) {
            continue;
        }

        /* -----| TEXT FIELD |----- */
        if( get_row_layout() == 'text_field' ): ?>
        <div class="form-field text-field<?php echo ' ' . get_sub_field('css_classes'); if(get_sub_field('conditional')): echo ' conditional'; endif; ?>"<?php if(get_sub_field('conditional')): echo ' cond-ext="' . get_sub_field('condition_field') . '" cond-val="' . get_sub_field('condition_value') . '"'; endif; ?>>
            <?php if($display_field_labels && get_sub_field('label_text')): echo '<span class="form-label">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <?php /*
            $text_value = "";
            if(get_sub_field('field_name') == "campaign_source") {
                if (isset($_GET['src']) && trim($_GET['src']) != "") {
                    $text_value = "Source: " . $_GET['src'];
                    $campaign_source = true;
                } elseif (isset($_GET['utm_source']) && trim($_GET['utm_source']) != "") {
                    $text_value = "UTM Source: " . $_GET['utm_source'];
                    $campaign_source = true;
                } elseif (isset($_GET['utm_campaign']) && trim($_GET['utm_campaign']) != "") {
                    $text_value = "Google Campaign: " . $_GET['utm_campaign'];
                    $campaign_source = true;
                } else if (isset($_SERVER['HTTP_REFERER']) && trim($_SERVER['HTTP_REFERER']) !== "") {
                    $text_value = "Referrer: " . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                    $campaign_source = true;
                }
            } */ ?>
            <?php if($custom_placeholders): echo '<div class="placeholder-wrap">'; endif; ?>
            <?php if(get_sub_field('conditional')): echo '<div class="cond-holder'; if(get_sub_field('required')): echo ' reqfield'; endif; echo '"><div style="height: 100%; position:relative;">'; endif; ?>
            <input type="<?php the_sub_field('type'); ?>" name="<?php the_sub_field('field_name'); ?>" id="<?php the_sub_field('field_name'); ?>" value="<?php echo $text_value; ?>"<?php if(!$custom_placeholders && $display_placeholders && get_sub_field('placeholder_text')): ?> placeholder="<?php if(get_sub_field('required') && $req_star === 'left'): echo '* '; endif; echo get_sub_field('placeholder_text'); if(get_sub_field('required') && $req_star === 'right'): echo ' *'; endif; echo '"'; endif; ?><?php if(get_sub_field('required') && !get_sub_field('conditional')): echo ' required'; endif; ?> />
            <?php if($custom_placeholders): echo '<span class="placeholder">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('placeholder_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
            <?php if(get_sub_field('conditional')): echo '</div></div>'; endif; ?>
            <?php if($custom_placeholders): echo '</div>'; endif; ?>
        </div>
        <?php

        /* -----| SELECT FIELD |----- */
        elseif( get_row_layout() == 'select_field' ): ?>
        <div class="form-field select-field<?php echo ' ' . get_sub_field('css_classes'); ?>">
            <?php if($display_field_labels && get_sub_field('label_text')): echo '<span class="form-label">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <div class="select-wrap<?php if($custom_placeholders): echo ' custom-placeholders'; endif; ?>">
                <select name="<?php the_sub_field('field_name'); ?>" id="<?php the_sub_field('field_name'); ?>"<?php if(get_sub_field('required')): echo ' required'; endif; ?>>
                    <option value=""><?php if($display_placeholders && get_sub_field('placeholder_text')): if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('placeholder_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; endif; ?></option>
                    <?php if(have_rows('choices')): while (have_rows('choices')): the_row(); ?>
                        <option value="<?php if(get_sub_field('value_same_as_text')): echo get_sub_field('text'); else: echo get_sub_field('value'); endif; ?>">
                            <?php the_sub_field('text');?>
                        </option>
                    <?php endwhile; endif; ?>
                </select>
                <?php if($custom_placeholders): echo '<span class="placeholder">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('placeholder_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
                <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
                <div class="select-arrow"></div>
            </div>
        </div>
        <?php

        /* -----| TEXTAREA |----- */
        elseif( get_row_layout() == 'textarea' ): ?>
        <div class="form-field<?php echo ' ' . get_sub_field('css_classes'); ?>">
            <?php if($display_field_labels && get_sub_field('label_text')): echo '<span class="form-label">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <textarea<?php if($display_placeholders && get_sub_field('placeholder_text')): ?> placeholder="<?php if(get_sub_field('required') && $req_star === 'left'): echo '* '; endif; echo get_sub_field('placeholder_text'); if(get_sub_field('required') && $req_star === 'right'): echo ' *'; endif; echo '"'; endif; ?> name="<?php the_sub_field('field_name'); ?>"<?php if(get_sub_field('required')): echo ' required'; endif; if(get_sub_field('max_length')): echo ' maxlength="' . get_sub_field('max_length') . '"'; endif; ?> ></textarea>
            <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
        </div>
        <?php

        /* -----| CHECKBOXES |----- */
        elseif( get_row_layout() == 'checkboxes' ): $checkbox_field_name = get_sub_field('checkbox_field_name'); $checkbox_css_classes = get_sub_field('checkbox_css_classes'); $required = get_sub_field('required'); $count = 1; ?>
        <div class="form-field check-field <?php echo $checkbox_field_name . '_check_required_wrapper'; ?>  <?php the_sub_field('css_classes'); if(get_sub_field('vertical')): echo ' vertical'; endif; ?>">
            <?php if($display_field_labels && get_sub_field('label_text')): echo '<span class="form-label">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <fieldset>
                <?php if(have_rows('choices')): $count = 1; while (have_rows('choices')): the_row(); ?>
                <label<?php if($checkbox_css_classes !== ''): echo ' class="' . $checkbox_css_classes . '"'; endif; echo ' for="' . $checkbox_field_name . $count . '"'; ?>>
                    <input type="checkbox" name="<?php echo $checkbox_field_name; ?>[]" id="<?php echo $checkbox_field_name . $count; ?>" value="<?php if(get_sub_field('value_same_as_label')): echo get_sub_field('label_text'); else: echo get_sub_field('value'); endif; ?>"<?php if($required): echo ' data-validator="' . $checkbox_field_name . '_check_required" '; endif; if(get_sub_field('selected')): echo ' checked'; endif; ?>>
                    <span><?php the_sub_field('label_text'); ?></span>
                </label>
                <?php $count++; endwhile; endif; ?>
                <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
            </fieldset>
        </div>
        <?php if ($required) { 
            $GLOBALS['checkboxRequiredScript'] = "<script>
                Foundation.Abide.defaults.validators['" . $checkbox_field_name . "_check_required'] =
                    function(el,required,parent) {
                        var is" . $checkbox_field_name . "Checked = false;
                        $.each( $('." . $checkbox_field_name . "_check_required_wrapper input'), function() {
                            if ( $(this).is(':checked') ) {
                                is" . $checkbox_field_name . "Checked = true;
                            }
                        });

                        return is" . $checkbox_field_name . "Checked;
                    };      
                </script>";

        } ?>
        <?php

        /* -----| SINGLE CHECKBOX |----- */
        elseif( get_row_layout() == 'single_checkbox' ): $field_name = get_sub_field('field_name'); ?>
        <div class="form-field single-check <?php the_sub_field('css_classes'); ?>">
            <fieldset>
                <label for="<?php echo $field_name; ?>">
                    <input type="checkbox" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php the_sub_field('checked_value'); ?>" <?php if(get_sub_field('required')): echo ' required'; endif; ?>>
                    <span><?php the_sub_field('label_text'); ?></span>
                </label>
                <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
            </fieldset>
        </div>
        <?php

        /* -----| RADIO |----- */
        elseif( get_row_layout() == 'radio' ): $field_name = get_sub_field('field_name'); $css_classes = get_sub_field('css_classes'); $required = get_sub_field('required'); ?>
        <div class="form-field radio-field <?php the_sub_field('css_classes'); if(get_sub_field('vertical')): echo ' vertical'; endif; ?>">
            <?php if($display_field_labels && get_sub_field('label_text')): echo '<span class="form-label">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <fieldset>
                <?php if(have_rows('choices')): $count = 1; while (have_rows('choices')): the_row(); ?>
                <?php echo get_sub_field('checked')?>
                    <label
                            class="<?php echo $css_classes; if(get_sub_field('selected')): echo 'checked'; endif; ?>"
                            for="<?php echo ($field_name . $count) ?>">
                        <input type="radio" name="<?php echo $field_name; ?>" id="<?php echo $field_name . $count; ?>" value="<?php if(get_sub_field('value_same_as_label')): echo get_sub_field('label_text'); else: echo get_sub_field('value'); endif; ?>"<?php if($required): echo ' required'; endif; if(get_sub_field('selected')): echo ' checked'; endif; ?>>
                        <span><?php the_sub_field('label_text'); ?></span>
                    </label>
                <?php $count++; endwhile; endif; ?>
                <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
            </fieldset>
        </div>
        <?php
        
        /* -----| CONDITIONAL TEXTFIELD |----- */
        elseif( get_row_layout() == 'conditional_textfield' ): $radio_field_name = get_sub_field('radio_field_name'); $radio_css_classes = get_sub_field('radio_css_classes'); $required_radio = get_sub_field('required_radio'); $required_textfield = get_sub_field('required_textfield'); ?>
        <div class="form-field cond-text<?php if(get_sub_field('css_classes')): echo ' ' . get_sub_field('css_classes'); endif; if(get_sub_field('vertical')): echo ' vertical'; endif; ?>">
            <?php if($display_field_labels && get_sub_field('label_text')): echo '<span class="form-label">'; if(get_sub_field('required_radio') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required_radio') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
            <fieldset>
                <?php if(have_rows('choices')): $count = 1; while (have_rows('choices')): the_row(); ?>
                    <label<?php if($radio_css_classes !== ''): echo ' class="' . $radio_css_classes . '"'; endif; echo ' for="' . $radio_field_name . $count . '"'; ?>>
                        <input type="radio" name="<?php echo $radio_field_name; ?>" id="<?php echo $radio_field_name . $count; ?>" value="<?php if(get_sub_field('value_same_as_label')): echo get_sub_field('label_text'); else: echo get_sub_field('value'); endif; ?>"<?php if(get_sub_field('selected')): echo ' checked';  endif; if($required_radio): echo ' required'; endif; if(get_sub_field('show_textfield')): echo ' class="show-textfield"'; endif; ?>>
                        <span><?php the_sub_field('label_text'); ?></span>
                    </label>
                <?php $count++; endwhile; endif; ?>
                <?php if($display_error_labels): ?><span class="form-error cond-error"><?php the_sub_field('radio_error_text'); ?></span><?php endif; ?>
            </fieldset>
            <div class="cond-holder">
                <div<?php if(get_sub_field('textfield_css_classes')): echo ' class="inner'; if(get_sub_field('textfield_css_classes')) echo ' ' . get_sub_field('textfield_css_classes'); echo '"'; endif; ?>>
                    <?php if($custom_placeholders): echo '<div class="placeholder-wrap">'; endif; ?>
                    <input type="text" name="<?php the_sub_field('textfield_field_name'); ?>" value="" <?php if(!$custom_placeholders && $display_placeholders && get_sub_field('textfield_placeholder_text')): ?> placeholder="<?php if($required_textfield && $req_star === 'left'): echo '* '; endif; echo get_sub_field('textfield_placeholder_text'); if($required_textfield && $req_star === 'right'): echo ' *'; endif; endif; ?>"
                        <?php if($required_textfield): echo ' class="reqfield" required'; endif; ?> 
                    />
                    <?php if($custom_placeholders): echo '<span class="placeholder">'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('textfield_placeholder_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
                    <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('textfield_error_text'); ?></span><?php endif; ?>
                    <?php if($custom_placeholders): echo '</div>'; endif; ?>
                </div>
            </div>
        </div>
        <?php

        /* -----| HTML |----- */
        elseif( get_row_layout() == 'html' ): ?>
            <?php the_sub_field('code'); ?>
        <?php

        /* -----| CONSENT |----- */
        elseif( get_row_layout() == 'consent' ): ?>

        <div class="form-field consent-field <?php the_sub_field('css_classes'); ?>">
            <fieldset>
                <label for="consent">
                    <input type="checkbox" name="consent" id="consent" value="yes"<?php if(get_sub_field('required')): echo ' required'; endif; ?>/>
                    <?php if(get_sub_field('label_text')): echo '<span>'; if(get_sub_field('required') && $req_star === 'left'): echo '<span class="req-star">* </span>'; endif; echo get_sub_field('label_text'); if(get_sub_field('required') && $req_star === 'right'): echo '<span class="req-star"> *</span>'; endif; echo '</span>'; endif; ?>
                </label>
            </fieldset>
        </div>
        <?php

        /* -----| reCAPTCHA |----- */
        elseif( get_row_layout() == 'recaptcha' ): ?>
        
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <div class="form-field recaptchafield<?php echo ' ' . get_sub_field('css_classes'); ?>">
            <div class="g-recaptcha" data-sitekey="<?php the_sub_field('client_side_key'); ?>" data-callback="reCaptchaValid" data-theme="<?php if(get_sub_field('theme') == 'light'): echo 'light'; else: echo 'dark'; endif; ?>"></div>
            <?php if($display_error_labels): ?><span class="form-error"><?php the_sub_field('error_text'); ?></span><?php endif; ?>
        </div>
        <?php

        /* -----| SUBMIT BUTTON |----- */
        elseif( get_row_layout() == 'submit_button' ): ?>
        <div class="<?php the_sub_field('css_classes'); ?> submit-container">
            <input type="submit" name="submit" id="submit" value="<?php the_sub_field('button_text'); ?>" class="block-button<?php if($disable_after_submit): echo ' auto-disable'; endif; ?>" />
            <?php if(get_sub_field('honeypot')): ?>
            <label class="honeypot-wrap"><input type="text" id="verification-name" value=""></label>
            <?php endif; ?>
            <?php if($input_error_message !== ''): ?>
            <div data-abide-error class="alert callout" style="display: none;">
                <p><i class="fi-alert"></i><?php echo $input_error_message ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php

        /* -----| DATABASE TABLE BUILDER |----- */
        elseif( get_row_layout() == 'database_table_creator' ): ?>
        <div class="large-12 columns">
            <?php echo $sql; ?>
        </div>
        
    <?php endif; endwhile; ?>
</form>
<?php } /* end for numsteps */ ?>

<?php /* -----| AJAX THANK YOU MESSAGE |----- */
if($is_ajax_form || $two_step_form): ?>
<div class="large-12 medium-12 columns small-centered acf-success" data-id="<?php echo $form_post; ?>">
    <div class="pad-bottom">
        <h2><?php echo $thank_you_heading; ?></h2>
    </div>
    <p class="msg consent-yes"><?php echo $thank_you_message_with_consent; ?></p>
    <p class="msg consent-no"><?php echo $thank_you_message_without_consent; ?></p>
    <p class="response"></p>
    <?php /* <a href="<?php echo home_url(); ?>" class="block-button">BACK TO HOME</a> */ ?>
</div>
<?php endif; ?>
