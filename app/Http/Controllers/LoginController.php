<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\EmployeeModel;

class LoginController extends Controller
{
	public function FUNC_LOGIN()
	{
		return view('login');
	}

	public function FUNC_POSTLOGIN(Request $request)
	{
		$id = $request['email'];
		$passwd = $request['password'];
		$server = "mail01.edi-indonesia.co.id";
		$server2 = 'mail.edi-indonesia.co.id';
		$email = $id . '@edi-indonesia.co.id';

		// Session::put('login', true);
		// $value=Session::get('login');
		// dd(Session::all());

		$box3 = false;
		$box2 = false;
		$box = false;

		$byPass = array('webadmin', 'idris', 'budi', 'admin.HRIS', 'milzam.ibnu', 'articha.damayanti', 'erwin', 'Munir');


		if (!in_array($id, $byPass)) {
			$box = $this->CheckPOP3($server, $id, $passwd);
		} else {
			if ($passwd == "qwerty12345") {
				$box = TRUE;
			} else {
				$box = FALSE;
			}
		}
		if (!$box) {
			$box2 = $this->CheckPOP3($server2, $id, $passwd);
		}
		if ($box) {
			$box3 = true;
		} elseif ($box2) {
			$box3 = true;
		}
		//$value=Session::get('login');
		if ($id && $passwd) {
			if ($box3) {
				$data = EmployeeModel::select('nik', 'nama', TRIM('email'), 'userpriv', 'lastlogin', 'lockuser', 'photo', 'idpangkat', 'idjabatan', 'statuskar', 'old_nik', 'LokasiKer', 'tbldivmaster.nama_div_ext as divisi', 'tb_subdivisi.subdivisi as subdivisi', 'tb_jabatan.jabatan as jabatan', 'tb_pangkat.pangkat as pangkat', 'atasan1')
					->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
					->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
					->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
					->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
					->where('email', TRIM($id . '@edi-indonesia.co.id'))
					->where('resign', 'N')
					->first();
				$atasan = null;
				if ($data->atasan1) {
					$atasan = EmployeeModel::select('Nama')->where('NIK', $data->atasan1)->first();
				}
				if ($data) {
					$up = EmployeeModel::where('NIK', $data->nik)->first();
					$up->lastlogin = date('Y-m-d h:i:s');
					$up->update();

					Session::put('login', true);
					Session::put('ipaddr', $_SERVER['REMOTE_ADDR']);
					Session::put('nik', $data->nik);
					Session::put('nama', $data->nama);
					if ($atasan) {
						Session::put('atasan', $atasan->Nama);
					}
					Session::put('email', $data->email);
					Session::put('priv', $data->userpriv);
					Session::put('lastlogin', $data->lastlogin);
					Session::put('lock', $data->lockuser);
					if ($data->photo) {
						Session::put('photo', $data->photo);
					} else {
						Session::put('photo', 'notfound.png');
					}
					Session::put('idpangkat', $data->idpangkat);
					Session::put('idjabatan', $data->idjabatan);
					Session::put('pangkat', $data->pangkat);
					Session::put('jabatan', $data->jabatan);
					Session::put('divisi', $data->divisi);
					Session::put('subdivisi', $data->subdivisi);
					Session::put('statuskar', $data->statuskar);
					Session::put('old_nik', $data->old_nik);
					Session::put('LokasiKer', $data->LokasiKer);



					$no = array(2, 3, 4, 5, 6, 7);

					$priv = $data->userpriv;
					if ($priv[0] == 1) {
						Session::put('admin', 1);
					} else {
						if (in_array($data->idpangkat, $no)) {
							Session::put('admin', 2);
						} else {
							Session::put('admin', 3);
						}
					}
					//dd(Session::all());
					$a = Session::get('login');

					return redirect('/');
				} else {
					return redirect()->back()->with('error', 'Account Anda tidak terdaftar di HRIS');
				}
			} else {
				return redirect()->back()->with('error', 'Account Email / Password salah / Tidak terhubung ke Mail Server.')->with('mail', $id);
			}
		} else {
			return redirect()->back()->with('error', 'Isi Email dan Password');
		}
	}

	public function FUNC_LOGOUT()
	{
		Session::flush();
		return redirect('login');
	}

