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
        <table style="border-collapse: collapse;width: 760px;margin: 40px auto;font-weight:400;font-family: 'Montserrat', sans-serif;border: 1px solid #dcdcdc;">
            <thead>
                <tr>
                    <th style="padding:20px 30px 0;text-align: center;">
                        <img src="<?php echo base_url(); ?>assets/bootstrap/images/emailer-logo.png" width="220" height="73" alt="insightsPRO Logo" style="display: block;margin-left: auto;margin-right: auto;max-width:60%;height: auto;" />
                    </th>
                </tr>
                <tr>
                    <th style="padding:20px 30px 6px;text-align: center;text-transform: capitalize;font-size:20px;font-weight:800;color: #2E7D32;"> Form Response Alert </th>
                </tr>
                
            </thead>
            <tbody>               
                <tr>
                    <td style="padding:0 30px 15px;">
                        <table style="width:80%; margin:0 auto;text-align:left;border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Form Name</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_form_name; ?></td>
                                </tr>
                                <?php if(!empty($metaField_val)) { 
                                    
                                    foreach($metaField_val as $key=>$val)
                                    {
                                    ?>
                                <tr>
                                    
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;"><?php echo $val['meta_attr']; ?></th>
                                    
                                   
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $val['meta_attr_val']; ?></td>
                                       
                                </tr>
                                    <?php }} ?>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Category</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_val ; ?></td>
                                </tr>
                                
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Attribute</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_form_question ; ?></td>
                                </tr>
                                
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Attribute Response</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_response; ?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Site</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_site_id ; ?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Group</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_group_id ; ?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Auditor Name</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $auditor_name ; ?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid #dcdcdc;background-color: #efefef;font-size: 13px;vertical-align: middle;width:35;padding:15px;font-weight:600;color:#565656;">Message</th>
                                    <td style="border:1px solid #dcdcdc;padding:15px;width:65%;font-size: 12px;vertical-align: middle;"><?php echo $t_notify_emails_body; ?></td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </td>
                </tr>
                
                <!--<tr>
                    <td style="padding:0 30px 20px;text-align: center;font-size: 14px;text-transform: capitalize;color:#fff;font-weight:600;"><a href="javascript:void(0)" style="background-color:#26a4d3;;color:#fff;display:inline-block;vertical-align: middle;text-decoration: none;padding:10px 24px;border-radius: 30px;text-transform:uppercase;letter-spacing: 0.5px;font-size: 12px;" onmouseover="this.style.background='#2E7D32'" onmouseout="this.style.background='#26a4d3'">Renew Subscription</a></td>
                </tr>-->
                <tr>
                    <td style="background-color:#efefef;">
                        <table style="width:100%;border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <td style="padding:20px 30px 0;text-align: center;font-size: 12px;font-weight:600;">Need help? Check out our quick User Guide for answers to common questions.</td>
                                </tr>
                                <tr>
                                    <td style="padding:15px 30px;text-align: center;font-size: 12px;font-weight:500;color: #676767;line-height: 1.6;">Get in touch with one of our experts, you can reach us at <a href="javascript:void(0)" style="text-decoration: underline;color:#333;">support@insightspro.io</a> or visit our Online Support Center anytime, day or night, to resolve your query.</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 30px 15px;text-align: center;font-size: 12px;font-weight:500;color: #ff6e40;">This is an automatically generated email, please do not reply</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #000;padding:30px;">
                        <table style="border-collapse: collapse;width:100%;">
                            <tbody>
                                <tr>
                                    <td style="padding-bottom:5px;font-weight:500;font-size:12px;color:#fff;text-align:center;text-transform: capitalize;">Our mailing address</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:400;font-size:12px;color:#fff;text-align:center;">576 Glatt Circle, Woodburn, OR, Zip - 97071, USA</td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 30px 0;font-weight:400;font-size:12px;color:#f5f5f5;text-align:center;line-height:1.6;">Want to change how you receive these emails? <br>You can <span style="text-decoration:underline;color:#f5f5f5;">update your preferences</span> or <span style="text-decoration:underline;color:#f5f5f5;">unsubscribe from this list</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 30px;text-align:center;font-size: 11px;color: #fff;background-color: #232323;">Copyright ?? <?php echo date('Y'); ?>, MattsenKumar LLC, All rights reserved.</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>