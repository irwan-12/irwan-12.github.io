<?php defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        check_not_login();
        $this->load->model('cart_m');
    }

    public function index()
    {
        $data['row'] = $this->cart_m->get();
        $this->template->load('template', 'transaction/payment_data', $data);
    }
}
