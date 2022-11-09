<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,800" rel="stylesheet">
        <title>Insights PRO</title>
    </head>
    <body>
        <table style="border-collapse: collapse;width: 760px;max-width: 100%;margin: 40px auto;font-weight:400;font-family: 'Montserrat', sans-serif;border: 1px solid #dcdcdc;">
            <thead>
                <tr>
                    <th style="padding:20px 30px 0;text-align: center;">
                        <img src="<?php echo base_url(); ?>assets/bootstrap/images/emailer-logo.png" width="220" height="73" alt="insightsPRO Logo" style="display: block;margin-left: auto;margin-right: auto;max-width:60%;height: auto;" />
                    </th>
                </tr>
                <tr>
                    <th style="padding:30px 30px 0;text-align: center;text-transform: capitalize;font-size:16px;font-weight:600;color: #2E7D32;">Hi,</th>
                </tr>
                <tr>
                <?php if(isset($heading) && (!empty($heading))){?>
                    <th style="padding:20px 30px 20px;text-align: center;text-transform: capitalize;font-size:16px;font-weight:600;color: #232323;"><?php echo $heading;?></th>
                    <?php } else { ?>
                        <th style="padding:20px 30px 20px;text-align: center;text-transform: capitalize;font-size:16px;font-weight:600;color: #232323;">Form Response Alert</th>
                    <?php  } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:0 30px 30px;">
                        <table style="width:560px;max-width:90%;margin:0 auto;text-align:left;border-collapse:collapse;border: 0px solid transparent;">
                            <tbody>
                                <?php if(isset($call_id) && (!empty($call_id))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Call Id</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><a href="<?php echo $url; ?>"><?php echo $call_id;?></a></td>
                                    </tr>
                                <?php } ?>
                                <?php if(isset($call_date) && (!empty($call_date))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Call Date</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $call_date;?></td>
                                    </tr>
                                <?php } ?>
                                <?php if(isset($call_time) && (!empty($call_time))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Call Time</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $call_time;?></td>
                                    </tr>
                                <?php } ?>
                                <?php if(isset($evaluation_date) && (!empty($evaluation_date))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Evaluation Date & Time</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $evaluation_date;?></td>
                                    </tr>
                                <?php } ?>
                                <?php if(isset($ani) && (!empty($ani))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">ANI</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $ani;?></td>
                                    </tr>
                                <?php } ?>
                                <?php if(isset($supervisor) && (!empty($supervisor))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Supervisor</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $supervisor;?></td>
                                    </tr>
                                <?php } ?>
                                <?php if(isset($agent_name) && (!empty($agent_name))){?>
                                    <tr>
                                        <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Agent</th>
                                        <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $agent_name;?></td>
                                    </tr>
                                <?php } ?>
                                <!-- <?php if(isset($form_name) && (!empty($form_name))){?> -->
                                <!-- <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Form Name</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $form_name;?></td>
                                </tr> -->
                                <!-- <?php } ?> -->
                                <?php if(isset($custom1) && (!empty($custom1))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Line of Business</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $custom1;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($custom2) && (!empty($custom2))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Campaign</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $custom2;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($custom3) && (!empty($custom3))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Vendor</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $custom3;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($custom4) && (!empty($custom4))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Location</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $custom4;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($pre_fatal_score) && (!empty($pre_fatal_score))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Pre Auto-Fail Score</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $pre_fatal_score;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($total_score) ){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Total Score</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $total_score;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($audit_reason) && (!empty($audit_reason))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Audit Reason</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $audit_reason;?></td>
                                </tr>
                                <?php } ?>
                                <?php if(isset($overall_com) && (!empty($overall_com))){?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Comment</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $overall_com;?></td>
                                </tr>
                                <?php } ?>
                                <!-- <?php if(isset($submitted_by) && (!empty($submitted_by))){?> -->
                                <!-- <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 12px;vertical-align: middle;width:35;padding:10px 12px;font-weight:600;color:#565656;">Submitted By</th>
                                    <td style="border:1px solid #dcdcdc;padding:10px 12px;width:65%;font-size: 11px;vertical-align: middle;"><?php echo $submitted_by;?></td>
                                </tr> -->
                                <!-- <?php } ?> -->
                                <?php if(isset($mail_dynamic_data_measure) && (!empty($mail_dynamic_data_measure))){?>
                                <tr style="border-bottom: transparent;">
                                    <td colspan=2 style="text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 12px;font-weight: 400;line-height: 1.5;padding:10px 12px;"><?php echo $mail_dynamic_data_measure;?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 30px 30px;">
                        <?php if(isset($mail_dynamic_data_attribute) && (!empty($mail_dynamic_data_attribute))){?>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0px solid" width="560" style="margin:0 auto;width: 560px;max-width:90%;border-collapse: collapse;border:0px solid transparent;">
                                <thead>
                                    <tr style="border-bottom: transparent;">
                                        <th style="border:1px solid #dcdcdc;background-color:#efefef;font-size:12px;vertical-align:middle;padding:10px;font-weight:600;color:#565656;">Category Name</th>
                                        <th style="border:1px solid #dcdcdc;background-color:#efefef;font-size:12px;vertical-align:middle;padding:10px;font-weight:600;color:#565656;">Attribute Name</th>
                                        <th style="border:1px solid #dcdcdc;background-color:#efefef;font-size:12px;vertical-align:middle;padding:10px;font-weight:600;color:#565656;">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $mail_dynamic_data_attribute;?>
                                </tbody>
                            </table>
                        <?php }?>   
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#efefef;">
                        <table style="width:100%;border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <td style="padding:20px 30px 0;text-align: center;font-size: 12px;font-weight:600;">Need help? Check out our quick User Guide for answers to common questions.</td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 30px 15px;text-align: center;font-size: 12px;font-weight:500;color: #676767;line-height: 1.6;">Get in touch with one of our experts, you can reach us at <a href="mailto:support@mattsenkumar.com" style="text-decoration: underline;color:#333;">support@mattsenkumar.com</a> or visit our Online Support Center anytime, to resolve your query.</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 30px 15px;text-align: center;font-size: 12px;font-weight:500;color: #ff6e40;">This is an automatically generated email, please do not reply</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <!-- <td style="background-color: #000;padding:30px;">
                        <table style="border-collapse: collapse;width:100%;">
                            <tbody>
                                <tr>
                                    <td style="padding-bottom:10px;font-weight:600;font-size:13px;color:#fff;text-align:center;text-transform: capitalize;">Our mailing address</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:400;font-size:12px;color:#fff;text-align:center;">576 Glatt Circle, Woodburn, OR, Zip - 97071, USA</td>
                                </tr> -->
                                <!-- <tr>
                                    <td style="padding:20px 30px 0;font-weight:400;font-size:12px;color:#f5f5f5;text-align:center;line-height:1.6;">Want to change how you receive these emails? <br>You can <span style="text-decoration:underline;color:#f5f5f5;">update your preferences</span> or <span style="text-decoration:underline;color:#f5f5f5;">unsubscribe from this list</span>
                                    </td>
                                </tr> -->
                            <!-- </tbody>
                        </table>
                    </td> -->
                </tr>
                <!-- <tr>
                    <td style="padding:12px 30px;text-align:center;font-size: 11px;color: #fff;background-color: #232323;">Copyright © 2022, MattsenKumar LLC, All rights reserved.</td>
                </tr> -->
            </tbody>
        </table>
    </body>
</html>