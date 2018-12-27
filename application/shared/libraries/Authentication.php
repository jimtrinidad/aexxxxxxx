<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Authentication Class
 *
 * Very basic user authentication for CodeIgniter.
 *
 * @package        Authentication
 * @version        1.0
 * @author        Joel Vardy <info@joelvardy.com>

* Changed hashing to password_hash
* crypt is too slow
* Jim

 */
class Authentication
{

    /**
     * CodeIgniter
     *
     * @access    private
     */
    private $ci;

    /**
     * Config items
     *
     * @access    private
     */
    private $user_table;
    private $identifier_field;
    private $username_field;
    private $password_field;

    /**
     * Constructor
     */
    public function __construct()
    {

        // Assign CodeIgniter object to $this->ci
        $this->ci = &get_instance();

        // Load config
        $this->ci->config->load('authentication');
        $authentication_config = $this->ci->config->item('authentication');

        // Set config items
        $this->user_table       = $authentication_config['user_table'];
        $this->identifier_field = $authentication_config['identifier_field'];
        $this->username_field   = $authentication_config['username_field'];
        $this->password_field   = $authentication_config['password_field'];

        // Load database
        $this->ci->load->database();

        // Load libraries
        $this->ci->load->library('session');

    }

    /**
     * Check whether the username is unique
     *
     * @access    public
     * @param    string [$username] The username to query
     * @return    boolean
     */
    public function username_check($username)
    {

        // Read users where username matches
        $query = $this->ci->db->where($this->username_field, $username)->get($this->user_table);

        // If there are users
        if ($query->num_rows() > 0) {
            // Username is not available
            return false;
        }

        // No users were found
        return true;

    }

    /**
     * Login
     *
     * @access    public
     * @param    string [$username] The username of the user to authenticate
     * @param    string [$password] The password to authenticate
     * @param    bool [$isAdmin] = authenticate with admin credentials
     * @return    boolean Either TRUE or FALSE depending upon successful login
     */
    public function login($username, $password, $isAdmin = false)
    {

        // Select user details
        $this->ci->db
            ->select($this->identifier_field . ' as identifier, ' . $this->username_field . ' as username, ' . $this->password_field . ' as password')
            ->where($this->username_field, $username)
            ->where('deletedAt', NULL)
            ->where_in('StatusID', array(1,2));

        if ($isAdmin) {
            // 3 - mGov SysIntegrator
            // 4 - super admin (hidden in options)
            $this->ci->db->where_in('AccountTypeID', array(3, 4));
        }

        $user = $this->ci->db->get($this->user_table);

        // Ensure there is a user with that username
        if ($user->num_rows() == 0) {
            // There is no user with that username, but we won't tell the user that
            return false;
        }
        
        // foreach ($user->result() as $user_details) {

            // Set the user details
            $user_details = $user->row();

            // verify password
            if ($this->verify_password($password, $user_details->password)) {

                // Yes, the passwords match

                // Set the userdata for the current user
                $this->ci->session->set_userdata(array(
                    'identifier' => $user_details->identifier,
                    'username'   => $user_details->username,
                    'logged_in'  => $_SERVER['REQUEST_TIME'],
                ));

                return true;

                // The passwords don't match
            } else {
                // The passwords don't match, but we won't tell the user that
                return false;
            }

        // }

        return false;

    }

    /**
     * Check whether a user is logged in
     *
     * @access    public
     * @return    boolean TRUE for a logged in user otherwise FALSE
     */
    public function is_loggedin()
    {

        // Return true or flase based on the presence of user data
        return (bool) $this->ci->session->userdata('identifier');

    }

    /**
     * Change password
     *
     * @access    public
     * @param    string [$password] The new password
     * @param    string [$user_identifier] The identifier of the user whos password will be changed, if none is set the current users password will be changed
     * @return    boolean Either TRUE or FALSE depending upon successful login
     */
    public function change_password($password, $user_identifier = null)
    {

        // If no user identifier has been set
        if (!$user_identifier) {
            // Ensure the current user is logged in
            if ($this->is_loggedin()) {

                // Read the user identifier
                $user_identifier = $this->ci->session->userdata('identifier');

                // There is no current logged in user
            } else {
                return false;
            }
        }

        // Generate hash
        $password = $this->hash_password($password);

        // Define data to update
        $data = array(
            $this->password_field => $password,
        );

        // Update the users password
        if ($this->ci->db->where($this->identifier_field, $user_identifier)->update($this->user_table, $data)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Log a user out
     *
     * @access    public
     * @return    boolean Will always return TRUE
     */
    public function logout()
    {

        // Remove userdata
        $this->ci->session->unset_userdata('identifier');
        $this->ci->session->unset_userdata('username');
        $this->ci->session->unset_userdata('logged_in');

        // Set logged out userdata
        $this->ci->session->set_userdata(array(
            'logged_out' => $_SERVER['REQUEST_TIME'],
        ));

        // Return true
        return true;

    }


    public function hash_password($password)
    {
        // Generate hash
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify_password($password, $hash)
    {
        // Verfiy hash password
        // echo $password .' - - ' .$hash;
        // var_dump(password_verify($password, $hash));exit;
        return password_verify($password, $hash) ? true : false;
    }

}

/* End of file Authentication.php */
/* Location: ./application/libraries/Authentication.php */
