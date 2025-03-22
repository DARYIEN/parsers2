<?php

	class DJEMMail {
	    var $priority = 3; // Email priority (1 = High, 3 = Normal, 5 = low).
	    var $charSet  = 'utf-8'; // CharSet of the message.
	    var $contentType = 'text/plain'; // Sets the Content-type of the message.
	
	    // Sets the Encoding of the message. Options for this are "8bit",
	    // "7bit", "binary", "base64", and "quoted-printable".
	    var $encoding = 'base64';
	    
	    var $errorInfo = ''; // Holds the most recent mailer error message.
	    var $from = 'root@localhost'; // Sets the From email address for the message.
	    var $fromName = ''; // Sets the From name of the message.
	    
	    // Sets the Sender email (Return-Path) of the message.  If not empty,
	    // will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
	    var $sender = '';
	    
	    var $subject = ''; // Sets the Subject of the message.
	    
	    // Sets the Body of the message.  This can be either an HTML or text body.
	    // If HTML then run SetHTML(true).
	    var $body = '';
	    
	    // Sets the text-only body of the message.  This automatically sets the
	    // email to multipart/alternative.  This body can be read by mail
	    // clients that do not have HTML email capability such as mutt. Clients
	    // that can read HTML will view the normal Body.
	    var $altBody = '';
	    
	    // Sets word wrapping on the body of the message to a given number of
	    // characters.
	    var $wordWrap = 0;
	    
	    var $mailer = 'mail'; // Method to send mail: ("mail", "sendmail", or "smtp").
	    var $sendmail = '/usr/sbin/sendmail'; // Sets the path of the sendmail program.
	    var $version = "1.00"; // Holds Mailer version.
	    
	    // Sets the email address that a reading confirmation will be sent.
	    var $confirmReadingTo  = '';
	    
	    //  Sets the hostname to use in Message-Id and Received headers
	    //  and as default HELO string. If empty, the value returned
	    //  by SERVER_NAME is used or 'localhost.localdomain'.
	    var $hostname = '';
	    
	    /////////////////////////////////////////////////
	    // SMTP VARIABLES
	    /////////////////////////////////////////////////
	    
	    var $host        = "localhost"; // e.g. "smtp1.example.com:25;smtp2.example.com"
	    var $port        = 25; // Sets the default SMTP server port.
	    var $helo        = ''; // Sets the SMTP HELO of the message (Default is $Hostname).
	    var $SMTPAuth     = false; // Sets SMTP authentication. Utilizes the Username and Password variables.
	    var $username     = ''; // Sets SMTP username.
	    var $password     = ''; // Sets SMTP password.
	    var $timeout      = 10; // Sets the SMTP server timeout in seconds.
	    var $SMTPDebug    = false; // Sets SMTP class debugging on or off.
	    
	    // Prevents the SMTP connection from being closed after each mail
	    // sending.  If this is set to true then to close the connection
	    // requires an explicit call to SmtpClose().
	    var $SMTPKeepAlive = false;
	    
	    var $smtp            = NULL;
	    var $to              = array();
	    var $cc              = array();
	    var $bcc             = array();
	    var $replyTo         = array();
	    var $attachment      = array();
	    var $customHeader    = array();
	    var $message_type    = '';
	    var $boundary        = array();
	    var $error_count     = 0;
	    var $LE              = "\n";
	    
	    /////////////////////////////////////////////////
	    // VARIABLE METHODS
	    /////////////////////////////////////////////////
	   
	    
	    function SetPriority($priority) {
	    	$this->priority = $priority;
	    }
	
	    function GetTemplate($owner, $language, $fileName) {
	        if (is_file(MAILER_TEMPLATE . $owner . '/' . $language . '/' . $fileName)) {
	            $content = @file_get_contents(MAILER_TEMPLATE.$owner.'/' . $language . '/' . $fileName);
	        } else {
	            $content = @file_get_contents(MAILER_DEFAULT_TEMPLATE . $owner . '/' . $language . '/' . $fileName);
	        }

	        return $content;
	    }
	
	    function SetHTML($bool) {
	        if ($bool == true) {
	            $this->contentType = "text/html";

	        } else {
	            $this->contentType = "text/plain";
	        }
	    }
	    
	    /**
	    * Sets Mailer to send message using the qmail MTA.
	    * @return void
	    */
	    function SetQmail() {
	        $this->sendmail = '/var/qmail/bin/sendmail';
	        $this->mailer = 'sendmail';
	    }
	    
	    
	    /////////////////////////////////////////////////
	    // RECIPIENT METHODS
	    /////////////////////////////////////////////////
	    
	    /**
	    * Adds a "To" address.
	    * @param string $address
	    * @param string $name
	    * @return void
	    */
	    function AddAddress($address, $name = '') {
	        $cur = count($this->to);
	        $this->to[$cur][0] = trim($address);
	        $this->to[$cur][1] = $name;
	    }
	    
	    /**
	    * Adds a "Cc" address. Note: this function works
	    * with the SMTP mailer on win32, not with the "mail"
	    * mailer.
	    * @param string $address
	    * @param string $name
	    * @return void
	    */
	    function AddCC($address, $name = '') {
	        $cur = count($this->cc);
	        $this->cc[$cur][0] = trim($address);
	        $this->cc[$cur][1] = $name;
	    }
	    
	    /**
	    * Adds a "Bcc" address. Note: this function works
	    * with the SMTP mailer on win32, not with the "mail"
	    * mailer.
	    * @param string $address
	    * @param string $name
	    * @return void
	    */
	    function AddBCC($address, $name = '') {
	        $cur = count($this->bcc);
	        $this->bcc[$cur][0] = trim($address);
	        $this->bcc[$cur][1] = $name;
	    }
	    
	    /**
	    * Adds a "Reply-to" address.
	    * @param string $address
	    * @param string $name
	    * @return void
	    */
	    function AddReplyTo($address, $name = '') {
	        $cur = count($this->replyTo);
	        $this->replyTo[$cur][0] = trim($address);
	        $this->replyTo[$cur][1] = $name;
	    }
	    
	    function SetSender($sender) {
	        $this->sender = $sender;
	    }
	
	    function Mail($charset, $from, $to, $subject, $body, $confirm = false) {
	    	if(preg_match('/(.*?)<(.*?)>/u', $from, $matches)) {
	    		$from = array(
	    			'email' => $matches[2],
	    			'name' => $matches[1]
	    		);
	    	}
	        $this->ClearAddresses();
	        if (is_array($from)) {
	            $this->from = $from['email'];
	            $this->fromName = $from['name'];
	        } else {
	            $this->from = $from;
	        }
	        $this->sender = $this->from;
	        $this->charSet = $charset;
	        if ($confirm) {
	            $this->confirmReadingTo = $from;
	        }
	        $toaddr = preg_split('/[ ;,\r\n\t]+/', $to);
	        foreach ($toaddr as $key => $val) {
	            if ($val != '') {
	                $this->AddAddress($val);
	            }
	        }
	        $this->subject = $subject;
	
	        $this->body = $body; 
	    }
	    
	    /////////////////////////////////////////////////
	    // MAIL SENDING METHODS
	    /////////////////////////////////////////////////
	    
	    function SendPrepare(&$header, &$body) {
	        if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1) {
	            $this->SetError("provide address ");
	            return false;
	        }
	        
	        // Set whether the message is multipart/alternative
	        if(!empty($this->altBody)) {
	            $this->contentType = 'multipart/alternative';
	        }
	        
	        $this->SetMessageType();
	        $header .= $this->CreateHeader();
	        $body = $this->CreateBody();        
	    }
	    
	    
	    /**
	    * Creates message and assigns Mailer. If the message is
	    * not sent successfully then it returns false.  Use the ErrorInfo
	    * variable to view description of the error.
	    * @return bool
	    */
	    function Send() {
	        $header = '';
	        $body = '';
	        $this->SendPrepare($header, $body);
	        
	        if ($body == '') {
	            return false;
	        }
	        
	        // Choose the mailer
	        switch ($this->mailer) {
	            case 'sendmail':
	                if (!$this->SendmailSend($header, $body)) return false;
	                break;

	            case 'mail':
	                if (!$this->MailSend($header, $body)) return false;
	                break;

	            case 'smtp':
	                if (!$this->SmtpSend($header, $body)) return false;
	                break;

	            default:
	                $this->SetError($this->mailer . ' mailer_not_supported ');
	                return false;
	        }

	        return true;
	    }
	    
	    /**
	    * Sends mail using the $Sendmail program.
	    * @access private
	    * @return bool
	    */
	    function SendmailSend($header, $body) {
	        if ($this->sender != '') {
	            $sendmail = sprintf('%s -oi -f "%s" -t', $this->sendmail, $this->sender);
	        } else {
	            $sendmail = sprintf('%s -oi -t', $this->sendmail);
	        }
	        
	        if (!@$mail = popen($sendmail, 'w')) {
	            $this->SetError('execute ' . $this->sendmail);
	            return false;
	        }
	        
	        fputs($mail, $header);
	        fputs($mail, $body);
	        
	        $result = pclose($mail) >> 8 & 0xFF;
	        if ($result != 0) {
	            $this->SetError('execute ' . $this->sendmail);
	            return false;
	        }
	        
	        return true;
	    }
	    
	    /**
	    * Sends mail using the PHP mail() function.
	    * @access private
	    * @return bool
	    */
	    function MailSend($header, $body) {
	        $to = '';
	        for ($i = 0; $i < count($this->to); $i++) {
	            if ($i != 0) { $to .= ", "; }
	            $to .= $this->to[$i][0];
	        }

	        if ($this->sender != '' && strlen(ini_get('safe_mode')) < 1) {
	            $old_from = ini_get('sendmail_from');
	            ini_set('sendmail_from', $this->sender);
	            $params = sprintf('-oi -f %s', escapeshellarg($this->sender));
	            $rt = mail($to, $this->EncodeHeader($this->subject), $body, $header, $params);
	            if ($rt === false) {
	            	$rt = mail($to, $this->EncodeHeader($this->subject), $body, $header);
	            }
	            if (isset($old_from)) {
	                ini_set('sendmail_from', $old_from);
	            }
	        } else {
	            $rt = mail($to, $this->EncodeHeader($this->subject), $body, $header);
	        }
	        if (!$rt) {
	            $this->SetError(' instantiate ');
	            return false;
	        }
	        
	        return true;
	    }
	    
	    /**
	    * Sends mail via SMTP using PhpSMTP (Author:
	    * Chris Ryan).  Returns bool.  Returns false if there is a
	    * bad MAIL FROM, RCPT, or DATA input.
	    * @access private
	    * @return bool
	    */
	    function SmtpSend($header, $body) {
	        require_once('SMTP.php');
	        $error = '';
	        $bad_rcpt = array();
	        
	        if (!$this->SmtpConnect()) {
	            return false;
	        }
	        
	        $smtp_from = ($this->sender == '') ? $this->from : $this->sender;
	        if (!$this->smtp->Mail($smtp_from)) {
	            $error = 'from_failed ' . $smtp_from;
	            $this->SetError($error);
	            $this->smtp->Reset();
	            return false;
	        }
	        
	        // Attempt to send attach all recipients
	        for ($i = 0; $i < count($this->to); $i++) {
	            if (!$this->smtp->Recipient($this->to[$i][0])) {
	                $bad_rcpt[] = $this->to[$i][0];
	            }
	        }
	        
	        for ($i = 0; $i < count($this->cc); $i++) {
	            if (!$this->smtp->Recipient($this->cc[$i][0])) {
	                $bad_rcpt[] = $this->cc[$i][0];
	            }
	        }
	        
	        for ($i = 0; $i < count($this->bcc); $i++) {
	            if (!$this->smtp->Recipient($this->bcc[$i][0])) {
	                $bad_rcpt[] = $this->bcc[$i][0];
	            }
	        }
	        
	        if (count($bad_rcpt) > 0) { // Create error message
	            for ($i = 0; $i < count($bad_rcpt); $i++) {
	                if ($i != 0) {
	                    $error .= ", ";
	                }
	                $error .= $bad_rcpt[$i];
	            }

	            $error = 'recipients_failed ' . $error;
	            $this->SetError($error);
	            $this->smtp->Reset();
	            return false;
	        }
	        
	        if (!$this->smtp->Data($header . $body)) {
	            $this->SetError(' data_not_accepted ');
	            $this->smtp->Reset();
	            return false;
	        }
	
	        if ($this->SMTPKeepAlive == true) {
	            $this->smtp->Reset();
	        } else {
	            $this->SmtpClose();
	        }
	        
	        return true;
	    }
	    
	    /**
	    * Initiates a connection to an SMTP server.  Returns false if the
	    * operation failed.
	    * @access private
	    * @return bool
	    */
	    function SmtpConnect() {
	        if ($this->smtp == NULL) {
	            $this->smtp = new SMTP();
	        }
	        $this->smtp->do_debug = $this->SMTPDebug;
	        $hosts = explode(";", $this->host);
	        $index = 0;
	        $connection = ($this->smtp->Connected());
	        
	        // Retry while there is no connection
	        while ($index < count($hosts) && $connection == false) {
	            if (strstr($hosts[$index], ":")) {
	                list($host, $port) = explode(":", $hosts[$index]);
	            } else {
	                $host = $hosts[$index];
	                $port = $this->port;
	            }

	            if ($this->smtp->Connect($host, $port, $this->timeout)) {
	                if ($this->helo != '') {
	                    $this->smtp->Hello($this->helo);
	                } else {
	                    $this->smtp->Hello($this->ServerHostname());
	                }
	                
	                if($this->SMTPAuth) {
	                    if(!$this->smtp->Authenticate($this->username,
	                    $this->password)) {
	                        $this->SetError(" authenticate ");
	                        $this->smtp->Reset();
	                        $connection = false;
	                    }
	                }

	                $connection = true;
	            }

	            $index++;
	        }

	        if(!$connection) {
	            $this->SetError(" connect_host ");
	        }
	        
	        return $connection;
	    }
	    
	    /**
	    * Closes the active SMTP session if one exists.
	    * @return void
	    */
	    function SmtpClose() {
	        if ($this->smtp != NULL) {
	            if ($this->smtp->Connected()) {
	                $this->smtp->Quit();
	                $this->smtp->Close();
	            }
	        }
	    }
	    
	    /////////////////////////////////////////////////
	    // MESSAGE CREATION METHODS
	    /////////////////////////////////////////////////
	    
	    /**
	    * Creates recipient headers.
	    * @access private
	    * @return string
	    */
	    function AddrAppend($type, $addr) {
	        $addr_str = $type . ': ';
	        $addr_str .= $this->AddrFormat($addr[0]);
	        if (count($addr) > 1) {
	            for ($i = 1; $i < count($addr); $i++) {
	                $addr_str .= ", " . $this->AddrFormat($addr[$i]);
	            }
	        }
	        $addr_str .= $this->LE;
	        
	        return $addr_str;
	    }
	    
	    /**
	    * Formats an address correctly.
	    * @access private
	    * @return string
	    */
	    function AddrFormat($addr) {
	        if (empty($addr[1])) {
	            $formatted = $addr[0];
	        } else {
	            $formatted = $this->EncodeHeader($addr[1], 'phrase') . ' <' .
	            $addr[0] . '>';
	        }
	        
	        return $formatted;
	    }
	    
	    /**
	    * Wraps message for use with mailers that do not
	    * automatically perform wrapping and for quoted-printable.
	    * Original written by philippe.
	    * @access private
	    * @return string
	    */
	    function WrapText($message, $length, $qp_mode = false) {
	        $soft_break = ($qp_mode) ? sprintf(" =%s", $this->LE) : $this->LE;

	        $message = $this->FixEOL($message);
	        if (substr($message, -1) == $this->LE) {
	            $message = substr($message, 0, -1);
	        }

	        $line = explode($this->LE, $message);
	        $message = '';
	        for ($i=0 ;$i < count($line); $i++) {
	            $line_part = explode(" ", $line[$i]);
	            $buf = '';
	            for ($e = 0; $e<count($line_part); $e++) {
	                $word = $line_part[$e];
	                if ($qp_mode and (strlen($word) > $length)) {
	                    $space_left = $length - strlen($buf) - 1;
	                    if ($e != 0) {
	                        if ($space_left > 20) {
	                            $len = $space_left;
	                            if (substr($word, $len - 1, 1) == '=') {
	                                $len--;
	                            } elseif (substr($word, $len - 2, 1) == '=') {
	                                $len -= 2;
	                            }
	                            $part = substr($word, 0, $len);
	                            $word = substr($word, $len);
	                            $buf .= " " . $part;
	                            $message .= $buf . sprintf("=%s", $this->LE);
	                        } else {
	                            $message .= $buf . $soft_break;
	                        }
	                        $buf = '';
	                    }
	                    
	                    while (strlen($word) > 0) {
	                        $len = $length;
	                        if (substr($word, $len - 1, 1) == "=") {
	                            $len--;
	                        } elseif (substr($word, $len - 2, 1) == "=") {
	                            $len -= 2;
	                        }
	                        do { //align to chars in UTF-8 (specially for theBat mailer)
	                            $lastbyte = substr($word, $len - 3, 3);
	                            if ($lastbyte[0] != '=') {
	                                break;
	                            }
	
	                            $byte = ord(pack('H*', $lastbyte[1].$lastbyte[2]));
	                            //print $lastbyte.'-'.($byte & 0x40)."\n";;
	                            if ($byte & 0x40) {
	                                $len -= 3;
	                                if ($len <= 3) {
	                                    $len = 3;
	                                }
	                                break;
	                            }
	                            if ($len > 3) {
	                                $len -= 3;
	                            } else {
	                                break;
	                            }
	                        } while (1);
	                        $part = substr($word, 0, $len);
	                        $word = substr($word, $len);
	                        
	                        if (strlen($word) > 0) {
	                            $message .= $part . sprintf("=%s", $this->LE);
	                        } else {
	                            $buf = $part;
	                        }
	                    }
	                } else {
	                    $buf_o = $buf;
	                    $buf .= ($e == 0) ? $word : (" " . $word);
	                    
	                    if (strlen($buf) > $length and $buf_o != '') {
	                        $message .= $buf_o . $soft_break;
	                        $buf = $word;
	                    }
	                }
	            }
	            $message .= $buf . $this->LE;
	        }
	        return $message;
	    }
	    
	    /**
	    * Set the body wrapping.
	    * @access private
	    * @return void
	    */
	    function SetWordWrap() {
	        if ($this->wordWrap < 1) {
	            return;
	        }
	        
	        switch ($this->message_type) {
	            case 'alt':
	            	// fall through
	            case 'alt_attachment':
	                $this->altBody = $this->WrapText($this->altBody, $this->wordWrap);
	                break;
	            default:
	                $this->body = $this->WrapText($this->body, $this->wordWrap);
	                break;
	        }
	    }
	    
	    /**
	    * Assembles message header.
	    * @access private
	    * @return string
	    */
	    function CreateHeader() {
	        $result = '';
	        // Set the boundaries
	        $uniq_id = md5(uniqid(time()));
	        $this->boundary[1] = 'oc1_' . $uniq_id;
	        $this->boundary[2] = 'oc2_' . $uniq_id;
	        
	        $result .= $this->Received();
	        $result .= $this->HeaderLine('Date', $this->RFCDate());
	        if($this->sender == '') {
	            $result .= $this->HeaderLine('Return-Path', trim($this->from));
	        } else {
	            $result .= $this->HeaderLine('Return-Path', trim($this->sender));
	        }

	        // To be created automatically by mail()
	        if ($this->mailer != "mail") {
	            if (count($this->to) > 0) {
	                $result .= $this->AddrAppend("To", $this->to);
	            } elseif (count($this->cc) == 0) {
	                $result .= $this->HeaderLine("To", "undisclosed-recipients:;");
	            }
	            if (count($this->cc) > 0) {
	                $result .= $this->AddrAppend("Cc", $this->cc);
	            }
	        }
	        
	        $from = array();
	        $from[0][0] = trim($this->from);
	        $from[0][1] = $this->fromName;
	        $result .= $this->AddrAppend("From", $from);
	        
	        // sendmail and mail() extract Bcc from the header before sending
	        if ((($this->mailer == "sendmail") || ($this->mailer == "mail")) && 
	             (count($this->bcc) > 0)) {
	            $result .= $this->AddrAppend("Bcc", $this->bcc);
	        }
	        
	        if (count($this->replyTo) > 0) {
	            $result .= $this->AddrAppend("Reply-to", $this->replyTo);
	        }
	        
	        // mail() sets the subject itself
	        if ($this->mailer != 'mail') {
	            $result .= $this->HeaderLine("Subject", $this->EncodeHeader(trim($this->subject)));
	        }

	        $result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), $this->LE);
	        $result .= $this->HeaderLine('X-Priority', $this->priority);
	        $result .= $this->HeaderLine('X-Mailer', "Mailer [version " . $this->version . "]");

	        if ($this->confirmReadingTo != '') {
	            $result .= $this->HeaderLine('Disposition-Notification-To',
	            '<' . trim($this->confirmReadingTo) . '>');
	        }
	        
	        // Add custom headers
	        for ($index = 0; $index < count($this->customHeader); $index++) {
	            $result .= $this->HeaderLine(trim($this->customHeader[$index][0]),
	            $this->EncodeHeader(trim($this->customHeader[$index][1])));
	        }
	        $result .= $this->HeaderLine("MIME-Version", "1.0");
	        
	        switch ($this->message_type) {
	            case 'plain':
	                $result .= $this->HeaderLine('Content-Transfer-Encoding', $this->encoding);
	                $result .= sprintf("Content-Type: %s; charset=\"%s\"",
	                $this->contentType, $this->charSet);
	                break;
	            case 'attachments':
	            // fall through
	            case 'alt_attachments':
	                if ($this->InlineImageExists()) {
	                    $result .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s",
	                    'multipart/related', $this->LE, $this->LE,
	                    $this->boundary[1], $this->LE);
	                } else {
	                    $result .= $this->HeaderLine('Content-Type', 'multipart/mixed;');
	                    $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
	                }
	                break;
	            case "alt":
	                $result .= $this->HeaderLine('Content-Type', 'multipart/alternative;');
	                $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
	                break;
	        }
	        
	        if ($this->mailer != 'mail') {
	            $result .= $this->LE . $this->LE;
	        }

	        return $result;
	    }
	    
	    /**
	    * Assembles the message body.  Returns an empty string on failure.
	    * @access private
	    * @return string
	    */
	    function CreateBody() {
	        $result = '';
	        
	        $this->SetWordWrap();
	        
	        switch ($this->message_type) {
	            case 'alt':
	                $result .= $this->GetBoundary($this->boundary[1], '',
	                'text/plain', '');
	                $result .= $this->EncodeString($this->altBody, $this->encoding);
	                $result .= $this->LE . $this->LE;
	                $result .= $this->GetBoundary($this->boundary[1], '',
	                "text/html", '');
	                
	                $result .= $this->EncodeString($this->body, $this->encoding);
	                $result .= $this->LE . $this->LE;
	                
	                $result .= $this->EndBoundary($this->boundary[1]);
	                break;
	                
	            case 'plain':
	                $result .= $this->EncodeString($this->body, $this->encoding);
	                break;
	                
	            case 'attachments':
	                $result .= $this->GetBoundary($this->boundary[1], '', '', '');
	                $result .= $this->EncodeString($this->body, $this->encoding);
	                $result .= $this->LE;
	                
	                $result .= $this->AttachAll();
	                break;
	                
	            case 'alt_attachments':
	                $result .= sprintf("--%s%s", $this->boundary[1], $this->LE);
	                $result .= sprintf("Content-Type: %s;%s" .
	                "\tboundary=\"%s\"%s",
	                "multipart/alternative", $this->LE,
	                $this->boundary[2], $this->LE . $this->LE);
	                
	                // Create text body
	                $result .= $this->GetBoundary($this->boundary[2], '',
	                'text/plain', '') . $this->LE;
	                
	                $result .= $this->EncodeString($this->altBody, $this->encoding);
	                $result .= $this->LE.$this->LE;
	                
	                // Create the HTML body
	                $result .= $this->GetBoundary($this->boundary[2], '',
	                'text/html', '') . $this->LE;
	                
	                $result .= $this->EncodeString($this->body, $this->encoding);
	                $result .= $this->LE.$this->LE;
	                
	                $result .= $this->EndBoundary($this->boundary[2]);
	                
	                $result .= $this->AttachAll();
	                break;
	        }

	        if ($this->IsError()) {
	            $result = '';
	        }
	        
	        return $result;
	    }
	    
	    /**
	    * Returns the start of a message boundary.
	    * @access private
	    */
	    function GetBoundary($boundary, $charSet, $contentType, $encoding) {
	        $result = '';
	        if ($charSet == '') {
	            $charSet = $this->charSet;
	        }
	        if ($contentType == '') {
	            $contentType = $this->contentType;
	        }
	        if ($encoding == '') {
	            $encoding = $this->encoding;
	        }
	        
	        $result .= $this->TextLine("--" . $boundary);
	        $result .= sprintf("Content-Type: %s; charset = \"%s\"",
	        $contentType, $charSet);
	        $result .= $this->LE;
	        $result .= $this->HeaderLine("Content-Transfer-Encoding", $encoding);
	        $result .= $this->LE;
	        
	        return $result;
	    }
	    
	    /**
	    * Returns the end of a message boundary.
	    * @access private
	    */
	    function EndBoundary($boundary) {
	        return $this->LE . "--" . $boundary . "--" . $this->LE;
	    }
	    
	    /**
	    * Sets the message type.
	    * @access private
	    * @return void
	    */
	    function SetMessageType() {
	        if (count($this->attachment) < 1 && strlen($this->altBody) < 1) {
	            $this->message_type = 'plain';
	        } else {
	            if (count($this->attachment) > 0) {
	                $this->message_type = 'attachments';
	            }
	            if (strlen($this->altBody) > 0 && count($this->attachment) < 1) {
	                $this->message_type = 'alt';
	            }
	            if (strlen($this->altBody) > 0 && count($this->attachment) > 0) {
	                $this->message_type = 'alt_attachments';
	            }
	        }
	    }
	    
	    /**
	    * Returns a formatted header line.
	    * @access private
	    * @return string
	    */
	    function HeaderLine($name, $value) {
	        return $name . ": " . $value . $this->LE;
	    }
	    
	    /**
	    * Returns a formatted mail line.
	    * @access private
	    * @return string
	    */
	    function TextLine($value) {
	        return $value . $this->LE;
	    }
	    
	    /////////////////////////////////////////////////
	    // ATTACHMENT METHODS
	    /////////////////////////////////////////////////
	    
	    /**
	    * Adds an attachment from a path on the filesystem.
	    * Returns false if the file could not be found
	    * or accessed.
	    * @param string $path Path to the attachment.
	    * @param string $name Overrides the attachment name.
	    * @param string $encoding File encoding (see $Encoding).
	    * @param string $type File extension (MIME) type.
	    * @return bool
	    */
	    function AddAttachment($path, $name = '', $encoding = "base64",
	        $type = "application/octet-stream") {
	        if (!@is_file($path)) {
	            $this->SetError(" file_access " . $path);
	            return false;
	        }
	        
	        $filename = basename($path);
	        if ($name == '') {
	            $name = $filename;
	        }
	        
	        $cur = count($this->attachment);
	        $this->attachment[$cur][0] = $path;
	        $this->attachment[$cur][1] = $filename;
	        $this->attachment[$cur][2] = $name;
	        $this->attachment[$cur][3] = $encoding;
	        $this->attachment[$cur][4] = $type;
	        $this->attachment[$cur][5] = false; // isStringAttachment
	        $this->attachment[$cur][6] = "attachment";
	        $this->attachment[$cur][7] = 0;
	        
	        return true;
	    }
	    
	    /**
	    * Attaches all fs, string, and binary attachments to the message.
	    * Returns an empty string on failure.
	    * @access private
	    * @return string
	    */
	    function AttachAll() {
	        // Return text of body
	        $mime = array();
	        
	        // Add all attachments
	        for ($i = 0; $i < count($this->attachment); $i++) {
	            // Check for string attachment
	            $bString = $this->attachment[$i][5];
	            if ($bString) {
	                $string = $this->attachment[$i][0];
	            } else {
	                $path = $this->attachment[$i][0];
	            }
	            
	            $filename    = $this->attachment[$i][1];
	            $name        = $this->attachment[$i][2];
	            $encoding    = $this->attachment[$i][3];
	            $type        = $this->attachment[$i][4];
	            $disposition = $this->attachment[$i][6];
	            $cid         = $this->attachment[$i][7];
	            
	            $mime[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
	            $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $this->EncodeHeader($name), $this->LE);
	            $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);
	            
	            if ($disposition == "inline") {
	                $mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);
	            }
	
	            $mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", $disposition, $this->EncodeHeader($name), $this->LE.$this->LE);
	
	            // Encode as string attachment
	            if ($bString) {
	                $mime[] = $this->EncodeString($string, $encoding);
	                if ($this->IsError()) {
	                    return '';
	                }
	                $mime[] = $this->LE.$this->LE;
	            } else {
	                $mime[] = $this->EncodeFile($path, $encoding);
	                if ($this->IsError()) {
	                    return '';
	                }
	                $mime[] = $this->LE . $this->LE;
	            }
	        }
	        
	        $mime[] = sprintf('--%s--%s', $this->boundary[1], $this->LE);
	        
	        return join('', $mime);
	    }
	    
	    /**
	    * Encodes attachment in requested format.  Returns an
	    * empty string on failure.
	    * @access private
	    * @return string
	    */
	    function EncodeFile ($path, $encoding = "base64") {
	        if (!@$fd = fopen($path, 'rb')) {
	            $this->SetError(' file_open ' . $path);
	            return '';
	        }
	        $file_buffer = fread($fd, filesize($path));
	        $file_buffer = $this->EncodeString($file_buffer, $encoding);
	        fclose($fd);
	        
	        return $file_buffer;
	    }
	    
	    /**
	    * Encodes string to requested format. Returns an
	    * empty string on failure.
	    * @access private
	    * @return string
	    */
	    function EncodeString ($str, $encoding = "base64") {
	        $encoded = '';
	        switch (strtolower($encoding)) {
	            case 'base64':
	                // chunk_split is found in PHP >= 3.0.6
	                $encoded = chunk_split(base64_encode($str), 76, $this->LE);
	                break;
	            case '7bit';
	            case '8bit':
	                $encoded = $this->FixEOL($str);
	                if (substr($encoded, -(strlen($this->LE))) != $this->LE) {
	                    $encoded .= $this->LE;
	                }
	                break;
	            case 'binary':
	                $encoded = $str;
	                break;
	            case 'quoted-printable':
	                $encoded = $this->EncodeQP($str);
	                break;
	            default:
	                $this->SetError(' encoding ' . $encoding);
	                break;
	        }
	        return $encoded;
	    }
	    
	    /**
	    * Encode a header string to best of Q, B, quoted or none.
	    * @access private
	    * @return string
	    */
	    function EncodeHeader ($str, $position = 'text') {
	        $x = 0;
	        
	        switch (strtolower($position)) {
	            case 'comment':
	                $x = preg_match_all('/[()"]/', $str, $matches);
	                // Fall-through
	            case 'phrase':
	            case 'text':
	            default:
	                $x += preg_match_all('/[\x00-\x1F\x7F-\xFF]/', $str, $matches);
	                break;
	        }
	
	        if ($x == 0) {
	            return ($str);
	        }
	        
	        $maxlen = 75 - 7 - strlen($this->charSet);
	        // Try to select the encoding which should produce the shortest output
	        if (0 && strlen($str) / 3 < $x) { //Disabled due to TheBat! Mailer hot handle splitted UTF symbols
	            $encoding = 'B';
	            $encoded = base64_encode($str);
	            $maxlen -= $maxlen % 4;
	            $encoded = trim(chunk_split($encoded, $maxlen, "\n"));
	        } else {
	            $encoding = 'Q';
	            $encoded = $this->EncodeQ($str, $position);
	            $encoded = $this->WrapText($encoded, $maxlen, true);
	            $encoded = str_replace("=".$this->LE, "\n", trim($encoded));
	        }
	        
	        $encoded = preg_replace('/^(.*)$/m', ' =?'.$this->charSet."?$encoding?\\1?=", $encoded);
	        $encoded = trim(str_replace("\n", $this->LE, $encoded));
	        
	        return $encoded;
	    }
	    
	    /**
	    * Encode string to quoted-printable.
	    * @access private
	    * @return string
	    */
	    function EncodeQP ($str) {
	        $encoded = $this->FixEOL($str);
	        if (substr($encoded, -(strlen($this->LE))) != $this->LE) {
	            $encoded .= $this->LE;
	        }
	        
	        // Replace every high ascii, control and = characters
	        $encoded = preg_replace('/([\000-\010\013\014\016-\037\075\177-\377])/e',
	        "'='.sprintf('%02X', ord('\\1'))", $encoded);
	        // Replace every spaces and tabs when it's the last character on a line
	        $encoded = preg_replace("/([\011\040])".$this->LE."/e",
	        "'='.sprintf('%02X', ord('\\1')).'".$this->LE."'", $encoded);
	        
	        // Maximum line length of 76 characters before CRLF (74 + space + '=')
	        $encoded = $this->WrapText($encoded, 74, true);
	        
	        return $encoded;
	    }
	    
	    /**
	    * Encode string to q encoding.
	    * @access private
	    * @return string
	    */
	    function EncodeQ ($str, $position = "text") {
	        // There should not be any EOL in the string
	        $encoded = preg_replace("[\r\n]", '', $str);
	        
	        switch (strtolower($position)) {
	            case 'phrase':
	                $encoded = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
	                break;
	                
	            case 'comment':
	                $encoded = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
	                break;
	                
	            case 'text':
	            default:
	                // Replace every high ascii, control =, ? and _ characters
	                $encoded = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e',
	                "'='.sprintf('%02X', ord('\\1'))", $encoded);
	                break;
	        }
	        
	        // Replace every spaces to _ (more readable than =20)
	        $encoded = str_replace(" ", "_", $encoded);
	        
	        return $encoded;
	    }
	    
	    /**
	    * Adds a string or binary attachment (non-filesystem) to the list.
	    * This method can be used to attach ascii or binary data,
	    * such as a BLOB record from a database.
	    * @param string $string String attachment data.
	    * @param string $filename Name of the attachment.
	    * @param string $encoding File encoding (see $Encoding).
	    * @param string $type File extension (MIME) type.
	    * @return void
	    */
	    function AddStringAttachment($string, $filename, $encoding = "base64",
	                                 $type = "application/octet-stream") {
	        // Append to $attachment array
	        $cur = count($this->attachment);
	        $this->attachment[$cur][0] = $string;
	        $this->attachment[$cur][1] = $filename;
	        $this->attachment[$cur][2] = $filename;
	        $this->attachment[$cur][3] = $encoding;
	        $this->attachment[$cur][4] = $type;
	        $this->attachment[$cur][5] = true; // isString
	        $this->attachment[$cur][6] = 'attachment';
	        $this->attachment[$cur][7] = 0;
	    }
	    
	    /**
	    * Adds an embedded attachment.  This can include images, sounds, and
	    * just about any other document.  Make sure to set the $type to an
	    * image type.  For JPEG images use "image/jpeg" and for GIF images
	    * use "image/gif".
	    * @param string $path Path to the attachment.
	    * @param string $cid Content ID of the attachment.  Use this to identify
	    *        the Id for accessing the image in an HTML form.
	    * @param string $name Overrides the attachment name.
	    * @param string $encoding File encoding (see $Encoding).
	    * @param string $type File extension (MIME) type.
	    * @return bool
	    */
	    function AddEmbeddedImage($path, $cid, $name = '', $encoding = "base64",
	                              $type = "application/octet-stream") {
	        
	        if (!@is_file($path)) {
	            $this->SetError(' file_access ' . $path);
	            return false;
	        }
	        
	        $filename = basename($path);
	        if ($name == '') {
	            $name = $filename;
	        }
	        
	        // Append to $attachment array
	        $cur = count($this->attachment);
	        $this->attachment[$cur][0] = $path;
	        $this->attachment[$cur][1] = $filename;
	        $this->attachment[$cur][2] = $name;
	        $this->attachment[$cur][3] = $encoding;
	        $this->attachment[$cur][4] = $type;
	        $this->attachment[$cur][5] = false; // isStringAttachment
	        $this->attachment[$cur][6] = 'inline';
	        $this->attachment[$cur][7] = $cid;
	        
	        return true;
	    }
	    
	    /**
	    * Returns true if an inline attachment is present.
	    * @access private
	    * @return bool
	    */
	    function InlineImageExists() {
	        $result = false;
	        for ($i = 0; $i < count($this->attachment); $i++) {
	            if ($this->attachment[$i][6] == 'inline') {
	                $result = true;
	                break;
	            }
	        }
	        return $result;
	    }
	    
	    /////////////////////////////////////////////////
	    // MESSAGE RESET METHODS
	    /////////////////////////////////////////////////
	    
	    function ClearAll() {
	        $this->ClearAddresses();
	        $this->ClearAllRecipients();
	        $this->ClearAttachments();
	        $this->ClearBCCs();
	        $this->ClearCCs();
	        $this->ClearCustomHeaders();
	        $this->ClearReplyTos();
	        $this->errorInfo = '';
	        $this->priority = 3;
	    }
	    
	    /**
	    * Clears all recipients assigned in the TO array.  Returns void.
	    * @return void
	    */
	    function ClearAddresses() {
	        $this->to = array();
	        $this->confirmReadingTo = '';
	    }
	    
	    /**
	    * Clears all recipients assigned in the CC array.  Returns void.
	    * @return void
	    */
	    function ClearCCs() {
	        $this->cc = array();
	    }
	    
	    /**
	    * Clears all recipients assigned in the BCC array.  Returns void.
	    * @return void
	    */
	    function ClearBCCs() {
	        $this->bcc = array();
	    }
	    
	    /**
	    * Clears all recipients assigned in the ReplyTo array.  Returns void.
	    * @return void
	    */
	    function ClearReplyTos() {
	        $this->replyTo = array();
	    }
	    
	    /**
	    * Clears all recipients assigned in the TO, CC and BCC
	    * array.  Returns void.
	    * @return void
	    */
	    function ClearAllRecipients() {
	        $this->to = array();
	        $this->cc = array();
	        $this->bcc = array();
	    }
	    
	    /**
	    * Clears all previously set filesystem, string, and binary
	    * attachments.  Returns void.
	    * @return void
	    */
	    function ClearAttachments() {
	        $this->attachment = array();
	    }
	
	    /**
	    * Clears all custom headers.  Returns void.
	    * @return void
	    */
	    function ClearCustomHeaders() {
	        $this->customHeader = array();
	    }
	
	    /////////////////////////////////////////////////
	    // MISCELLANEOUS METHODS
	    /////////////////////////////////////////////////
	
	    /**
	    * Adds the error message to the error container.
	    * Returns void.
	    * @access private
	    * @return void
	    */
	    function SetError($msg) {
	        $this->error_count++;
	        $this->errorInfo = $msg;
	    }
	
	    /**
	    * Returns the proper RFC 822 formatted date.
	    * @access private
	    * @return string
	    */
	    function RFCDate() {
	        $tz = date("Z");
	        $tzs = ($tz < 0) ? "-" : "+";
	        $tz = abs($tz);
	        $tz = ($tz/3600)*100 + ($tz%3600)/60;
	        $result = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);
	        
	        return $result;
	    }
	    
	    /**
	    * Returns Received header for message tracing.
	    * @access private
	    * @return string
	    */
	    function Received() {
	        if ($this->ServerVar('SERVER_NAME') != '') {
	            $protocol = ($this->ServerVar('HTTPS') == 'on') ? 'HTTPS' : 'HTTP';
	            $remote = $this->ServerVar('REMOTE_HOST');
	            if ($remote == '') {
	                $remote = 'mailer';
	            }
	            $remote .= ' (['.$this->ServerVar('REMOTE_ADDR').'])';
	        } else {
	            $protocol = 'local';
	            $remote = $this->ServerVar('USER');
	            if ($remote == '') {
	                $remote = 'mailer';
	            }
	        }
	
	        $result = sprintf("Received: from %s %s\tby %s " .
	        "with %s (Mailer);%s\t%s%s", $remote, $this->LE,
	        $this->ServerHostname(), $protocol, $this->LE,
	        $this->RFCDate(), $this->LE);
	        
	        return $result;
	    }
	
	    /**
	    * Returns the appropriate server variable.  Should work with both
	    * PHP 4.1.0+ as well as older versions.  Returns an empty string
	    * if nothing is found.
	    * @access private
	    * @return mixed
	    */
	    function ServerVar($varName) {
	        global $HTTP_SERVER_VARS;
	        global $HTTP_ENV_VARS;
	        
	        if (!isset($_SERVER)) {
	            $_SERVER = $HTTP_SERVER_VARS;
	            if(!isset($_SERVER["REMOTE_ADDR"]))
	            $_SERVER = $HTTP_ENV_VARS; // must be Apache
	        }
	        
	        if (isset($_SERVER[$varName])) {
	            return $_SERVER[$varName];
	        } else {
	            return '';
	        }
	    }
	    
	    /**
	    * Returns the server hostname or 'localhost.localdomain' if unknown.
	    * @access private
	    * @return string
	    */
	    function ServerHostname() {
	        if ($this->hostname != '') {
	            $result = $this->hostname;
	        } elseif ($this->ServerVar('SERVER_NAME') != '') {
	            $result = $this->ServerVar('SERVER_NAME');
	        } else {
	            $result = 'localhost.localdomain';
	        }
	
	        return $result;
	    }
	
	    /**
	    * Returns true if an error occurred.
	    * @return bool
	    */
	    function IsError() {
	        return ($this->error_count > 0);
	    }
	
	    /**
	    * Changes every end of line from CR or LF to CRLF.
	    * @access private
	    * @return string
	    */
	    function FixEOL($str) {
	        $str = str_replace("\r\n", "\n", $str);
	        $str = str_replace("\r", "\n", $str);
	        $str = str_replace("\n", $this->LE, $str);
	        return $str;
	    }
	
	    /**
	    * Adds a custom header.
	    * @return void
	    */
	    function AddCustomHeader($custom_header) {
	        $this->customHeader[] = explode(':', $custom_header, 2);
	    }
	};

