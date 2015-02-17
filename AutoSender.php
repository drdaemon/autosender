<?php

/**
 * EmailTemplate
 *
 * template processing
 */
class EmailTemplate
{
    private $path;
    private $content;
    private $variables;

    public function __construct($templatePath)
    {
        dlog("--> " . __METHOD__);
        $this->path = $templatePath;
        $this->variables = array();
    }

    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function loadTemplate($filename)
    {
        dlog("--> " . __METHOD__);
        $this->content = '';
        clog("Looking for file: " . $this->path . $filename);
        if (is_file($this->path . $filename))
        {
            $this->content = file_get_contents($this->path . $filename);
            if (empty($this->content))
            {
                clog("Error: Template content is empty");
                return false;
            }
        }
        else
        {
            clog("Error: Template file was not found");
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $result = $this->content;
        foreach ($this->variables as $name => $value)
        {
            $result = preg_replace('/{' . $name . '}/', $value, $result);
        }
        return $result;
    }
}

/**
 * EmailSender
 *
 * abstract class for sending email message
 *
 * @abstract
 */
abstract class EmailSender
{
    protected $fromName;
    protected $fromEmail;

    public function setFrom($fromName, $fromEmail)
    {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
    }
    abstract protected function sendTo($toName, $toEmail, $subject, $content);
}

/**
 * EmailSenderMandrill
 *
 * sending email via MandrillApp http://mandrillapp.com
 */
class EmailSenderMandrill extends EmailSender
{
    // All methods are accessed via:
    // https://mandrillapp.com/api/1.0/SOME-METHOD.OUTPUT_FORMAT
    const API_URL = "https://mandrillapp.com/api/1.0";
    const API_METHOD = '/messages/send.json';

    private $apiKey;

    public function setKey($key)
    {
        dlog("--> " . __METHOD__);
        $this->apiKey = $key;
    }

    public function sendTo($toName, $toEmail, $subject, $content)
    {
        dlog("--> " . __METHOD__);
        clog("EmailSender Mandrill: From: $this->fromName <$this->fromEmail>");
        clog("EmailSender Mandrill: To:   $toName <$toEmail>");
        clog("EmailSender Mandrill: Subj: $subject");

        clog("API key: $this->apiKey");
        
        $json = array (
            'key' => $this->apiKey,
            'message' => array(
// todo
//                'html' => $content_html,
                'text' => $content,
                'subject' => $subject,
                'from_name'  => $this->fromName,
                'from_email' => $this->fromEmail,
                'to' => array(
                    array(
                        'email' => $toEmail,
                        'name' => $toName
                    )
                ),
                'headers' => array(
                    'Reply-To' => $this->fromEmail,
                ),
                'track_opens' => '0',
                'track_clicks' => '0',
                'auto_text' => '0',
                'url_strip_qs' => '1',
                'preserve_recipients' => '1',
                'merge' => '0',
                'async' => '0'
            )
        );
        $API_CALL_URL = self::API_URL . self::API_METHOD;
        
        clog("URL $API_CALL_URL");
        clog("DATA " . json_encode($json));

        if (!empty($this->apiKey))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_URL, $API_CALL_URL);
            if ( ! $result = curl_exec($ch)) 
            { 
                trigger_error(curl_error($ch)); 
            } 
            curl_close($ch);
        }
        else
        {
            $result = 0;

            // for testing
            //$result = '[{"email":"some@email.com","status":"sent","_id":"cc2a154fe17d4380aeec39826eb3902a","reject_reason":null}]';
        }

        $res = 1;

        $email = '';
        $id = '';
        $status = '';
        $reject_reason = '';

        if ($result)
        {
            dlog($result);
            $jsonObj = json_decode($result);
            if (null === $jsonObj)
            {
                clog("EmailSender Mandrill: JSON error: " . json_last_error_msg());
                $res = 0;
            }
            else
            {
                foreach ($jsonObj as $v){
                    // [{"email":"some@email.com","status":"sent","_id":"cc2a154fe17d4380aeec39826eb3902a","reject_reason":null}]
                    dlog("Sending Email via Mandrill: ID " . $v->{'_id'} . ", status " . $v->{'status'} . ', reject_reason ' . $v->{'reject_reason'});
                    $id = $v->{'_id'};
                    $email = $v->{'email'};
                    $status = $v->{'status'};
                    $reject_reason = $v->{'reject_reason'};
                }
                $res = 1;
            }
        }
        else
        {
            clog("EmailSender Mandrill: API Access Error");
            $res = 0;
        }
        return array($res, $id, $status);
    }
}

/**
 * AutoSender
 *
 * Processing db and sending emails
 */
class AutoSender
{
    private $em;
    private $emailSender;

    public function __construct($entityManager, EmailSender $emailSender)
    {
        dlog("--> " . __METHOD__);
        $this->em = $entityManager;
        $this->emailSender = $emailSender;
    }

