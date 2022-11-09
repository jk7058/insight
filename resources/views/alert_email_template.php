<!DOCTYPE html>
<html>
	<head>
		<title>Alert Email Template</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,800" rel="stylesheet">
        <style>
            html, body {padding: 0 !important;margin: 0 auto !important;height: 100% !important;width: 100% !important;}
            * {-ms-text-size-adjust: 100%;}
            table, td {mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;}
            img {-ms-interpolation-mode: bicubic;}
            a {text-decoration: none;}
        </style>
	</head>
	<body width="100%" style="margin: 0 !important;padding: 0 !important; mso-line-height-rule: exactly;background: #fcfcfc;">
		<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border-collapse: collapse;font-weight:400;font-family: Montserrat, sans-serif;background: #fcfcfc;">
            <tbody>
                <tr style="border-bottom: transparent;">
                    <td style="text-align: center;vertical-align: middle; padding:0px;">
                            <tbody>
                                <!-- Emailer Logo Start -->
                                <tr style="border-bottom: transparent;">
                                    <td class="emailer-logo" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/bootstrap/images/emailer-logo.png" width="220" height="73" alt="insightsPRO Logo" style="display: block;margin-left: auto;margin-right: auto;width:60%;max-width:160px;height: auto;" />
                                    </td>
                                </tr>
                                <!-- Emailer Logo End -->
                                <!-- Emailer Header Start -->
                                <tr style="border-bottom: transparent;">
                                    <td class="emailer-header">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                            <tbody>
                                                <tr style="border-bottom: transparent;">
                                                    <td style="padding-left:20px; font-family: Montserrat, sans-serif;padding-bottom:5px;text-align:left;vertical-align:middle;font-size: 13px;line-height: 1.5;color: #232323;padding:10px;">
                                                      <b> Hi Team  </b>
                                                    </td>
                                                </tr>
                                                <tr style="border-bottom: transparent;">
                                                    <td style="text-align: center;vertical-align: middle;padding-top:20px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="520" style="margin:0 auto;width:100%;max-width:520px;border-collapse: collapse;border:0px solid transparent;">
                                                            <tbody>
                                                                <tr style="border-bottom: transparent;">
                                                                    <td style="font-family: Montserrat, sans-serif;padding-bottom:5px;text-align:center;vertical-align:middle;font-size: 13px;line-height: 1.5;color: #232323;padding:10px;"><b><?php echo $message_temp;?></b></td>
                                                                </tr>
                                                            </tbody>
                                                            <?php if(isset($form_name) && (!empty($form_name))){?>
                                                                <tr>
                                                                    <td style="font-family: Montserrat, sans-serif;padding-top:3px;text-align:center;vertical-align:middle;font-size: 13px;line-height: 1.5;color: #232323;">
                                                                        <strong style="font-weight:600">Form Name : </strong>
                                                                        <span style="font-weight:400"><?php echo $form_name;?></span>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>
                                                                <?php if(isset($submitted_by) && (!empty($submitted_by))){?>
                                                                <tr>
                                                                    <td style="font-family: Montserrat, sans-serif;padding-top:3px;text-align:center;vertical-align:middle;font-size: 13px;line-height: 1.5;color: #232323;">
                                                                        <strong style="font-weight:600">Submitted By : </strong>
                                                                        <span style="font-weight:400"><?php echo $submitted_by;?></span>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>
                                                                <?php if(isset($overall_com) && (!empty($overall_com))){?>
                                                                <tr>
                                                                    <td style="font-family: Montserrat, sans-serif;padding-top:3px;text-align:center;vertical-align:middle;font-size: 13px;line-height: 1.5;color: #232323;">
                                                                        <strong style="font-weight:600">Overall Comment : </strong>
                                                                        <span style="font-weight:400"><?php echo $overall_com;?></span>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>
                                                                <?php if(isset($mail_dynamic_data_measure) && (!empty($mail_dynamic_data_measure))){?>
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                                    <tbody>
                                                                        <tr style="border-bottom: transparent;">
                                                                            <td style="text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 13px;font-weight: 400;line-height: 1.5;padding:10px;"><?php echo $mail_dynamic_data_measure;?></td>
                                                                        </tr>
                                                                    </tbody>
                                                                    
                                                                </table>
                                                             <?php }?>
                                                             <?php if(isset($mail_dynamic_data_attribute) && (!empty($mail_dynamic_data_attribute))){?>
                                                            <table role="presentation" cellpadding="0" cellspacing="0" border="solid 1px" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                            <thead>
                                                                <tr style="border-bottom: transparent;">
                                                                    <th style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 13px;font-weight: 400;line-height: 1.5;">Category Name</th>
                                                                    <th style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 13px;font-weight: 400;line-height: 1.5;">Attribute Name</th>
                                                                    <th style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 13px;font-weight: 400;line-height: 1.5;">Option</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php echo $mail_dynamic_data_attribute;?>
                                                            </tbody>
                                                            </table>
                                                        <?php }?>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Emailer Header End -->
                                <!-- Emailer Body Start -->
                                <!-- Emailer Body End -->
                                <!-- Emailer Footer Start -->
                                <tr>
                                    <td class="emailer-footer" style="padding:0px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                            <tbody>
                                                <tr style="border-bottom: transparent;">
                                                    <td style="background-color:#efefef;">
                                                       
                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color:#efefef;padding-left:20px;padding-right:20px;padding-top:20px;padding-bottom:20px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#232323;font-size: 13px;font-weight: 600;line-height: 1.5;">Need help? Check out our quick User Guide for answers to common questions.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 13px;font-weight: 400;line-height: 1.5;">Get in touch with one of our experts, you can reach us at <a style="text-decoration: underline;color:#232323;" href="mailto:support@insightspro.io">support@insightspro.io</a> or visit our Online Support Center anytime, day or night, to resolve your query.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ff6e40;font-size: 13px;font-weight: 400;line-height: 1.5;">This is an automatically generated email, please do not reply</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #000;padding-left:20px;padding-right:20px;padding-top:20px;padding-bottom:20px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom:6px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ffffff;font-size: 13px;font-weight: 600;line-height: 1.5;">Our Mailing Address</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ffffff;font-size: 13px;font-weight: 400;line-height: 1.5;">576 Glatt Circle, Woodburn, OR, Zip - 97071, USA</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="text-align: center;vertical-align: middle;">
                                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="114" style="margin:0 auto;width:114px;border-collapse: collapse;border:0px solid transparent;">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td style="text-align: center;vertical-align: middle;padding-left:3px;padding-right:3px;"><a href="#" style="color:#ffffff;text-decoration:none;display: block;"><img style="display:block;margin: 0;width:32px;height:32px;border-radius: 50%;font-size: 0px;background:#000000;" src="<?php echo base_url();?>assets/images/facebook.png" alt="facebook"></a></td>
                                                                                    <td style="text-align: center;vertical-align: middle;padding-left:3px;padding-right:3px;"><a href="#" style="color:#ffffff;text-decoration:none;display: block;"><img style="display:block;margin: 0;width:32px;height:32px;border-radius: 50%;font-size: 0px;background:#000000;" src="<?php echo base_url();?>assets/images/twitter.png" alt="twitter"></a></td>
                                                                                    <td style="text-align: center;vertical-align: middle;padding-left:3px;padding-right:3px;"><a href="#" style="color:#ffffff;text-decoration:none;display: block;"><img style="display:block;margin: 0;width:32px;height:32px;border-radius: 50%;font-size: 0px;background:#000000;" src="<?php echo base_url();?>assets/images/kalbos.png" alt="kalbos"></a></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color:#464646;padding-left:20px;padding-right:20px;padding-top:12px;padding-bottom:12px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ffffff;font-size: 11px;font-weight: 400;line-height: 1.5;">Copyright © 2019, InsightsPRO, All rights reserved.</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Emailer Footer End -->
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
		</table>
	</body>
</html>