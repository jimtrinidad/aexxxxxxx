<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Email Queue
 *
 * A CodeIgniter library to queue e-mails.
 *
 * @package     CodeIgniter
 * @category    Libraries
 * @author      Thaynã Bruno Moretti
 * @link    http://www.meau.com.br/
 * @license http://www.opensource.org/licenses/mit-license.html
 */

// CI built-in email is not working anymore on current smtp server
// use phpmailer instead

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$main_tp_path = dirname(APPPATH) . '/main/';
require_once $main_tp_path .'third_party/phpmailer/Exception.php';
require_once $main_tp_path .'third_party/phpmailer/PHPMailer.php';
require_once $main_tp_path .'third_party/phpmailer/SMTP.php';

class MY_Email extends CI_Email
{
    // DB table
    private $table_email_queue = 'email_queue';

    // Main controller
    private $main_controller = 'sys/queue_email/send_pending_emails';

    // PHP Nohup command line
    private $phpcli = 'nohup php';
    private $expiration = NULL;

    // Status (pending, sending, sent, failed)
    private $status;

    private $raw_subject;

    public $configKey;

    /**
     * Constructor
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        log_message('debug', 'Email Queue Class Initialized');

        $this->expiration = 60*5;
        $this->CI = & get_instance();

        $this->CI->load->database('default');
    }

    public function set_status($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get
     *
     * Get queue emails.
     * @return  mixed
     */
    public function get($limit = NULL, $offset = NULL)
    {
        if ($this->status != FALSE)
            $this->CI->db->where('status', $this->status);

        $query = $this->CI->db->get("{$this->table_email_queue}", $limit, $offset);

        return $query->result();
    }

    /**
     * Save
     *
     * Add queue email to database.
     * @return  mixed
     */
    public function send($skip_job = FALSE)
    {
        if ( $skip_job === TRUE ) {
            return parent::send();
        }

        $date = date("Y-m-d H:i:s");

        $to = is_array($this->_recipients) ? implode(", ", $this->_recipients) : $this->_recipients;
        $cc = implode(", ", $this->_cc_array);
        $bcc = implode(", ", $this->_bcc_array);

        $dbdata = array(
            'config' => $this->configKey,
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
            'subject' => $this->raw_subject,
            'message' => $this->_body,
            'headers' => serialize($this->_headers),
            'status' => 'pending',
            'date' => $date
        );

        return $this->CI->db->insert($this->table_email_queue, $dbdata);
    }

    /**
     * Start process
     *
     * Start php process to send emails
     * @return  mixed
     */
    public function start_process()
    {
        $filename = FCPATH . 'index.php';
        $exec = shell_exec("{$this->phpcli} {$filename} {$this->main_controller} > /dev/null &");

        return $exec;
    }

    /**
     * Send queue
     *
     * Send queue emails.
     * @return  void
     */
    public function send_queue()
    {
        $this->set_status('pending');
        $emails = $this->get(10);

        $tosendID = array();
        foreach ($emails as $email) {
            $tosendID[] = $email->id;
        }

        if (count($tosendID)) {
            $this->CI->db->where_in('id', $tosendID);
            $this->CI->db->where('status', 'pending');
            $this->CI->db->set('status', 'sending');
            $this->CI->db->set('date', date("Y-m-d H:i:s"));
            $this->CI->db->update($this->table_email_queue);
        }

        foreach ($emails as $email)
        {

            $this->CI->load->config('email');
            $config = $this->CI->config->item('email');

            $this->initialize($config[$email->config]);

            $recipients = explode(", ", $email->to);

            $cc = !empty($email->cc) ? explode(", ", $email->cc) : array();
            $bcc = !empty($email->bcc) ? explode(", ", $email->bcc) : array();

            $this->set_newline("\r\n");

            $this->_headers = unserialize($email->headers);

            $this->to($recipients);
            $this->cc($cc);
            $this->bcc($bcc);

            $this->subject($email->subject);
            $this->message($email->message);

            $to      = $email->to;
            $subject = $email->subject;

            if ($this->send(TRUE)) {
                $status = 'sent';
                syslog(LOG_INFO, "Queue Email Sent: [{$to}][{$subject}]");
            } else {
                $status = 'failed';
            }

            var_dump($this->print_debugger());

            $this->CI->db->where('id', $email->id);

            $this->CI->db->set('status', $status);
            $this->CI->db->set('date', date("Y-m-d H:i:s"));
            $this->CI->db->update($this->table_email_queue);
        }
    }

    /**
     * Retry failed emails
     *
     * Resend failed or expired emails
     * @return void
     */
    public function retry_queue()
    {
        $expire = (time() - $this->expiration);
        $date_expire = date("Y-m-d H:i:s", $expire);

        $this->CI->db->set('status', 'pending');
        $this->CI->db->where("(date < '{$date_expire}' AND status = 'sending')");
        $this->CI->db->or_where("status = 'failed'");

        $this->CI->db->update($this->table_email_queue);

        log_message('debug', 'Email queue retrying...');
    }

    /**
    * store raw subject
    */
    public function subject($subject)
    {
        $this->raw_subject = $subject;
        $subject = $this->_prep_q_encoding($subject);
        $this->set_header('Subject', $subject);
        return $this;
    }

    public function test()
    {
        // $this->CI->load->config('email');
        // $config = $this->CI->config->item('email');
        // $this->initialize($config['info']);
        // $this->from('info@mgov.cloud', 'MGov');
        // $this->to('jimtrinidad002@gmail.com');
        // $this->subject('Email Test');
        // $this->message('Testing the email.');

        // $this->send(true);
        // var_dump($this->print_debugger());

        $this->phpmailer_send();
    }

    public function phpmailer_send()
    {

        $this->CI->load->config('email');
        $config = $this->CI->config->item('email');
        $config = $config['info'];

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $config['smtp_host'];                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $config['smtp_user'];                     //SMTP username
            $mail->Password   = $config['smtp_pass'];                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('info@mgov.cloud', 'MGov Test');
            $mail->addAddress('jimtrinidad002@gmail.com', 'Joe User');     //Add a recipient
            $mail->addReplyTo('information@mgov.net', 'Information');
        
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