    public function __destruct()
    {
        dlog("--> " . __METHOD__);
    }

    public function process()
    {
        dlog("--> " . __METHOD__);

        // process first emails (who just subscribed)
        while ($this->processEmails(1)) {};

        // process rest of emails (rest of email in series of emails)
        while ($this->processEmails(0)) {};
    }

    private function processEmails($PROCESS_FIRST_EMAIL)
    {
        $isProcessed = 0;
        dlog("--> " . __METHOD__);
        //dlog("+   API_TEST");
        dlog("+   PROCESS_FIRST_EMAIL: " . $PROCESS_FIRST_EMAIL);
        try
        {
            if ($PROCESS_FIRST_EMAIL)
            {
                $query = $this->em->createQuery("
                    SELECT s, e, ml, p
                        FROM Sub s
                        JOIN SubList sl WITH sl.idSub = s
                        JOIN MailList ml WITH sl.idList = ml
                        JOIN Project p WITH ml.idProject = p
                        JOIN Schedule sch WITH sch.idList = ml
                        JOIN Email e WITH sch.idEmail = e
                        WHERE
                            NOT EXISTS (SELECT 1 FROM SendList sl2 WHERE sl2.idEmail = sch.idEmail AND sl2.idSub = s)
                            AND sch.idEmailPrev is NULL
                            AND s.isDisabled = 0
                ")->setMaxResults(1);
            }
            else
            {
                $query = $this->em->createQuery("
                    SELECT s, e, ml, p
                        FROM Sub s
                        JOIN SubList sl WITH sl.idSub = s
                        JOIN MailList ml WITH sl.idList = ml
                        JOIN Project p WITH ml.idProject = p
                        JOIN Schedule sch WITH sch.idList = ml
                        JOIN Email e WITH sch.idEmail = e
                        WHERE
                            EXISTS (SELECT 1 FROM SendList sl2 WHERE sl2.idEmail = sch.idEmailPrev AND sl2.idSub = s AND DATE_DIFF(CURRENT_DATE(), sl2.dtSent) >= sch.delayDays)
                            AND NOT EXISTS (SELECT 1 FROM SendList sl3 WHERE sl3.idEmail = sch.idEmail AND sl3.idSub = s)
                            AND sch.idEmailPrev is NOT NULL
                            AND s.isDisabled = 0
                ")->setMaxResults(1);
            }

            dlog('SQL:');
            dlog($query->getSQL());

            $results = $query->getResult();
            if ($results)
            {
                $isProcessed = 1;
                $emailContent = '';
                $template = new EmailTemplate(FILES_PATH);

                //\Doctrine\Common\Util\Debug::dump($results);

                foreach ($results as $class)
                {
                    if ($class instanceof Project)
                    {
                        clog("Project: " . $class->getName());
                        clog("Domain : " . $class->getDomain());
                    }
                    elseif ($class instanceof Sub)
                    {
                        $userName = $class->getName();
                        $userEmail = $class->getEmail();
                        clog("User   : " . $class->getName() . " <" . $class->getEmail() . ">");
                        $userFirstName = preg_replace('/(.+)\s+(.+)/', '$1', $userName);
                        clog("Name   : " . $userFirstName);
                        $classSub = $class;
                    }
                    elseif ($class instanceof MailList)
                    {
                        $fromName = $class->getFromName();
                        $fromEmail = $class->getEmail();
                    }
                    elseif ($class instanceof Email)
                    {
                        //echo "User:   " . $class->getName() . "<" . $class->getEmail() . ">" . "\n";
                        $filename = $class->getFilenameText();
                        $emailSubject = $class->getTitle();
                        clog("Subject: " . $emailSubject);
                        $classEmail = $class;
                    }
                }

                clog();
                // load template
                if ($template->loadTemplate($filename))
                {
                    $template->setVariable('name', $userFirstName);
                    $emailContent = $template->getContent();
                    //dlog($emailContent);
                    $this->emailSender->setFrom($fromName, $fromEmail);
                    // send Email
                    list($res, $id, $status) = $this->emailSender->sendTo($userName, $userEmail, $emailSubject, $emailContent);
                    if ( $res == 1 )
                    {
                        $sendList = new SendList();
                        $sendList->setMndId($id);
                        $sendList->setStatus($status);
                        $sendList->setSub($classSub);
                        $sendList->setEmail($classEmail);
                        $this->em->persist($sendList);
                        $this->em->flush();
                    }
                    else
                    {
                        clog('Sending Error, exiting..');
                        $isProcessed = 0;
                    }
                }
                else
                {
                    clog('Template Error, exiting..');
                    $isProcessed = 0;
                }
            }
        } catch (Exception $e) {
            clog('Exception: ' . $e->getMessage());
        }
        return $isProcessed;
    }
}