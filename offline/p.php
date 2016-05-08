<?php
        $postData       = filter_input_array(INPUT_POST);
        
        $validClients   = ['lernr.com'];
        
        if ( !empty($postData) && 
             in_array($postData['site'], $validClients) && 
             !empty( $postData['form'] )
           )
        {
                $to = [
                        'lernr.com' => [       ['abari@goodeducator.com', 'Anis Bari'],
                                                ['jprakash@goodeducator.com','Jay Prakash'],
                                                ['gtiwari@goodeducator.com','Ghanshyam Tiwari'],
                                                ['kgupta@goodeducator.com','Kapil Gupta'],
                                                ['sumanparijat@gmail.com','Suman Parijat'],
                                                ['manisdeepak@gmail.com','Manis Deepak'],
                                        ],
                ];
                
                $cc = [
                        'lernr.com' => [       
                                                //'alok@aandb.xyz',
                                                
                                        ],
                ];
                
                $bcc = [
                        'lernr.com' => [       
                                                //'alok@aandb.xyz',
                                                ["alok@aandb.xyz" , "Alok Shenoy"],
                                                ["himanshu@aandb.xyz" , "Himanshu Singh Gurjar"],
                                        ],
                ];
                
                $subject = [
                        'lernr.com' => "A new lead has submitted details",
                ];
                
                require_once '../lib/phpmailer/PHPMailerAutoload.php';
                $serverData     = filter_input_array(INPUT_SERVER);
                
                $message = "Hello," . PHP_EOL . PHP_EOL . "Please find the lead details below:" . PHP_EOL . PHP_EOL;

                foreach ($postData['form'] as $v)
                {
                        foreach ($v as $fieldName => $fieldValue)
                        {
                                $message .= $fieldName . ": " . $fieldValue . PHP_EOL;
                                
                                if ($fieldName == "Name")
                                {
                                        $fromName  = trim($fieldValue);
                                }
                                
                                if ($fieldName == "Email")
                                {
                                        $fromEmail = trim($fieldValue);
                                }
                        }
                        $message .= PHP_EOL;
                }
                
                if ( !empty( $postData['utm'] ) )
                {
                        foreach ($postData['utm'] as $fieldName => $fieldValue)
                        {
                                $message .= $fieldName . ": " . $fieldValue . PHP_EOL;
                        }
                }

                $message .= PHP_EOL;

                $message .= "IP: " . $serverData['REMOTE_ADDR'] . PHP_EOL;

                $message .= PHP_EOL;

                $message .= "Regards," . PHP_EOL . "The A&B Team";
                
                
                //Create a new PHPMailer instance
                $mail = new PHPMailer;
                $mail->CharSet = 'utf-8';
                $mail->IsSendmail();
                
                //Set who the message is to be sent from
                $mail->setFrom('no-reply@aandb.xyz', 'AandB Team');
                
                //Set an alternative reply-to address
                if (isset($fromName) && isset($fromEmail))
                {
                        $mail->addReplyTo($fromEmail, $fromName);
                }
                
                //Set who the message is to be sent to
                if (isset($to[$postData['site']]) && !empty($to[$postData['site']]))
                {
                        foreach ($to[$postData['site']] as $arr)
                        {
                                if ( isset( $arr[1] ) )
                                {
                                        $mail->addAddress($arr[0], $arr[1]);
                                }
                                else
                                {
                                        $mail->addAddress($arr[0]);
                                }
                        }
                }
                else if ( isset( $postData['errorURL'] ) )
                {
                        header('Location: ' . $postData['errorURL'], true, 302);
                }
                else
                {
                        exit;
                }
                
                if (isset($cc[$postData['site']]) && !empty($cc[$postData['site']]))
                {
                        foreach ($cc[$postData['site']] as $arr)
                        {
                                if ( isset( $arr[1] ) )
                                {
                                        $mail->addCC($arr[0], $arr[1]);
                                }
                                else
                                {
                                        $mail->addCC($arr[0]);
                                }
                        }
                }
                
                if (isset($bcc[$postData['site']]) && !empty($bcc[$postData['site']]))
                {
                        foreach ($bcc[$postData['site']] as $arr)
                        {
                                if ( isset( $arr[1] ) )
                                {
                                        $mail->addBCC($arr[0], $arr[1]);
                                }
                                else
                                {
                                        $mail->addBCC($arr[0]);
                                }
                        }
                }
                
                //Set the subject line
                $mail->Subject = isset($subject[$postData['site']]) ? $subject[$postData['site']] : "";
                
                $mail->WordWrap = 50;                                 // set word wrap to 50 characters
                $mail->IsHTML(true);
                $mail->Body    = nl2br($message);
                
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                //$mail->msgHTML( nl2br( $message ) );
                
                //Replace the plain text body with one created manually
                $mail->AltBody = $message;
                
                $mail->XMailer = ' ';
                
                //send the message, check for errors
                if (!$mail->send())
                {
                        if ( isset( $postData['errorURL'] ) )
                        {
                                header('Location: ' . $postData['errorURL'], true, 302);
                        }
                        else
                        {
                                echo "Mailer Error: " . $mail->ErrorInfo;
                        }
                } 
                else
                {
                        if ( isset( $postData['returnURL'] ) )
                        {
                                header('Location: ' . $postData['returnURL'], true, 302);
                        }
                        else
                        {
                                echo "Message sent!";
                        }
                }
                
                
        }

?>