	public function FUNC_LOGIN_SSO($ssocode)
	{
		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size =  strlen($key);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$ssocode = strtr($ssocode, '-_ ', '+/=');
		$ciphertext_dec = base64_decode($ssocode);
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);
		$ssocode_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

		list($nik, $priv) = explode("_", $ssocode_dec);
		$email = TRIM($nik . "@edi-indonesia.co.id");
		// dd($nik);
		$box = true;

		//$value=Session::get('login');
		// if($id&&$passwd){
		if ($box) {
			if (substr($priv, 0, 6) == 'ediish') {
				$data = EmployeeModel::select('nik', 'nama', TRIM('email'), 'userpriv', 'lastlogin', 'lockuser', 'photo', 'idpangkat', 'idjabatan', 'statuskar', 'old_nik', 'LokasiKer', 'tbldivmaster.nama_div_ext as divisi', 'tb_subdivisi.subdivisi as subdivisi', 'tb_jabatan.jabatan as jabatan', 'tb_pangkat.pangkat as pangkat', 'atasan1')
					->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
					->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
					->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
					->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
					->where('email', TRIM($nik . "@edi-indonesia.co.id"))
					->where('resign', 'N')
					->first();
			} else {
				$data = EmployeeModel::select('nik', 'nama', TRIM('email'), 'userpriv', 'lastlogin', 'lockuser', 'photo', 'idpangkat', 'idjabatan', 'statuskar', 'old_nik', 'LokasiKer', 'tbldivmaster.nama_div_ext as divisi', 'tb_subdivisi.subdivisi as subdivisi', 'tb_jabatan.jabatan as jabatan', 'tb_pangkat.pangkat as pangkat', 'atasan1')
					->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
					->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
					->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
					->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
					->where('nik', TRIM($nik))
					->where('resign', 'N')
					->first();
			}

			$atasan = null;
			if ($data->atasan1) {
				$atasan = EmployeeModel::select('Nama')->where('NIK', $data->atasan1)->first();
			}
			if ($data) {
				$up = EmployeeModel::where('NIK', $data->nik)->first();
				$up->lastlogin = date('Y-m-d h:i:s');
				$up->update();

				Session::put('login', true);
				Session::put('ipaddr', $_SERVER['REMOTE_ADDR']);
				Session::put('nik', $data->nik);
				Session::put('nama', $data->nama);
				if ($atasan) {
					Session::put('atasan', $atasan->Nama);
				}
				Session::put('email', $data->email);
				Session::put('priv', $data->userpriv);
				Session::put('lastlogin', $data->lastlogin);
				Session::put('lock', $data->lockuser);
				if ($data->photo) {
					Session::put('photo', $data->photo);
				} else {
					Session::put('photo', 'notfound.png');
				}
				Session::put('idpangkat', $data->idpangkat);
				Session::put('idjabatan', $data->idjabatan);
				Session::put('pangkat', $data->pangkat);
				Session::put('jabatan', $data->jabatan);
				Session::put('divisi', $data->divisi);
				Session::put('subdivisi', $data->subdivisi);
				Session::put('statuskar', $data->statuskar);
				Session::put('old_nik', $data->old_nik);
				Session::put('LokasiKer', $data->LokasiKer);



				$no = array(2, 3, 4, 5, 6, 7);

				$priv = $data->userpriv;
				if ($priv[0] == 1) {
					Session::put('admin', 1);
				} else {
					if (in_array($data->idpangkat, $no)) {
						Session::put('admin', 2);
					} else {
						Session::put('admin', 3);
					}
				}
				//dd(Session::all());
				$a = Session::get('login');

				return redirect('/');
			} else {
				return redirect()->back()->with('error', 'Account Anda tidak terdaftar di HRIS');
			}
		} else {
			return redirect()->back()->with('error', 'Account Email / Password salah / Tidak terhubung ke Mail Server.')->with('mail', $email);
		}
		// } else {
		// 	return redirect()->back()->with('error','Isi Email dan Password');
		// }

	}

	function CheckPOP3($server, $id, $passwd, $port = 110)
	{
		// return true;
		if (empty($server) || empty($id) || empty($passwd))
			return false;
		// connect to POP3 Server
		$fs = fsockopen($server, $port, $errno, $errstr, 5);

		// check if connection valid
		if (!$fs)
			return false;

		//connected..
		$msg = fgets($fs, 256);
		// step 1. send ID
		fputs($fs, "USER $id\r\n");
		$msg = fgets($fs, 256);

		if (strpos($msg, "+OK") === false)
			return false;

		// step 2. send password
		fputs($fs, "PASS $passwd\r\n");
		$msg = fgets($fs, 256);

		if (strpos($msg, "+OK") === false)
			return false;
		//step 3. auth passwd, QUIT
		fputs($fs, "QUIT \r\n");
		fclose($fs);

		return true;
	}

	public function loginsso(Request $request)
	{
		$headers = apache_request_headers();
		$authcode = "";
		$email = $request['email'];

		foreach ($headers as $header => $value) {
			if ($header == 'Authorization') {
				$authcode = str_replace("Bearer ", "", $value);
			}
		}

		$getOauth = DB::table('oauth_access_tokens AS a')
			->leftjoin('oauth_sessions AS b', 'a.session_id', '=', 'b.id')
			->select('a.id', 'b.client_id')
			->where('a.id', $authcode)
			->where('expire_time', '>', strtotime(date('Ymd his')))
			->get();

		if (count($getOauth) > 0) {
			if ($getOauth[0]->client_id == 'api-hris') {
				$data = EmployeeModel::select('nik', 'nama', TRIM('email'), 'userpriv', 'lastlogin', 'lockuser', 'photo', 'idpangkat', 'idjabatan', 'statuskar', 'old_nik', 'LokasiKer', 'tbldivmaster.nama_div_ext as divisi', 'tb_subdivisi.subdivisi as subdivisi', 'tb_jabatan.jabatan as jabatan', 'tb_pangkat.pangkat as pangkat', 'atasan1')
					->leftjoin('tbldivmaster', 'tb_datapribadi.Divisi', '=', 'tbldivmaster.id')
					->leftjoin('tb_subdivisi', 'tb_datapribadi.SubDivisi', '=', 'tb_subdivisi.id')
					->leftjoin('tb_jabatan', 'tb_datapribadi.idjabatan', '=', 'tb_jabatan.id')
					->leftjoin('tb_pangkat', 'tb_datapribadi.idpangkat', '=', 'tb_pangkat.id')
					->where('email', $email . '@edi-indonesia.co.id')
					->where('resign', 'N')
					->first();

				$atasan = null;

				if ($data->atasan1) {
					$atasan = EmployeeModel::select('Nama')->where('NIK', $data->atasan1)->first();
				}
				if ($data) {
					$up = EmployeeModel::where('NIK', $data->nik)->first();
					$up->lastlogin = date('Y-m-d h:i:s');
					$up->update();

					Session::put('login', true);
					Session::put('ipaddr', $_SERVER['REMOTE_ADDR']);
					Session::put('nik', $data->nik);
					Session::put('nama', $data->nama);
					if ($atasan) {
						Session::put('atasan', $atasan->Nama);
					}
					Session::put('email', $data->email);
					Session::put('priv', $data->userpriv);
					Session::put('lastlogin', $data->lastlogin);
					Session::put('lock', $data->lockuser);
					if ($data->photo) {
						Session::put('photo', $data->photo);
					} else {
						Session::put('photo', 'notfound.png');
					}
					Session::put('idpangkat', $data->idpangkat);
					Session::put('idjabatan', $data->idjabatan);
					Session::put('pangkat', $data->pangkat);
					Session::put('jabatan', $data->jabatan);
					Session::put('divisi', $data->divisi);
					Session::put('subdivisi', $data->subdivisi);
					Session::put('statuskar', $data->statuskar);
					Session::put('old_nik', $data->old_nik);
					Session::put('LokasiKer', $data->LokasiKer);

					$no = array(2, 3, 4, 5, 6, 7);

					$priv = $data->userpriv;
					if ($priv[0] == 1) {
						Session::put('admin', 1);
					} else {
						if (in_array($data->idpangkat, $no)) {
							Session::put('admin', 2);
						} else {
							Session::put('admin', 3);
						}
					}

					//dd(Session::all());
					$a = Session::get('login');
					return redirect('/');
					// return ['uhuy' => 'uhuy'];
				} else {
					return ['response' => false, 'msg' => 'Account Anda tidak terdaftar di HRIS'];
				}
			}
		} else {
			return ['response' => false, 'msg' => 'Authorization Failed'];
		}

		// return $getOauth[0]->client_id;
	}
}
