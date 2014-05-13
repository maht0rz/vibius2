<?php
namespace vibius\plugins;
/**
 * MainHelper is part of VibiusPHP.
 * Autor: Mato Kormuth
 * Date: 4.4.2014
 * Time: 19:13
 */
/*
//Sends an email plain text or html email.
$success = Mail::create($from, $to, $subject, $content)->send();
//Sends an email with attachment.
$mail = Mail::create($from, $to, $subject, $content);
$mail->addAttachment("path_to_my_file.pdf");
$success = $mail->send();

*/

class Mail
{

    /**
     * @var string The contents of email. HTML or plaintext.
     */
    private $content;
    /**
     * @var string The subject of email.
     */
    private $subject;
    /**
     * @var bool Specifies if the content of email is HTML or not.
     */
    private $isHTML;
    /**
     * @var string The sender.
     */
    private $from;
    /**
     * @var string The recipient.
     */
    private $to;
    /**
     * @var bool Specifies if the email has attachment or not.
     */
    private $hasAttachment;
    /**
     * @var array The attachements.
     */
    private $attachments;
    /**
     * @var string Charset used for message. (Default is iso-8859-1)
     */
    private $charset = "iso-8859-1";

    /**
     * Creates a new mail object with specified.
     *
     * @param $from    string Sender's email address.
     * @param $to      string Recipient's email address.
     * @param $content string Content of email.
     * @param $subject string Subject of email.
     */
    function __construct($from, $to, $subject, $content)
    {
        $this->from = $from;
        $this->to = $to;
        $this->content = $content;
    }

    /**
     * Adds an attachment to email.
     *
     * @param $file string The path to file.
     */
    public function addAttachment($file)
    {
        $this->hasAttachment = true;
        $this->attachments[] = $file;
    }

    /**
     * Creates a new instance of Mail.
     *
     * @param $from        string Message sender.
     * @param $to          string Message recipient.
     * @param $subject     string The subject of message.
     * @param $content     string The content of message.
     * @param $charset     string The charset of message.
     * @param $attachments array The array of attached files.
     *
     * @return Mail mail
     */
    public static function create($from, $to, $subject, $content, $charset = 'utf-8')
    {
        $mail = new Mail($from, $to, $subject, $content);
        $mail->setCharset($charset);

        return $mail;
    }

    /**
     * Tries to send email.
     *
     * @return bool if the email was sent.
     */
    public function send()
    {
        //Check if email content id HTML
        $this->isHTML = $this->hasHTMLTags();
        //Build from, replay headers.
        $headers = "From: " . $this->from . "\r\n" .
            "Reply-To: " . $this->from . "\r\n";
        //Add html or attachment headers.
        if ($this->hasAttachment) {
            //We need a random hash to send file.
            $separator = md5(time());

            //Main headers.
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . "\r\n" . "\r\n";
            $headers .= "Content-Transfer-Encoding: 7bit" . "\r\n";

            //Message
            if ($this->isHTML) {
                $headers .= "--" . $separator . "\r\n";
                $headers .= "Content-Type: text/html; charset=\"" . $this->charset . "\"" . "\r\n";
                $headers .= "Content-Transfer-Encoding: 8bit" . "\r\n" . "\r\n";
                $headers .= $this->content . "\r\n" . "\r\n";
            } else {
                $headers .= "--" . $separator . "\r\n";
                $headers .= "Content-Type: text/plain; charset=\"" . $this->charset . "\"" . "\r\n";
                $headers .= "Content-Transfer-Encoding: 8bit" . "\r\n" . "\r\n";
                $headers .= $this->content . "\r\n" . "\r\n";
            }

            //Attachments
            foreach ($this->attachments as $file) {
                //Gets content of file.
                $fileContent = file_get_contents($file);
                $headers .= "--" . $separator . "\r\n";
                $headers .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"" . "\r\n";
                $headers .= "Content-Transfer-Encoding: base64" . "\r\n";
                $headers .= "Content-Disposition: attachment" . "\r\n" . "\r\n";
                $headers .= $fileContent . "\r\n" . "\r\n";
                $headers .= "--" . $separator . "--";
            }

            //Send email.
            return mail($this->to, $this->subject, "", $headers);
        } else {
            if ($this->isHTML) {
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=\"" . $this->charset . "\"" . "\r\n";
            }

            //Send email.

            return mail($this->to, $this->subject, $this->content, $headers);
        }
    }

    /**
     * Checks if email has HTML tags inside content.
     *
     * @return bool if is HTML or not.
     */
    private function hasHTMLTags()
    {
        return !(strcmp($this->content, strip_tags($this->content)) == 0);
    }

    /**
     * Sets charset of message.
     *
     * @param string $charset charset.
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Returns the email charset.
     *
     * @return string charset.
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Sets the content of email.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Returns the content of email.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets sender.
     *
     * @param string $from sender
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * Returns sender.
     *
     * @return string sender.
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets subject.
     *
     * @param string $subject subject.
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Returns subject.
     *
     * @return string subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets recipient.
     *
     * @param string $to recipient.
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * Returns recipient.
     *
     * @return string recipient.
     */
    public function getTo()
    {
        return $this->to;
    }
}