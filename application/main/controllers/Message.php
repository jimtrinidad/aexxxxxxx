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
        $msg = $this->mahana_messaging->get_all_threads_grouped(66);
        // $msg = $this->mahana_messaging->get_participant_list(1);
        // $msg = $this->mahana_messaging->get_msg_count(1192);
        // $msg = $this->mahana_messaging->get_thread_msg_count(66, 2);
        // $msg = $this->mahana_messaging->send_new_message(66, 1192, 'Test New Subject', 'tet New Message test');
        // $msg = $this->mahana_messaging->reply_to_message(1, 1192, Reply to message 1');
        // $msg = $this->mahana_messaging->reply_to_thread(2, 1192, 'mabilis pa din naman e');
        // $msg = $this->mahana_messaging->add_participant(1, 67);
        // $msg = $this->mahana_model->get_thread_by_participants(66, array(1192, 67));
        // $msg = $this->mahana_model->get_thread_messages(66, null);
        print_data($msg);
        $this->output->delete_cache('/message/services');
    }

    public function send()
    {

        $user_id        = current_user();
        $thread_id      = get_post('thread_id');
        $receiver       = get_post('receiver');
        $service_id     = get_post('service_id');
        $message        = get_post('message');
        $type           = 1;

        if ($message != '') {
            if ($thread_id) {
                $rsp = $this->mahana_messaging->reply_to_thread($thread_id, $user_id, $message);
                $type = 1; // reply
            } else if ($receiver && $receiver != $user_id) {
                $rsp = $this->mahana_messaging->send_new_message($user_id, $receiver, '', $message);
                $type = 2; // new
            } else if ($service_id) {
                $serviceData = $this->mgovdb->getRowObject('Service_Services', $service_id, 'Code');
                $receiver = json_decode($serviceData->Supports, true);
                $rsp = $this->mahana_messaging->send_new_message($user_id, $receiver, ($serviceData->Name . ' Support'), $message, PRIORITY_NORMAL, 1, $serviceData->Code, $user_id);
                $type = 3; // new support service
            }

            if ($receiver && $receiver == $user_id) {
                response_json(array(
                    'status'    => false,
                    'message'   => 'Cannot send message to yourself.'
                ));
            } else if ($service_id && !isset($serviceData)) {
                response_json(array(
                    'status'    => false,
                    'message'   => 'Cannot find service.'
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
                $found = false;
                foreach ($match_thread as $match) {
                    if ($match['type'] == 0) {
                        $found = true;
                        $thread_id   = $match['thread_id'];
                        $return_data = array(
                            'status'    => true,
                            'code'      => 2,
                            'receiver'  => $receiver,
                            'thread_id' => $thread_id
                        );
                        break;
                    }
                }
                if ($found == false) {
                    $return_data = array(
                        'status'    => true,
                        'code'      => 1,
                        'receiver'  => $receiver
                    );
                }
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
                'message'   => 'Cannot find this Mabuhay ID.'
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

            while(true) {

                $total_unread   = 0;
                $message_count  = 0;

                $threads = array();
                $rsp = $this->mahana_messaging->get_all_threads_grouped($user_id, false, 'desc');
                foreach ($rsp['retval'] as $item) {
                    $unread         = $this->mahana_model->get_thread_msg_count($user_id, $item['thread_id'], MSG_STATUS_UNREAD);
                    $participants   = $this->mahana_model-> get_participant_list($item['thread_id'], $user_id);
                    $total_unread  += $unread;
                    $message_count += count($item['messages']);

                    if ($item['thread_type'] == 1 && ($serviceData = $this->mgovdb->getRowObject('Service_Services', $item['key'], 'Code'))) {
                        // print_r($serviceData);
                        $name  = $serviceData->Name;
                        $photo = public_url('assets/logo/') . logo_filename($serviceData->Logo);
                        $supports = json_decode($serviceData->Supports, true);
                        if (in_array($user_id, $supports)) {
                            foreach ($participants as $i) {
                                if (!in_array($i['user_id'], $supports)) {
                                    $name = $i['user_name'] . ' - ' . $name;
                                    $photo = public_url('assets/profile/') . get_user_photo($i['user_id']);
                                    break;
                                }
                            }
                        }
                    } else {
                        $name   = $participants[0]['user_name'];
                        $photo  = '';
                        if (count($participants) > 2) {
                            $name .= ', +' . (count($participants) - 1) + ' Others';
                        } else if (count($participants) == 2){
                            $name .= ', + 1 Other';
                        }
                    }

                    $threads[] = array(
                        'id'        => $item['thread_id'],
                        'msg_count' => count($item['messages']),
                        'unread'    => $unread,
                        'type'      => $item['thread_type'],
                        'key'       => $item['key'],
                        'participants'  => $participants,
                        'name'      => $name,
                        'photo'     => $photo,
                    );
                }

                if ($old_count != $message_count || $last_unread != $total_unread) {

                    foreach ($threads as &$thread) {
                        if ($thread['type'] == 0) {
                            $thread['photo'] = public_url('assets/profile/') . get_user_photo($thread['participants'][0]['user_id']);
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

                if ($i >= $limit) {
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


    // get thread messages
    public function messages()
    {
        $user_id        = current_user();
        $thread_id      = get_post('thread_id');
        $timestamp      = get_post('timestamp');

        $start = time();
        $limit = 10;
        $i = 0;

        session_write_close();
        // ignore_user_abort(false);
        // set_time_limit($limit);

        try {

            $time = time();
            while(true) {

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

                    if ($message['type'] == 1 && ($serviceData = $this->mgovdb->getRowObject('Service_Services', $message['key'], 'Code'))) {
                        $support = json_decode($serviceData->Supports, true);
                        if (in_array($message['sender_id'], $support)) {
                            $message['user_name'] = $serviceData->Name;
                        }
                    }
                }

                $last = end($messages);

                if (count($messages)) {
                    response_json(array(
                        'status'    => true,
                        'timestamp' => strtotime($last['cdate']),
                        'data'      => $messages
                    ));
                    break;
                }

                if ($i >= $limit) {
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

    public function find_user()
    {   
        $query = get_post('q');
        $where =  'deletedAt IS NULL' .
                    'AND (CONCAT(FirstName, " ", LastName) LIKE "%' . $query . '%")' .
                    'AND AccountTypeID IN(1,2,3)';
        $users = $this->mgovdb->getRecords('UserAccountInformation', $where);
        $items = array();
        foreach ($users as $user) {
            $items[] = array(
                'id'        => $user['id'],
                'firstname' => $user['FirstName'],
                'lastname'  => $user['LastName'],
                'fullname'  => $user['FirstName'] . ' ' . $user['LastName'],
                'mabuhayID' => $user['MabuhayID'],
                'email'     => $user['EmailAddress'],
                'contact'   => $user['ContactNumber'],
                'gender'    => lookup('gender', $user['GenderID']),
                'address'   => array_values(array_reverse(lookup_address($user))),
                'photo'     => photo_filename($user['Photo']),
                'actype'    => lookup('account_type', $user['AccountTypeID']),
                'aclevel'   => lookup_db('UserAccountLevel', 'LevelName', $user['AccountLevelID'])
            );
        }
        response_json($items);
    }


    // get user services that he is not part of the support team
    public function services()
    {
        $user_id = current_user();
        $sql = "SELECT ss.id,ss.Code,ss.Name,Supports,Logo,t.id AS thread_id FROM Service_Services ss
                    JOIN UserAccountInformation ua ON (
                        ua.id = {$user_id} AND (
                            (ss.LocationScopeID = 1) OR
                            (ss.RegionalID = ua.RegionalID AND ss.LocationScopeID = 2) OR 
                            (ss.ProvincialID = ua.ProvincialID AND ss.LocationScopeID = 3) OR
                            (ss.MunicipalityCityID = ua.MunicipalityCityID AND (ss.LocationScopeID = 4 OR ss.LocationScopeID = 5)) OR
                            (ss.BarangayID = ua.BarangayID AND ss.LocationScopeID = 6)
                        )
                    )
                    LEFT OUTER JOIN msg_threads t ON (t.key = ss.Code AND t.type = 1 AND t.client = {$user_id})
                    WHERE ss.deletedAt IS NULL
                    AND Supports NOT LIKE '%\"{$user_id}\"%'
                    AND ss.Status = 1";

        $results = $this->db->query($sql)->result_array();
        if (count($results)) {
            $items = array();
            foreach ($results as &$result) {
                $supports = json_decode($result['Supports'], true);
                if (count($supports)) {
                    $result['Logo']     = logo_filename($result['Logo']);
                    $result['Supports'] = json_decode($result['Supports'], true);

                    $items[] = $result;
                }
            }
            response_json(array(
                'status'    => true,
                'data'      => $items
            ));
        } else {
            response_json(array(
                'status'    => false,
                'data'      => 'No service found.'
            ));
        }
    }
}
