<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Snap extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
	{
		parent::__construct();
		$params = array('server_key' => 'SB-Mid-server-g_vpZGWjz682lU_2GH3b27oi', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');

		$this->load->model('cart_m');
	}

	public function index()
	{

		$this->load->view('sale_form');
	}

	public function token()
	{

		$gross_amount = $this->input->post('grandtotal');
		$data = $this->cart_m->getallkeranjang();
		// $disc = $this->input->post('discount');

		// $gross_amount = ($total - $disc);



		// Required
		$transaction_details = array(
			'order_id' => rand(),
			'gross_amount' => $gross_amount, // no decimal allowed for creditcard
		);

		$item_details = array();

		foreach ($data as $cart_details) {
			$item_details[] = array(
				'id' => $cart_details['cart_id'],
				'price' => $cart_details['price'],
				'quantity' => $cart_details['qty'],
				'name' => $cart_details['item_name']
			);
		};





		// Optional
		// $item_details = array(
		// 	'id' => '2',
		// 	'price' => 2000,
		// 	'quantity' => 1,
		// 	'name' => 'a'
		// );


		// $item_details = array($item_details);
		$credit_card['secure'] = true;
		$time = time();
		$custom_expiry = array(
			'start_time' => date("Y-m-d H:i:s O", $time),
			'unit' => 'minute',
			'duration'  => 15
		);

		$transaction_data = array(
			'transaction_details' => $transaction_details,
			'item_details'       => $item_details,
			'credit_card'        => $credit_card,
			'expiry'             => $custom_expiry
		);

		error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
	}

	public function finish()
	{

		$result = json_decode($this->input->post('result_data'));
		echo 'RESULT <br><pre>';
		var_dump($result);
		echo '</pre>';

		if ($result->payment_type == 'bank_transfer') {
			if (@$result->va_numbers) {
				foreach ($result->va_numbers as $row) {
					$bank = $row->bank;
					$vaNumber = $row->va_number;
					$billerCode = '';
				}
			} else {
				$bank = 'permata';
				$vaNumber = $result->permata_va_number;
				$billerCode = '';
			}
		} elseif ($result->payment_type == 'echannel') {
			$bank = 'mandiri';
			$vaNumber = $result->bill_key;
			$billerCode = $result->biller_code;
		}

		$grossAmount = str_replace('.00', '', $result->gross_amount);
		$dataInput = [
			'order_id' => $result->order_id,
			'gross_amount' => $grossAmount,
			'payment_type' => $result->payment_type,
			'bank' => $bank,
			'va_number' => $vaNumber,
			'biller_code' => $billerCode,
			'transaction_status' => $result->transaction_status,
			'transaction_time' => $result->transaction_time,
			'pdf_url' => $result->pdf_url,
			'date_created' => time(),
			'date_modified' => time()
		];
		$this->db->insert('midtrans_payment', $dataInput);
	}
}
