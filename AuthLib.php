<?php


class AuthLib extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function findDuplicate($table, $field, $PostedField)
	{
		$data = $this->db->get_where($table, array($field => $PostedField))->result_array();
		if ($data == null) {
			return true;
		} else {
			return false;
		}
	}
	public function findDuplicateData($table, $field, $PostedField)
	{
		$data = $this->db->get_where($table, array($field => $PostedField))->row();
		return $data ? $data : null;
	}
	public function findDuplicateUsername($username)
	{
		if (
			$this->findDuplicate('admin', 'username', $username)
			and $this->findDuplicate('customer', 'username', $username)
			and $this->findDuplicate('teachers', 'username', $username)
			and $this->findDuplicate('personels', 'username', $username)
			== true
		) {
			return true;
		} else {
			return false;
		}
	}
	public function findDuplicateUsernameEdit($username, $id_field)
	{
		$tables = array('admin', 'customer', 'teachers', 'personels');
		for ($i = 0; $i < count($tables); $i++) {
			$data = $this->findDuplicateEdit($tables[$i], 'username', $username);
			if ($data) {
				return $res = array($data[0][$id_field], $tables[$i]);
				break;
			}
		}
	}
	public function findDuplicateEmail($email)
	{
		if (
			$this->findDuplicate('admin', 'email', $email)
			and $this->findDuplicate('customer', 'email', $email)
			and $this->findDuplicate('personels', 'email', $email)
			and $this->findDuplicate('teachers', 'email', $email)
			== true
		) {
			return true;
		} else {
			return false;
		}
	}

	public function findDuplicateUsernameEdit($username, $table)
	{
		switch ($table) {
			case "admin":
				$data = $this->findDuplicateEdit('admin', 'username', $username);
				return $data ? $data[0]['admin_id'] : false;

				break;
			case "customer":
				$data = $this->findDuplicateEdit('customer', 'username', $username);

				return $data ? $data[0]['customer_id'] : false;
				break;
			case "teachers":
				$data = $this->findDuplicateEdit('teachers', 'username', $username);

				return $data ? $data[0]['id'] : false;
				break;
			case "personels":
				$data = $this->findDuplicateEdit('personels', 'username', $username);

				return $data ? $data[0]['id'] : false;
				break;
			default:
				return false;
				break;
		}
	}

	public function add($view, $info, $table, $redirectPage = null, $data = null)
	{

		$this->load->view($view, array('data' => $data));

		if (isset($_POST['submit'])) {
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$info['password'] = $this->hash($this->input->post('password'));
			if ($this->findDuplicate($table, 'username', $username) and $this->findDuplicateEmail($email) == true) {
				$res = $this->db->insert($table, $info);
				if ($res) {
					$_SESSION['success'] = 'با موفقیت ثبت شد';
				} else {
					$_SESSION['error'] = 'خطایی در هنگام ثبت رخ داده است';
				}
				if ($redirectPage) {
					redirect($redirectPage);
				}
			} else {
				$_SESSION['same_user_error'] = true;
				if ($redirectPage) {
					redirect($redirectPage);
				}
			}
		}
	}

	public function edit($view, $info, $table, $id_field, $id, $redirectPage = null)
	{
		$this->db->where($id_field, $id);
		$data = $this->db->get($table)->result_array();
		$this->load->view($view, array('data' => $data));
		if (isset($_POST['submit'])) {
			$username = $this->input->post('username');
			if ($this->findDuplicateData($table, 'username', $username)->$id_field == $data[0][$id_field]) {
				$this->db->where($id_field, $id);
				$res = $this->db->update($table, $info);
				if ($res) {
					$_SESSION['success'] = 'با موفقیت ثبت شد';
				} else {
					$_SESSION['error'] = 'خطایی در هنگام ثبت رخ داده است';
				}
				redirect($redirectPage);
			} elseif (($this->findDuplicateData($table, 'username', $username)->$id_field != $data[0][$id_field]) and ($this->findDuplicate($table, 'username', $username) == true)) {
				$this->db->where($id_field, $id);
				$res = $this->db->update($table, $info);
				if ($res) {
					$_SESSION['success'] = 'با موفقیت ثبت شد';
				} else {
					$_SESSION['error'] = 'خطایی در هنگام ثبت رخ داده است';
				}
				redirect($redirectPage);
			} else {
				redirect($redirectPage);
			}
		}
	}

	public function hash($password)
	{
		$saltStr = 'yellowbeauty@139004';
		$hash = sha1($saltStr . md5($password . $saltStr));
		return $hash;
	}
	public function updateToken($username, $table)
	{
		$token = $this->generateRandomNumber(10);
		$this->db->set('token', $token);
		$this->db->where('username', $username);
		if ($this->db->update($table)) return $token;
	}
	public function getToken($table)
	{
		$username = $_SESSION['username'];
		$this->db->where('username', $username);
		return $this->db->get($table)->row()->token;
	}
	public function checkToken($token, $sessionToken, $redirect)
	{
		if ($token == $sessionToken) {
			return true;
		} else {
			redirect($redirect);
		}
	}
	public function login($username, $password, $table, $SessionName, $redirectSuccess, $redirectFailed)
	{
		$data = $this->db->get_where($table, array('username' => $username, 'password' => $password))->result_array();
		if ($data != null) {
			$_SESSION["username"] = $username;
			$_SESSION["password"] = $password;
			$_SESSION["token"] = $this->updateToken($username, $table);
			$_SESSION[$SessionName] = true;
			redirect($redirectSuccess);
		} else {
			redirect($redirectFailed);
		}
	}
	public function GetIdBySession($table, $id_field)
	{
		$user = $_SESSION['username'];
		$data = $this->db->get_where($table, array("username" => $user))->row();
		return $data ? $data->$id_field : false;
	}
	public function GetPhotoBySession($table, $image_url)
	{
		$user = $_SESSION['username'];
		$data = $this->db->get_where($table, array("username" => $user))->row();
		return $data->$image_url ? $data->$image_url : null;
	}
	public function isLogin($SessionName, $redirect)
	{
		if (isset($_SESSION[$SessionName]))
			return true;
		else {
			$_SESSION["error_login"] = true;
			redirect($redirect);
		}
	}

	public function getUserBySession($table)
	{
		$user = $_SESSION['username'];
		$data = $this->db->get_where($table, array("username" => $user))->result_array();
		if ($data) {
			return $data;
		}
	}
	function generateRandomNumber($length = 8)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
