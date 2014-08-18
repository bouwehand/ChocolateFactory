<?php
/**
 * Created by PhpStorm.
 * User: vanax
 * Date: 5/13/14
 * Time: 12:01 PM
 */
class  Email{

    protected $headers;
    protected $params;
    protected $from;
    protected $to;
    protected $_subject = 'Cthulu Calls';
    protected $cc;
    protected $bcc;
    protected $_content;

    /**
     *    Construct emails
     * @param string $to
     * @param null|string $from
     * @param null $subject
     * @param null|string $cc
     * @param null|string $bcc
     * @param null $attachment
     * @internal param string $to
     * @return \Email
     */
    function __construct( $to, $from = null, $subject = null,  $cc = null, $bcc = null, $attachment = null ) {
        global $INFO;

        if ( $from === null ) {
            $from = $INFO['email'];
        } else {
            $from = strip_tags($from);
        }

        if ( $subject != null ) {
            $this->_subject = $subject;
        }

        //set all header info
        $this->to = $to;
        $this->from = $from;
        $this->params = "-f{$from}";
        $this->cc =	$cc ;
        $this->bcc = empty($bcc) ? 'thrynillan@gmail.com' : $bcc ;

        //construct header
        $this->headers = "From: Cthulu, master of dreams <{$this->from}>\r\n";
        $this->headers .= "Reply-To: Cthulu <{$this->from}>\r\n";

        if(!empty($this->cc) ){
            $this->headers.= "CC: ".$this->cc." \r\n";
        }
        if(!empty($this->bcc) ){
            $this->headers.= "BCC: ".$this->bcc." \r\n";
        }
        $this->headers .= "MIME-Version: 1.0\r\n";

        if ( !empty($attachment) ) {
            $file_size = filesize($attachment);
            $handle = fopen($attachment, "r");
            $attachment_content = fread($handle, $file_size);
            fclose($handle);
            $attachment_content = chunk_split(base64_encode($attachment_content));

            // a random hash will be necessary to send mixed content
            $separator = md5(time());

            // carriage return type (we use a PHP end of line constant)
            $eol = PHP_EOL;

            // main header (multipart mandatory)
            $this->headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
            $this->headers .= "X-Mailer: PHP/" . phpversion() . $eol;
            $this->headers .= "Content-Transfer-Encoding: 7bit" . $eol;
            $this->headers .= "This is a MIME encoded message." . $eol . $eol;

            // message
            $this->headers .= "--" . $separator . $eol;
            $this->headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $eol;
            $this->headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
            $this->headers .= "[MSG_PLACEHOLDER]" . $eol . $eol;

            // attachment
            $this->headers .= "--" . $separator . $eol;
            $this->headers .= "Content-Type: application/octet-stream; name=\"" . $attachment . "\"" . $eol;
            $this->headers .= "Content-Transfer-Encoding: base64" . $eol;
            $this->headers .= "Content-Disposition: attachment" . $eol . $eol;
            $this->headers .= $attachment_content . $eol . $eol;
            $this->headers .= "--" . $separator . "--";

            // actual content will be put in the headers
            $this->_content = '';
        } else {
            // normal message
            $this->headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $this->headers .= "X-Mailer: PHP/".phpversion()."\r\n";
            $this->_content = "[NULL]";
        }

    }
    /**
     *	Give an email with a reset link
     *		@param	$message	string 	new message
     *		@return	this
     */
    public function setMessage($message){
        $this->_content = $message;
        return $this;
    }


    /**
     *	Send email
     *		@param	void
     *		@return	void
     */
    public function send(){
        //send mail
        return mail($this->to, $this->_subject, $this->_content, $this->headers, $this->params);
    }

}
?>
