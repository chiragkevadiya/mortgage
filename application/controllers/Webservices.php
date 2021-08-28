<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Webservices extends REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'jwt', 'authorization']);
        $this->lang->load('auth');
        $this->load->model('webservice_model', 'webservice');

        $this->form_validation->set_error_delimiters('', '');
    }

    function json_decode() {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        if ($data) {
            $this->form_validation->set_data($data);
        }

        return $data;
    }

    private function user_auth() {
        // Get all the headers
        $headers = $this->input->request_headers();

        // Extract the token
        $token = isset($headers['Authorization']) ? $headers['Authorization'] : '';

        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
                $this->response($response, $status);

                exit();
            } else {
                return strtok($data, '_');
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            $this->response($response, $status);

            exit();
        }
    }

    /** register */
    public function signup_post() {
        $data = $this->json_decode();
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');

        // validate form input
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']', array('is_unique' => 'Username is already being used.'));
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]', array('is_unique' => 'This email address is already being used.'));
        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]', array('is_unique' => 'This email address is already being used.'));
        }

        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]', array('regex_match' => 'Phone number must be at least 10 numbers'));
        $this->form_validation->set_rules('device_token', 'Device Token', 'trim');
        $this->form_validation->set_rules('device', 'Device (android or ios)', 'trim|required');

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');

        $this->form_validation->set_rules('oauth_provider', 'Oauth Provider', 'trim');
        $this->form_validation->set_rules('oauth_uid', 'Oauth Id', 'trim');
        $this->form_validation->set_rules('profile_picture', 'Profile Picture', 'trim');
        $this->form_validation->set_rules('usergroup', 'User Group', 'callback_usergroup_check');

        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($data['email']);
            $identity = ($identity_column === 'email') ? $email : $data['username'];
            $password = $data['password'];

            $additional_data = ['full_name' => $data['full_name'], 'password_text' => $data['password'], 'device_token' => $data['device_token'], 'device' => $data['device'], 'phone' => $data['phone']];
            if (isset($data['oauth_provider']) && $data['oauth_provider'] != '') {
                if ($data['oauth_provider'] == 'facebook' || $data['oauth_provider'] == 'google' || $data['oauth_provider'] == 'firebase') {
                    $additional_data['oauth_provider'] = $data['oauth_provider'];
                    $additional_data['oauth_uid'] = $data['oauth_uid'];
                } else {
                    $this->response(['status' => false, 'message' => 'Invalid Oauth Provider'], REST_Controller::HTTP_OK);
                    return;
                }
            }

            if ($data['profile_picture'] != '' || $data['profile_picture'] != null) {
                $image_name = $this->image_upload($data['profile_picture']);

                $additional_data['profile_picture'] = $image_name;
                $additional_data['profile_url'] = base_url(PROFILE_PICTURE_PATH . $image_name);
            }

            $id = $this->ion_auth->register($identity, $password, $email, $additional_data, array($data['usergroup']));
            $user = $this->ion_auth->user($id)->row();

            $token = AUTHORIZATION::generateToken($user->id . '_' . uniqid());
            $token_data = ['auth_token' => $token];
            $this->ion_auth->update($user->id, $token_data);
            $user->auth_token = $token;

            $this->response(['status' => true, 'message' => 'Thanks! Your account has been successfully created.', 'user' => $user], REST_Controller::HTTP_OK);
        } else {
            $error = ['full_name' => form_error('full_name'), 'email' => form_error('email'), 'phone' => form_error('phone'), 'device_token' => form_error('device_token'),
                'device' => form_error('device'), 'password' => form_error('password'), 'usergroup' => form_error('usergroup')];
            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    public function usergroup_check($str) {
        if ($str == '2' || $str == '3') {
            return true;
        } else {
            $this->form_validation->set_message('usergroup_check', 'The usergroup field value must be 2 or 3.');
            return false;
        }
    }

    /** login */
    public function login_post() {
        $data = $this->json_decode();

        // validate form input
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            if ($this->ion_auth->login($data['email'], $data['password'], FALSE)) {
                $user = $this->ion_auth->user()->row();

                $token = AUTHORIZATION::generateToken($user->id . '_' . uniqid());

                $additional_data = ['auth_token' => $token];
                $this->ion_auth->update($user->id, $additional_data);
                $user->auth_token = $token;

                $this->response(['status' => true, 'message' => 'Logged in successfully', 'user' => $user], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => $this->ion_auth->errors()], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(['status' => false, 'data' => new stdClass(), 'message' => 'The Username/Password field is required'], REST_Controller::HTTP_OK);
        }
    }

    /** edit_profile */
    public function edit_profile_post() {
        $user_id = $this->user_auth();
        $data = $this->json_decode();

        // validate form input
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]', array('regex_match' => 'Phone number must be at least 10 numbers'));
        $this->form_validation->set_rules('profile_picture', 'Profile Picture', 'trim');

        if (!empty($data['password'])) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
        }

        if ($this->form_validation->run() === TRUE) {
            $additional_data = array(
                'full_name' => $data['full_name'],
                'phone' => $data['phone'],
                'email' => $data['email']
            );

            if (!empty($data['password'])) {
                $additional_data['password'] = $data['password'];
            }

            if (!empty($data['profile_picture'])) {
                $image_name = $this->image_upload($data['profile_picture']);
                $additional_data['profile_picture'] = $image_name;
                $additional_data['profile_url'] = base_url(PROFILE_PICTURE_PATH . $image_name);
            }

            if ($this->ion_auth->update($user_id, $additional_data)) {
                $user = $this->ion_auth->user($user_id)->row();
                $this->response(['status' => true, 'message' => 'Profile updated successfully', 'user' => $user], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => $this->ion_auth->errors()], REST_Controller::HTTP_OK);
            }
        } else {
            if ($data['password']) {
                $error = ['full_name' => form_error('full_name'), 'email' => form_error('email'), 'phone' => form_error('phone'), 'password' => form_error('password')];
            } else {
                $error = ['full_name' => form_error('full_name'), 'email' => form_error('email'), 'phone' => form_error('phone')];
            }

            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    /** Forgot password */
    public function forgot_password_post() {
        $data = $this->json_decode();
        $this->form_validation->set_rules('email', 'Email', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $data['email'])->users()->row();

            if (empty($identity)) {
                $this->ion_auth->set_error('forgot_password_email_not_found');
                $this->response(['status' => false, 'message' => $this->ion_auth->errors()], REST_Controller::HTTP_OK);
            }

            /** run the forgotten password method to email an activation code to the user */
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                $this->response(['status' => true, 'message' => 'Forgot password OTP sent successfully', 'password' => $identity->password_text], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => $this->ion_auth->errors()], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(['status' => false, 'message' => form_error('email')], REST_Controller::HTTP_OK);
        }
    }

    /** verify OTP */
    public function verify_otp_post() {
        $data = $this->json_decode();
        $this->form_validation->set_rules('otp', 'otp', 'trim|required');
        $this->form_validation->set_rules('username', 'username', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $user = $this->ion_auth->where($identity_column, $data['username'])->users()->row();

            if (empty($user)) {
                $this->response(['status' => false, 'data' => new stdClass(), 'message' => 'Invalid username.'], REST_Controller::HTTP_OK);
            } else {
                if ($user->forgotten_password_code == $data['otp']) {
                    $this->response(['status' => true, 'message' => 'OTP verified successfully'], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['status' => false, 'data' => new stdClass(), 'message' => 'Invalid OTP'], REST_Controller::HTTP_OK);
                }
            }
        } else {
            $error = ['otp' => form_error('otp'), 'username' => form_error('username')];
            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    /** reset password  */
    public function reset_password_post() {
        $data = $this->json_decode();

        $this->form_validation->set_rules('otp', 'otp', 'trim|required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm Password', 'required');

        if ($this->form_validation->run() === TRUE) {
            $user = $this->ion_auth->where('forgotten_password_code', $data['otp'])->users()->row();
            if ($user) {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};
                $change = $this->ion_auth->reset_password($identity, $data['new']);

                if ($change) {
                    $this->response(['status' => true, 'message' => $this->ion_auth->messages()], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['status' => false, 'data' => new stdClass(), 'message' => $this->ion_auth->errors()], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(['status' => false, 'message' => 'Invalid OTP'], REST_Controller::HTTP_OK);
            }
        } else {
            $error = ['otp' => form_error('otp'), 'new' => form_error('new'), 'new_confirm' => form_error('new_confirm')];
            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    /** refinance offer request  */
    public function refinance_request_post() {
        $user_id = $this->user_auth();
        $data = $this->json_decode();

        $this->form_validation->set_rules('cashoutType', 'Cashout Type', 'trim|required');
        $this->form_validation->set_rules('newLoan', 'New Loan Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('currentRate', 'Current Rate', 'trim|required|numeric');
        $this->form_validation->set_rules('estimatedValue', 'Estiamted Value', 'trim|required|numeric');
        $this->form_validation->set_rules('currentBank', 'Current Bank', 'trim|required');
        $this->form_validation->set_rules('purchaseDate', 'Purchase Date', 'trim|required');
        $this->form_validation->set_rules('propertyType', 'Property Type', 'trim|required');
        $this->form_validation->set_rules('purposeType', 'Purpose Type', 'trim|required');
        $this->form_validation->set_rules('note', 'Note', 'trim');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $postData = array(
                'user_id' => $user_id,
                'cashoutType' => $data['cashoutType'],
                'newLoan' => $data['newLoan'],
                'currentRate' => $data['currentRate'],
                'estimatedValue' => $data['estimatedValue'],
                'currentBank' => $data['currentBank'],
                'purchaseDate' => $data['purchaseDate'],
                'propertyType' => $data['propertyType'],
                'purposeType' => $data['purposeType'],
                'note' => $data['note'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'created_on' => time()
            );

            $requestId = $this->webservice->insert_data('refinance_request', $postData);
            if (!empty($requestId)) {
                $this->data['data'] = $postData;
                $html = $this->load->view('auth/email/refinance_offer_request', $this->data, true);
                send_mail(ADMIN_EMAIL, 'Refinance quote request', $html);
                $this->response(['status' => true, 'message' => 'Refinance offer request has been sent successfully.'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'data' => new stdClass(), 'message' => 'Failed something went wrong.'], REST_Controller::HTTP_OK);
            }
        } else {
            $error = ['cashoutType' => form_error('cashoutType'), 'newLoan' => form_error('newLoan'), 'currentRate' => form_error('currentRate'),
                'estimatedValue' => form_error('estimatedValue'), 'currentBank' => form_error('currentBank'), 'purchaseDate' => form_error('purchaseDate'), 'propertyType' => form_error('propertyType'),
                'purposeType' => form_error('purposeType'), 'note' => form_error('note'), 'name' => form_error('name'), 'phone' => form_error('phone'),
                'email' => form_error('email'), 'address' => form_error('address')];
            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    /** pre-qualification request  */
    public function prequal_request_post() {
        $user_id = $this->user_auth();
        $data = $this->json_decode();

        $this->form_validation->set_rules('purchasePrice', 'Purchase Price', 'trim|required|numeric');
        $this->form_validation->set_rules('downPmtPer', 'Down Percentage', 'trim|required|numeric');
        $this->form_validation->set_rules('downPmt', 'Down Payment', 'trim|required|numeric');
        $this->form_validation->set_rules('loanAmt', 'Loan Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('useFor', 'Use', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('annInc1', 'Anual Income', 'trim|required|numeric');
        $this->form_validation->set_rules('incType', 'Income Type', 'trim|required');
        $this->form_validation->set_rules('monthlyDebt', 'Month Debit', 'trim|required');
        $this->form_validation->set_rules('credit', 'Credit Score', 'trim|required');
        $this->form_validation->set_rules('fTime', 'First Time', 'trim|required');
        $this->form_validation->set_rules('vaMil', 'VA', 'trim|required');
        $this->form_validation->set_rules('clientName', 'Client Name', 'trim|required');
        $this->form_validation->set_rules('clientPhone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('clientEmail', 'Email', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('state', 'State', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $postData = array(
                'user_id' => $user_id,
                'purchasePrice' => $data['purchasePrice'],
                'downPmtPer' => $data['downPmtPer'],
                'downPmt' => $data['downPmt'],
                'loanAmt' => $data['loanAmt'],
                'useFor' => $data['useFor'],
                'type' => $data['type'],
                'annInc1' => $data['annInc1'],
                'incType' => $data['incType'],
                'monthlyDebt' => $data['monthlyDebt'],
                'credit' => $data['credit'],
                'fTime' => $data['fTime'],
                'vaMil' => $data['vaMil'],
                'clientName' => $data['clientName'],
                'clientPhone' => $data['clientPhone'],
                'clientEmail' => $data['clientEmail'],
                'address' => $data['address'],
                'state' => $data['state'],
                'created_on' => time()
            );

            $requestId = $this->webservice->insert_data('prequal_request', $postData);
            if (!empty($requestId)) {
                $this->data['data'] = $postData;
                $html = $this->load->view('auth/email/prequal_request', $this->data, true);
                send_mail(ADMIN_EMAIL, 'Pre approval request', $html);
                $this->response(['status' => true, 'message' => 'Pre approval request has been sent successfully.'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'data' => new stdClass(), 'message' => 'Failed something went wrong.'], REST_Controller::HTTP_OK);
            }
        } else {
            $error = ['purchasePrice' => form_error('purchasePrice'), 'downPmtPer' => form_error('downPmtPer'), 'downPmt' => form_error('downPmt'),
                'loanAmt' => form_error('loanAmt'), 'useFor' => form_error('useFor'), 'type' => form_error('type'), 'annInc1' => form_error('annInc1'),
                'incType' => form_error('incType'), 'monthlyDebt' => form_error('monthlyDebt'), 'credit' => form_error('credit'), 'fTime' => form_error('fTime'),
                'vaMil' => form_error('vaMil'), 'clientName' => form_error('clientName'), 'clientPhone' => form_error('clientPhone'), 'clientEmail' => form_error('clientEmail'),
                'address' => form_error('address'), 'state' => form_error('state')];
            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    /** submit testimonial */
    public function submit_testimonial_post() {
        $user_id = $this->user_auth();
        if (empty($_FILES['video_upload']['name'])) {
            $this->form_validation->set_rules('video_upload', 'Testimonial Video', 'required');
        } else {
            $this->form_validation->set_rules('video_upload', 'Testimonial Video', 'trim');
        }

        if ($this->form_validation->run() === TRUE) {
            $this->check_row_exist('testimonial', array('user_id' => $user_id, 'status' => 'N'), 'You have already given the testimonial.');

            //file upload destination
            $config['upload_path'] = TESTIMONIAL_VIDEO;
            $config['allowed_types'] = '*';
            $config['max_size'] = '102400';
            $config['overwrite'] = false;
            $config['remove_spaces'] = true;
            $video_name = random_string('numeric', 10);
            $config['file_name'] = $video_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('video_upload')) {
                $this->response(['status' => false, 'message' => strip_tags($this->upload->display_errors())], REST_Controller::HTTP_OK);
            } else {
                $data = $this->upload->data();
                $dataPost = array(
                    'file_name' => $data['file_name'],
                    'user_id' => $user_id,
                    'full_path' => base_url(TESTIMONIAL_VIDEO . $data['file_name']),
                    'created_on' => time()
                );

                $success = 'Thanks for taking the time to post testimonial! We really appreciate it.';
                $failed = 'Failed! Please try again.';
                $this->insert_data('testimonial', $dataPost, $success, $failed);
            }
        } else {
            $this->response(['status' => false, 'message' => form_error('video_upload')], REST_Controller::HTTP_OK);
        }
    }

    /** user testimonial */
    public function testimonial_post() {
        $user_id = $this->user_auth();
        $list = $this->webservice->testimonial();
        if (count($list) > 0) {
            $myData = $this->webservice->testimonial_row(array('t.user_id' => $user_id, 't.status' => 'N'));
            if (!empty($myData)) {
                $this->response(['status' => true, 'message' => 'Testimonials found.', 'myData' => array($myData), 'data' => $list], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => true, 'message' => 'Testimonials found.', 'myData' => array(), 'data' => $list], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(['status' => false, 'message' => 'No testimonials found.'], REST_Controller::HTTP_OK);
        }
    }

    /** send email */
    public function send_email_post() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim');
        $this->form_validation->set_rules('cal_type', 'Calculator type', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $to = $this->input->post('email');
            $cal_type = $this->input->post('cal_type');
            $imagepath = $this->save_image();
            $subject = $this->input->post('subject');

            $data['subject'] = $subject;
            
            if ($cal_type == 'mortgage_calculation') {
                $html = $this->load->view('auth/email/mortgage_calculation', $data, true);
            } else if ($cal_type == 'loan_compare') {
                $html = $this->load->view('auth/email/loan_compare', $data, true);
            } else if ($cal_type == 'refinance_saving') {
                $html = $this->load->view('auth/email/mortgage_calculation', $data, true);
            } else if ($cal_type == 'amortization_calc') {
                $html = $this->load->view('auth/email/mortgage_calculation', $data, true);
            } else if ($cal_type == 'payoff_calc') {
                $html = $this->load->view('auth/email/mortgage_calculation', $data, true);
            }

            if (send_mail($to, $subject, $html, $imagepath)) {
                $this->response(['status' => true, 'message' => 'Email has been send successfully'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => 'Failed! Please try again'], REST_Controller::HTTP_OK);
            }
        } else {
            $error = ['email' => form_error('email'), 'subject' => form_error('subject'), 'message' => form_error('message'), 'cal_type' => form_error('cal_type')];
            $this->response(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation Error'], REST_Controller::HTTP_OK);
        }
    }

    private function save_image() {
        if (!empty($_FILES['cal_image']['name'])) {
            $config['upload_path'] = PROFILE_PICTURE_PATH;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('cal_image')) {
                $this->response(['status' => false, 'message' => strip_tags($this->upload->display_errors())], REST_Controller::HTTP_OK);
                return;
            } else {
                $img_data = $this->upload->data();
                $imagepath = base_url(PROFILE_PICTURE_PATH . $img_data['file_name']);
                return $imagepath;
            }
        } else {
            return null;
        }
    }

    private function check_row_exist($table, $condition, $failed) {
        $status = $this->webservice->check_row_exist($table, $condition);
        if ($status) {
            $this->response(['status' => false, 'message' => $failed], REST_Controller::HTTP_OK);
            exit();
        }

        return;
    }

    private function insert_data($table, $data, $success, $failed) {
        $status = $this->webservice->insert_data($table, $data);
        if ($status !== false) {
            $this->response(['status' => true, 'message' => $success], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => $failed], REST_Controller::HTTP_OK);
        }
    }

    private function image_upload($base64 = null) {
        if ($base64 != '' || $base64 != null) {
            $image_parts = explode(";base64,", $base64);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $image_name = md5(uniqid(rand(), true));
            $filename = $image_name . '.' . $image_type;
            //rename file name with random number
            file_put_contents(PROFILE_PICTURE_PATH . $filename, $image_base64);
            return $filename;
        } else {
            return null;
        }
    }

    function generate_orderID() {
        $prefix = 'ORD';
        $time = time();
        $randID = random_string('nozero', 5);
        $order_no = $prefix . $time . $randID;

        $check_exist = $this->webservice->check_row_exist('orders', array('order_no' => $order_no));
        if ($check_exist) {
            $this->generate_orderID();
        } else {
            return $order_no;
        }
    }

}
