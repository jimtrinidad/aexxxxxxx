<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Message extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        $this->load->library('mahana_messaging');
        $this->load->model('mahana_model');

    }

    public function index()
    {
        // $msg = $this->mahana_messaging->get_all_threads(66);
        // $msg = $this->mahana_messaging->get_all_threads_grouped(66);
        // $msg = $this->mahana_messaging->get_participant_list(1);
        // $msg = $this->mahana_messaging->get_msg_count(1192);
        // $msg = $this->mahana_messaging->get_thread_msg_count(66, 2);
        // $msg = $this->mahana_messaging->send_new_message(66, 1192, 'Test New Subject', 'tet New Message test');
        // $msg = $this->mahana_messaging->reply_to_message(1, 1192, Reply to message 1');
        $msg = $this->mahana_messaging->reply_to_thread(2, 1192, 'mabilis pa din naman e');
        // $msg = $this->mahana_messaging->add_participant(1, 67);
        // $msg = $this->mahana_model->get_thread_by_participants(66, array(1192, 67));
        // $msg = $this->mahana_model->get_thread_messages(66, null);
        print_data($msg);
    }

    public function send()
    {
        $user_id        = current_user();
        $thread_id      = get_post('thread_id');
        $receiver       = get_post('receiver');
        $message        = get_post('message');
        $type           = 1;

        if ($message != '') {
            if ($thread_id) {
                $rsp = $this->mahana_messaging->reply_to_thread($thread_id, $user_id, $message);
                $type = 1; // reply
            } else if ($receiver && $receiver != $user_id) {
                $rsp = $this->mahana_messaging->send_new_message($user_id, $receiver, '', $message);
                $type = 2; // new
            }

            if ($receiver && $receiver == $user_id) {
                response_json(array(
                    'status'    => false,
                    'message'   => 'Cannot send message to yourself.'
                ));
            } else {

                if (isset($rsp['err']) && $rsp['err'] == 0) {

                    response_json(array(
                        'status'    => true,
                        'message'   => 'Message sent.',
                        'type'      => $type,
                        'data'      => $rsp['retval'],
                        'timestamp' => time()+1,
                        'datetime'  => date('Y-m-d H:i:s')
                    ));
                } else {
                    response_json(array(
                        'status'    => false,
                        'message'   => 'Sending failed.'
                    ));
                }

            }

        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'Invalid message.'
            ));
        }

    }

    public function read()
    {
        $user_id        = current_user();
        $thread_id      = get_post('thread_id');
        // find unread and mark them as read
        $unread         = $this->mahana_model->get_thread_msg_count($user_id, $thread_id, MSG_STATUS_UNREAD);
        if ($unread) {
            $messages = $this->mahana_model->get_thread_messages($user_id, $thread_id, false, MSG_STATUS_UNREAD);
            foreach ($messages as $message) {
                $this->mahana_messaging->update_message_status($message['id'], $user_id, MSG_STATUS_READ);
            }
        }
    }

    /**
    * find a existing thread or prepare to make a new one using mabuhay id
    */
    public function find()
    {
        $user_id    = current_user();
        $mID        = get_post('mabuhay_id');
        $userData   = $this->mgovdb->getRowObject('UserAccountInformation', $mID, 'MabuhayID');
        if ($userData) {
            $match_thread = $this->mahana_model->get_thread_by_participants($user_id, $userData->id);
            $receiver     = array(
                                'id'    => $userData->id,
                                'name'  => $userData->FirstName . ' ' . $userData->LastName,
                                'photo' => photo_filename($userData->Photo)
                            );
            if (count($match_thread)) {
                $thread_id   = $match_thread[0]['thread_id'];
                $return_data = array(
                    'status'    => true,
                    'code'      => 2,
                    'receiver'  => $receiver,
                    'thread_id' => $thread_id
                );
            } else {
                $return_data = array(
                    'status'    => true,
                    'code'      => 1,
                    'receiver'  => $receiver
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Mabuhay ID not found.'
            );
        }

        response_json($return_data);
    }

    public function threads()
    {
        $user_id        = current_user();
        $last_unread    = get_post('unread');
        $old_count      = get_post('count');

        $start = time();
        $limit = 100;
        $i = 0;

        session_write_close();
        // // ignore_user_abort(false);
        // set_time_limit(10 + $limit);

        try {

            $time = time();
            while($i++ < $limit) {

                $total_unread   = 0;
                $message_count  = 0;

                // only do query every seconds
                // used time because sleep will pause execution time limit
                // if ($time < time()) {

                    $threads = array();
                    $rsp = $this->mahana_messaging->get_all_threads_grouped($user_id);
                    foreach ($rsp['retval'] as $item) {
                        $unread         = $this->mahana_model->get_thread_msg_count($user_id, $item['thread_id'], MSG_STATUS_UNREAD);
                        $participants   = $this->mahana_model-> get_participant_list($item['thread_id'], $user_id);
                        $total_unread  += $unread;
                        $message_count += count($item['messages']);
                        $threads[] = array(
                            'id'        => $item['thread_id'],
                            'msg_count' => count($item['messages']),
                            'unread'    => $unread,
                            'participants'  => $participants
                        );
                    }

                    if ($old_count != $message_count || $last_unread != $total_unread) {

                        foreach ($threads as &$thread) {
                            foreach ($thread['participants'] as &$participant) {
                                $participant['photo'] = get_user_photo($participant['user_id']);
                            }
                        }
                        response_json(array(
                            'status'    => true,
                            'unread'    => $total_unread,
                            'count'     => $message_count,
                            'data'      => $threads
                        ));
                        break;

                    }

                    // $time = time()+2;

                    if ((time() - $start) > $limit) {
                        response_json(array(
                            'status'    => false,
                            'message'   => 'nothing new.'
                        ));
                        break;
                    }

                    if (connection_aborted()) {
                        exit('aborted');
                    }
                // }

                sleep(1);

            }

        } catch (Exception $e) {
            response_json(array(
                'status'    => false,
                'error'     => $e->getMessage()
            ));
        }
    }


    // get thread messages
    public function messages()
    {
        $user_id        = current_user();
        $thread_id      = get_post('thread_id');
        $timestamp      = get_post('timestamp');

        $start = time();
        $limit = 100;
        $i = 0;

        session_write_close();
        // ignore_user_abort(false);
        // set_time_limit($limit);

        try {

            $time = time();
            while(true) {

                echo "\n";

                syslog(LOG_INFO, "message {$i},  {$user_id}, {$thread_id}, {$timestamp} - " . connection_status());

                $messages = $this->mahana_model->get_thread_messages($user_id, $thread_id, $timestamp);
                
                $user_cache = array();
                foreach ($messages as &$message) {
                    // mark message as read upon fetch
                    if ($message['status'] != MSG_STATUS_READ && get_post('read')) {
                        $this->mahana_messaging->update_message_status($message['id'], $user_id, MSG_STATUS_READ);
                    }

                    // get sender photo
                    // if (!isset($user_cache[$message['sender_id']])) {
                    //     $user_cache[$message['sender_id']] = get_user_photo($message['sender_id']);
                    //     $message['photo'] = $user_cache[$message['sender_id']];
                    // } else {
                    //     $message['photo'] = $user_cache[$message['sender_id']];
                    // }
                }

                $last = end($messages);

                if (count($messages)) {
                    ob_clean();
                    response_json(array(
                        'status'    => true,
                        'timestamp' => strtotime($last['cdate']),
                        'data'      => $messages
                    ));
                    break;
                }

                if ($i > $limit) {
                    response_json(array(
                        'status'    => false,
                        'message'   => 'nothing new.'
                    ));
                    break;
                }

                if (connection_aborted()) {
                    exit('aborted');
                }

                sleep(1);
                $i++;

            }

        } catch (Exception $e) {
            response_json(array(
                'status'    => false,
                'error'     => $e->getMessage()
            ));
        }
    }
}
