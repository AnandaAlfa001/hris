<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [EmployeeController::class, 'HOME']);
Route::redirect('/login', '/auth/login');

/** Add By Dandy Firmansyah 05 04 2019 - Numpang CRM **/

Route::get('processToCRM', [CRMController::class, 'processToCRM']);
Route::get('pull-presence-hadirkoe/{startDate?}/{endData?}', [AbsenControllerCRMController::class, 'pullPresenceHadirkoe']);
Route::get('pull-absence-hadirkoe/{startDate?}/{endData?}/{absenceType?}/{dataType?}', [CutiControllerCRMController::class, 'pullAbsenceHadirkoe']);

/** End Add By Dandy Firmansyah 05 04 2019 - Numpang CRM **/


Route::get('/surveyReport', [ReportController::class, 'surveyReport']);
Route::get('/emailizinatasan', [EmailController::class, 'FUNC_EMAIL_IZIN_ATASAN']);
Route::get('/emailizinkarapprove', [EmailController::class, 'FUNC_EMAIL_IZIN_KAR_APPROVE']);
Route::get('/emailizinkarreject', [EmailController::class, 'FUNC_EMAIL_IZIN_KAR_REJECT']);
Route::get('/emailcutiatasan1', [EmailController::class, 'FUNC_EMAIL_CUTI_ATASAN1']);
Route::get('/emailcutiatasan2', [EmailController::class, 'FUNC_EMAIL_CUTI_ATASAN2']);
Route::get('/emailcutikarapprove', [EmailController::class, 'FUNC_EMAIL_CUTI_KAR_APPROVE']);
Route::get('/emailcutikarreject', [EmailController::class, 'FUNC_EMAIL_CUTI_KAR_REJECT']);
Route::get('/emaildaftarcuti', [EmailController::class, 'FUNC_EMAIL_CUTI_KAR_DAFTAR']);
Route::get('/testemail', [EmailController::class, 'TEST_SEND_EMAIL']);
Route::get('/employeedata', [EmployeeController::class, 'employeedata']);
Route::get('/emaillogperubahan', [EmailController::class, 'sendemailperubahan']);


Route::post('/savedokapi', [DokumenController::class, 'FUNC_SAVEDOK_API']);

// Add By Dandy Firmansyah 5 Oktober 2018
Route::get('/insertToHRIS', [EmployeeController::class, 'insertToHRIS']);
Route::get('/executionToTable', [EmployeeController::class, 'executionToTable']);
Route::get('/checkResignOrNot', [EmployeeController::class, 'checkResignOrNot']);
// End Add By Dandy Firmansyah 5 Oktober 2018

Route::get('/update_idmaster', [EmployeeController::class, 'update_idmaster']);
Route::get('/executionToHRIS', [EmployeeController::class, 'executionToHRIS']);



Route::group(['middleware' => ['admin']], function () {

	// Data Pegawai
	Route::prefix('employee')->group(function () {
		Route::get('/', [EmployeeController::class, 'listEmployee']);
		Route::get('data', [EmployeeController::class, 'dataEmployee']);
	});

	Route::get('/outemployeelist', [EmployeeController::class, 'OUTEMPLOYEE']);
	Route::get('/employeelist', [EmployeeController::class, 'INDEX']);
	Route::get('/karttplist', [EmployeeController::class, 'KARTTPLIST']);
	Route::get('/kontrakemplist', [EmployeeController::class, 'KARKONLIST']);
	Route::get('/karoutlist', [EmployeeController::class, 'KAROUTLIST']);
	Route::get('/searchemployee', [EmployeeController::class, 'FUNC_SEARCHEMPLOYEE']);

	Route::get('/headerpe/{nik}', [EmployeeController::class, 'FUNC_HEADERPE']);
	Route::post('/saveheaderpe', [EmployeeController::class, 'FUNC_SAVEHEADERPE']);
	Route::post('/saveupdateheaderpe', [EmployeeController::class, 'FUNC_SAVEUPDATEHEADERPE']);
	Route::get('/projectexperience/{id}', [EmployeeController::class, 'FUNC_PROJECTEXPERIENCE']);
	Route::post('/saveprojectex', [EmployeeController::class, 'FUNC_SAVEPROJECTEX']);
	Route::get('/detailemployee/{nik}', [EmployeeController::class, 'FUNC_DETAILEMPLOYEE']);
	Route::get('/detailemployeeout/{nik}', [EmployeeController::class, 'FUNC_DETAILEMPLOYEEOUT']);
	Route::get('/cetakSK_PHK/{nik}', [EmployeeController::class, 'FUNC_CETAKSK_PHK']);

	// MUTASI //
	Route::get('/mutasi/{nik}', [EmployeeController::class, 'FUNC_MUTASI']);
	Route::post('/savemutasi', [EmployeeController::class, 'FUNC_SAVEMUTASI']);


	// Route::post('')

	// HISTORY JABATAN //
	Route::get('/historyjabatan/{nik}', [EmployeeController::class, 'FUNC_HISTORYJABATAN']);
	Route::post('/savehistory', [EmployeeController::class, 'FUNC_SAVEHISTORY']);
	Route::get('/edithistory/{id}', [EmployeeController::class, 'FUNC_EDITHISTORY']);
	Route::get('/edithistoryter/{id}', [EmployeeController::class, 'FUNC_EDITHISTORYTER']);
	Route::post('/updatehistory', [EmployeeController::class, 'FUNC_UPDATEHISTORY']);
	Route::post('/updatehistoryter', [EmployeeController::class, 'FUNC_UPDATEHISTORYTER']);
	Route::get('/deletehistory/{id}', [EmployeeController::class, 'FUNC_DELETEHISTORY']);
	Route::get('/GenerateSkExcel/{id}', [EmployeeController::class, 'FUNC_GENERATESKEXCEL']);
	Route::get('/GenerateSkPdf_Num/{id}', [EmployeeController::class, 'FUNC_GENERATESKPDF_NUM']);
	Route::get('/GenerateSkPdf_Alih/{id}', [EmployeeController::class, 'FUNC_GENERATESKPDF_ALIH']);
	// Route::get('/GenerateSkExcel', [EmployeeController::class, 'FUNC_GENERATESKEXCEL']);
	// Route::get('/GenerateSkPdf_Num', [EmployeeController::class, 'FUNC_GENERATESKPDF_NUM']);
	// Route::get('/GenerateSkPdf_Alih', [EmployeeController::class, 'FUNC_GENERATESKPDF_ALIH']);

	// PERPANJNANG KONTRAK //
	Route::get('/perpanjangkontrak/{nik}', [EmployeeController::class, 'FUNC_PERPANJANGKONTRAK']);
	Route::post('/saveperpanjangkontrak', [EmployeeController::class, 'FUNC_SAVEPERPANJANGKONTRAK']);
	Route::get('/editkontrak/{id}', [EmployeeController::class, 'FUNC_EDITKONTRAK']);
	Route::post('/savekontrak/{id}', [EmployeeController::class, 'FUNC_SAVEKONTRAK']);
	Route::get('/deletekontrak/{id}', [EmployeeController::class, 'FUNC_DELETEKONTRAK']);

	// RESIGN EMPLOYEE //
	Route::get('/resignemployee/{nik}', [EmployeeController::class, 'FUNC_RESIGNEMPLOYEE']);
	Route::post('/saveresign', [EmployeeController::class, 'FUNC_SAVERESIGN']);

	// Master
	Route::prefix('master')->group(function () {

		// Pangkat
		Route::prefix('grade')->group(function () {
			Route::get('/new', [MasterController::class, 'formGrade']);
			Route::post('/', [MasterController::class, 'createGrade']);
			Route::get('/', [MasterController::class, 'listGrade']);
			Route::get('/data', [MasterController::class, 'dataGrade']);
			Route::get('{id}/edit', [MasterController::class, 'formGrade']);
			Route::post('{id}/update', [MasterController::class, 'updateGrade']);
			Route::get('{id}/delete', [MasterController::class, 'deleteGrade']);
		});
	});

	//DATA JABATAN//
	Route::get('/jabatanlist', [MasterController::class, 'FUNC_MASTERJABATAN']);
	Route::get('/addjabatan', [MasterController::class, 'FUNC_ADDJABATAN']);
	Route::post('/savejabatan', [MasterController::class, 'FUNC_SAVEJABATAN']);
	Route::get('/editjabatan/{id}', [MasterController::class, 'FUNC_EDITJABATAN']);
	Route::post('/updatejabatan/{id}', [MasterController::class, 'FUNC_UPDATEJABATAN']);
	Route::get('/deletejabatan/{id}', [MasterController::class, 'FUNC_DELETEJABATAN']);

	//DATA DIVISI//
	Route::get('/divisilist', [MasterController::class, 'FUNC_MASTERDIVISI']);
	Route::get('/adddivisi', [MasterController::class, 'FUNC_ADDDIVISI']);
	Route::post('/savedivisi', [MasterController::class, 'FUNC_SAVEDIVISI']);
	Route::get('/editdivisi/{id}', [MasterController::class, 'FUNC_EDITDIVISI']);
	Route::post('/updatedivisi/{id}', [MasterController::class, 'FUNC_UPDATEDIVISI']);
	Route::get('/deletedivisi/{id}', [MasterController::class, 'FUNC_DELETEDIVISI']);

	//DATA SUBDIVISI//
	Route::get('/subdivisilist', [MasterController::class, 'FUNC_MASTERSUBDIVISI']);
	Route::get('/addsubdivisi', [MasterController::class, 'FUNC_ADDSUBDIVISI']);
	Route::post('/savesubdivisi', [MasterController::class, 'FUNC_SAVESUBDIVISI']);
	Route::get('/editsubdivisi/{id}', [MasterController::class, 'FUNC_EDITSUBDIVISI']);
	Route::post('/updatesubdivisi/{id}', [MasterController::class, 'FUNC_UPDATESUBDIVISI']);
	Route::get('/deletesubdivisi/{id}', [MasterController::class, 'FUNC_DELETESUBDIVISI']);

	//DATA GOLONGAN//
	Route::get('/golonganlist', [MasterController::class, 'FUNC_MASTERGOLONGAN']);
	Route::get('/addgolongan', [MasterController::class, 'FUNC_ADDGOLONGAN']);
	Route::post('/savegolongan', [MasterController::class, 'FUNC_SAVEGOLONGAN']);
	Route::get('/editgolongan/{id}', [MasterController::class, 'FUNC_EDITGOLONGAN']);
	Route::post('/updategolongan/{id}', [MasterController::class, 'FUNC_UPDATEGOLONGAN']);
	Route::get('/deletegolongan/{id}', [MasterController::class, 'FUNC_DELETEGOLONGAN']);

	//DATA GOLONGANOUT
	Route::get('/golonganoutlist', [MasterController::class, 'FUNC_MASTERGOLONGANOUT']);
	Route::get('/addgolonganout', [MasterController::class, 'FUNC_ADDGOLONGANOUT']);
	Route::post('/savegolonganout', [MasterController::class, 'FUNC_SAVEGOLONGANOUT']);
	Route::get('/editgolonganout/{id}', [MasterController::class, 'FUNC_EDITGOLONGANOUT']);
	Route::post('/updategolonganout/{id}', [MasterController::class, 'FUNC_UPDATEGOLONGANOUT']);
	Route::get('/deletegolonganout/{id}', [MasterController::class, 'FUNC_DELETEGOLONGANOUT']);

	//CUTI//
	Route::get('/addcuti', [CutiController::class, 'FUNC_ADDCUTI']);

	Route::post('/saveaddcuti', [CutiController::class, 'FUNC_SAVEADDCUTI']);
	Route::get('/hakcuti', [CutiController::class, 'FUNC_HAKCUTI']);
	Route::get('/edithakcuti/{id}', [CutiController::class, 'FUNC_EDITHAKCUTI']);
	Route::post('/updatehakcuti/{id}', [CutiController::class, 'FUNC_UPDATEHAKCUTI']);

	//KESEHATAN//

	Route::get('/hakkesehatan', [HealthController::class, 'FUNC_HAKKES']);
	Route::get('/addkesehatan', [HealthController::class, 'FUNC_ADDKES']);
	Route::get('/daftarreq', [HealthController::class, 'FUNC_LISTREQ']);
	Route::get('/detailreq/{id}', [HealthController::class, 'FUNC_DETAILREQ']);

	// Report
	Route::prefix('report')->group(function () {

		// Pegawai
		Route::prefix('employee')->group(function () {
			Route::get('/', [ReportController::class, 'listEmployee']);
			Route::get('data', [ReportController::class, 'dataEmployee']);
			Route::get('export', [ReportController::class, 'exportEmployee']);
		});

		// Cuti
		Route::prefix('offwork')->group(function () {
			Route::get('/', [ReportController::class, 'listOffWork']);
			Route::get('data', [ReportController::class, 'dataOffWork']);
			Route::get('export', [ReportController::class, 'exportOffWork']);
		});
	});

	Route::get('reportcuti', [ReportController::class, 'FUNC_REPORTCUTI']);
	Route::get('/filtercuti', [ReportController::class, 'FUNC_FILTERCUTI']);
	Route::get('getexportcuti', [ReportController::class, 'FUNC_GETEXPORTCUTI']);

	Route::get('reporttraining', [ReportController::class, 'FUNC_REPORTTRAINING']);
	Route::get('/filtertraining', [ReportController::class, 'FUNC_FILTERTRAINING']);
	Route::get('getexporttraining', [ReportController::class, 'FUNC_GETEXPORTTRAINING']);

	Route::get('reportkesehatan', [ReportController::class, 'FUNC_REPORTKESEHATAN']);
	Route::get('/filterkesehatan', [ReportController::class, 'FUNC_FILTERKESEHATAN']);
	Route::get('getexportkesehatan', [ReportController::class, 'FUNC_GETEXPORTKESEHATAN']);

	//EXPORT PDF
	Route::get('/cvlelang/{id_head}', [PdfController::class, 'FUNC_CVLELANG']);

	//IJIN
	Route::get('/listrequestizin', [IjinController::class, 'FUNC_LISTREQUESTIZIN']);
});

//LEMBUR
Route::get('reportlembur', [ReportController::class, 'FUNC_REPORTLEMBUR']);
Route::get('getexportlembur', [ReportController::class, 'FUNC_GETEXPORTLEMBUR']);

// Route::group(['middleware' => ['atasan']], function(){
// 	// LEMBURRRRRR //

// 		Route::get('/tambahspl',[LemburController::class, 'FUNC_TAMBAHSPL']);
// 		Route::post('/cektambahspl',[LemburController::class, 'FUNC_CEKTAMBAHSPL']);
// 		Route::post('/saveperintahlembur',[LemburController::class, 'FUNC_SAVETAMBAHSPL']);

// 	});

// LEMBURRRRR
Route::get('/tambahspl', [LemburController::class, 'FUNC_TAMBAHSPL']);
Route::post('/cektambahspl', [LemburController::class, 'FUNC_CEKTAMBAHSPL']);
Route::post('/saveperintahlembur', [LemburController::class, 'FUNC_SAVETAMBAHSPL']);

//SURAT PERINGATAN
// Route::get('/tambahsp',[EmployeeController::class, 'FUNC_TAMBAHSP']);
Route::get('/daftarsp', [EmployeeController::class, 'FUNC_DAFTARSP']);
Route::get('/tambahsp', [EmployeeController::class, 'FUNC_TAMBAHSP']);
Route::get('/downloadsp/{id}', [EmployeeController::class, 'FUNC_DOWNDOK']);
Route::get('/previewsp/{id}', [EmployeeController::class, 'FUNC_PREVIEWPDF']);
Route::post('/previewimgsp', [EmployeeController::class, 'FUNC_PREVIEWIMG']);
Route::post('/cektambahsp', [EmployeeController::class, 'FUNC_CEKTAMBAHSP']);
Route::post('/ceklastsp', [EmployeeController::class, 'FUNC_CEKLASTSP']);
Route::post('/saveperingatan', [EmployeeController::class, 'FUNC_SAVETAMBAHSP']);
Route::get('/editsp/{id}', [EmployeeController::class, 'FUNC_EDITSP']);
Route::post('updateperingatan/', [EmployeeController::class, 'FUNC_UPDATESP']);
Route::get('/deletesp/{id}', [EmployeeController::class, 'FUNC_DELETESP']);

// Authentication
Route::prefix('auth')->group(function () {

	// Login
	Route::prefix('login')->group(function () {
		Route::get('/', [AuthController::class, 'showLogin']);
		Route::post('/', [AuthController::class, 'doLogin']);
		Route::get('sso/{ssocode}', [AuthController::class, 'FUNC_LOGIN_SSO']);
		Route::get('sso', [AuthController::class, 'loginsso'])->middleware('cors');
	});

	Route::get('/logout', [AuthController::class, 'FUNC_LOGOUT']);
});

// Perusahaan
Route::get('company/{id}', [CompanyController::class, 'showProfile']);


Route::get('/addemployee', [EmployeeController::class, 'FUNC_ADDEMPLOYEE']);
Route::post('/saveemployee', [EmployeeController::class, 'FUNC_SAVEDATAPRIBADI']);
Route::get('/editemployee/{nik}', [EmployeeController::class, 'FUNC_EDITEMPLOYEE']);
Route::post('/updateemployee/{nik}', [EmployeeController::class, 'FUNC_UPDATEEMPLOYEE']);
Route::post('/updatepicture/{nik}', [EmployeeController::class, 'FUNC_UPDATEPICTURE']);
Route::post('/updatekeluarga/{nik}', [EmployeeController::class, 'FUNC_UPDATEKELUARGA']);
Route::post('/updateripek/{nik}', [EmployeeController::class, 'FUNC_UPDATERIPEK']);
Route::post('/updateripen/{nik}', [EmployeeController::class, 'FUNC_UPDATERIPEN']);
Route::get('/deleteripen/{id}', [EmployeeController::class, 'FUNC_DELETERIPEN']);
Route::post('/updateriwayatpenyakit/{nik}', [EmployeeController::class, 'FUNC_UPDATERIWAYATPENYAKIT']);
Route::get('/deleteriwayatpenyakit/{id}', [EmployeeController::class, 'FUNC_DELETERIWAYATPENYAKIT']);
Route::post('/updateripennon/{nik}', [EmployeeController::class, 'FUNC_UPDATERIPENNON']);
Route::get('/deleteripennon/{id}', [EmployeeController::class, 'FUNC_DELETERIPENNON']);
Route::post('/updateorangterdekat/{nik}', [EmployeeController::class, 'FUNC_UPDATEORANGTERDEKAT']);
Route::get('/deleteorangterdekat/{id}', [EmployeeController::class, 'FUNC_DELETEORANGTERDEKAT']);
Route::post('/updatekegiatan/{nik}', [EmployeeController::class, 'FUNC_UPDATEKEGIATAN']);
Route::get('/deletekegiatan/{nik}', [EmployeeController::class, 'FUNC_DELETEKEGIATAN']);
Route::get('/deleteanak/{nik}', [EmployeeController::class, 'FUNC_DELETEANAK']);
Route::post('/updateskill/{nik}', [EmployeeController::class, 'FUNC_UPDATESKILL']);
Route::get('/deleteskill/{id}', [EmployeeController::class, 'FUNC_DELETESKILL']);

// Route::get('/pdfcv',[PdfController::class, 'FUNC_GETPDFPROEX']);


// Detail + PROFILE //

Route::get('/profilemployee/{nik}', [EmployeeController::class, 'FUNC_PROFILEMPLOYEE']);

//CUTI

Route::get('/historycuti', [CutiController::class, 'FUNC_HISTORYCUTI']);
Route::get('/detailhistorycuti/{nik}', [CutiController::class, 'FUNC_DETAILHISTORYCUTI']);
Route::get('/editcuti/{id}', [CutiController::class, 'FUNC_EDITCUTI']);
Route::post('/updatecuti/{id}', [CutiController::class, 'FUNC_UPDATECUTI']);
Route::get('/deletecuti/{id}', [CutiController::class, 'FUNC_DELETECUTI']);
Route::post('/cekaddcuti', [CutiController::class, 'FUNC_CEKADDCUTI']);
Route::post('/selisihtanggal', [CutiController::class, 'FUNC_SELISIHTANGGAL']);
Route::get('/requestcuti', [CutiController::class, 'FUNC_REQUESTCUTI']);
Route::post('/saverequestcuti', [CutiController::class, 'FUNC_SAVEREQUESTCUTI']);
Route::get('/approvecuti', [CutiController::class, 'FUNC_APPROVECUTI']);
Route::post('/saveapprovecuti/{id}', [CutiController::class, 'FUNC_SAVEAPPROVECUTI']);
Route::get('/rejectcuti/{id}', [CutiController::class, 'FUNC_REJECTCUTI']);
Route::post('/saverejectcuti/{id}', [CutiController::class, 'FUNC_SAVEREJECTCUTI']);
Route::get('/catatancuti/{id}', [CutiController::class, 'FUNC_CATATANCUTI']);
Route::get('/actionapprove/{id}', [CutiController::class, 'FUNC_ACTIONAPPROVE']);
Route::get('/listrequestcuti', [CutiController::class, 'FUNC_LISTREQUESTCUTI']);

//Kesehatan
Route::get('/historykesehatan', [HealthController::class, 'FUNC_HISTORYKES']);

Route::get('/reqkesehatan', [HealthController::class, 'FUNC_REQKES']);

Route::post('savereqrj', [HealthController::class, 'FUNC_SAVEREQRAWATJALAN']);
Route::post('savereqkm', [HealthController::class, 'FUNC_SAVEREQRAWATKM']);
Route::post('savereqrg', [HealthController::class, 'FUNC_SAVEREQRAWATGIGI']);
Route::post('savereqmh', [HealthController::class, 'FUNC_SAVEREQRAWATLAHIR']);
Route::post('/cekrawatjalan', [HealthController::class, 'FUNC_CEKRAWATJALAN']);
Route::post('/buktikw', [HealthController::class, 'FUNC_BUKTIKW']);
Route::post('saverawatjalan', [HealthController::class, 'FUNC_SAVERAWATJALAN']);
//
Route::post('/cekrawatgigi', [HealthController::class, 'FUNC_CEKRAWATGIGI']);
Route::post('/buktikw2', [HealthController::class, 'FUNC_BUKTIKW2']);
Route::post('saverawatgigi', [HealthController::class, 'FUNC_SAVERAWATGIGI']);
//
Route::post('/cekrawatkm', [HealthController::class, 'FUNC_CEKRAWATKM']);
Route::post('/buktikw3', [HealthController::class, 'FUNC_BUKTIKW3']);
Route::post('saverawatkm', [HealthController::class, 'FUNC_SAVERAWATKM']);
//
Route::post('/cekrawatlahir', [HealthController::class, 'FUNC_CEKRAWATLAHIR']);
Route::post('/buktikw4', [HealthController::class, 'FUNC_BUKTIKW4']);
Route::post('saverawatlahir', [HealthController::class, 'FUNC_SAVERAWATLAHIR']);

//TRAINING
Route::get('/historytrain', [TrainingController::class, 'FUNC_HISTORYTRAIN']);
Route::get('/edittrain/{ID}', [TrainingController::class, 'FUNC_EDITTRAIN']);
Route::post('/updatetrain/{ID}', [TrainingController::class, 'FUNC_UPDATETRAIN']);
Route::get('/addtrain', [TrainingController::class, 'FUNC_ADDTRAIN']);
Route::post('/savetrain', [TrainingController::class, 'FUNC_SAVETRAIN']);
Route::get('/downloadtrain/{id}', [TrainingController::class, 'FUNC_DOWNDOK']);
Route::get('/previewtrain/{id}', [TrainingController::class, 'FUNC_PREVIEWPDF']);
Route::post('/previewimgtrain', [TrainingController::class, 'FUNC_PREVIEWIMG']);
Route::get('/deletetrain/{ID}', [TrainingController::class, 'FUNC_DELETETRAIN']);
Route::post('/cekaddtrain', [TrainingController::class, 'FUNC_CEKADDTRAIN']);


// IJIN //


Route::get('/historyijin', [IjinController::class, 'FUNC_HISTORYIJIN']);
Route::get('/requestijin', [IjinController::class, 'FUNC_REQUESTIJIN']);
Route::post('/saverequestijin', [IjinController::class, 'FUNC_SAVEREQUESTIJIN']);
Route::get('/approveijin', [IjinController::class, 'FUNC_APPROVEIJIN']);
Route::get('/prosesijin/{id}', [IjinController::class, 'FUNC_PROSESIJIN']);
// Route::get('/saveapproveijin/{id}',[IjinController::class, 'FUNC_SAVEAPPROVEIJIN']);
Route::post('/saveapproveijin', [IjinController::class, 'FUNC_SAVEAPPROVEIJIN']);
Route::get('/rejectijin/{id}', [IjinController::class, 'FUNC_REJECTIJIN']);
Route::post('/saverejectijin/{id}', [IjinController::class, 'FUNC_SAVEREJECTIJIN']);
Route::post('/cekrequestijin', [IjinController::class, 'FUNC_CEKREQUESTIJIN']);
//PTH
Route::get('/daftarpth', [EmployeeController::class, 'FUNC_DAFTARPTH']);
Route::get('/tambahpth', [EmployeeController::class, 'FUNC_TAMBAHPTH']);
Route::post('/cekaddpth', [EmployeeController::class, 'FUNC_CEKADDPTH']);
Route::post('/saveaddpth', [EmployeeController::class, 'FUNC_SAVEADDPTH']);
Route::get('/editpth/{id}', [EmployeeController::class, 'FUNC_EDITPTH']);
Route::post('/updatepth', [EmployeeController::class, 'FUNC_UPDATEPTH']);
Route::get('/deletepth/{id}', [EmployeeController::class, 'FUNC_DELETEPTH']);

// Route::post('/editpth',[EmployeeController::class, 'FUNC_EDITPTH']);

//PERJALANAN DINAS
Route::get('/tambahpd', [EmployeeController::class, 'FUNC_TAMBAHPD']);
Route::get('/daftarpd', [EmployeeController::class, 'FUNC_DAFTARPD']);
Route::post('/savetambahpd', [EmployeeController::class, 'FUNC_SAVETAMBAHPD']);
Route::post('/cektambahpd', [EmployeeController::class, 'FUNC_CEKTAMBAHPD']);
Route::get('/editpd/{id}', [EmployeeController::class, 'FUNC_EDITPD']);
Route::post('updatepd/{id}', [EmployeeController::class, 'FUNC_UPDATEPD']);

Route::get('/deletepd/{id}', [EmployeeController::class, 'FUNC_DELETESPD']);
Route::post('/callquerydetail', [EmployeeController::class, 'FUNC_DETAILPD']);
Route::get('/delete_pd_kar/{id}', [EmployeeController::class, 'FUNC_DELETESPDKAR']);
// CEK NIK //
Route::post('/ceknik', [EmployeeController::class, 'FUNC_CEKNIK',]);
Route::post('/cektglsk', [EmployeeController::class, 'FUNC_CEKTGLSK',]);
Route::post('/cektglcuti', [CutiController::class, 'FUNC_CEKTGLCUTI',]);

Route::post('/inputpth', [CutiController::class, 'FUNC_INPUTPTH',]);
Route::post('inputpthsave', [CutiController::class, 'FUNC_INPUTPTHSAVE']);
// LEMBUR //
Route::get('/historylembur', [LemburController::class, 'FUNC_HISTORYLEMBUR']);
Route::get('/detaillembur/{id}', [LemburController::class, 'FUNC_DETAILLEMBUR']);
Route::get('/saveapprovelembur/{id}', [LemburController::class, 'FUNC_SAVEAPPROVELEMBUR']);
Route::get('/editlembur/{id}', [LemburController::class, 'FUNC_EDITLEMBUR']);
Route::post('/saveeditlembur/{id}', [LemburController::class, 'FUNC_SAVEEDITLEMBUR']);
Route::get('/selesailembur/{id}', [LemburController::class, 'FUNC_SELESAILEMBUR']);
Route::post('/saveselesailembur/{id}', [LemburController::class, 'FUNC_SAVESELESAILEMBUR']);
Route::get('/listeditlembur', [LemburController::class, 'FUNC_LISTEDITLEMBUR']);
Route::get('/detaileditlembur/{id}', [LemburController::class, 'FUNC_DETAILEDITLEMBUR']);
Route::get('/approveubah/{id}', [LemburController::class, 'FUNC_APPROVEUBAH']);
Route::get('/rejectubah/{id}', [LemburController::class, 'FUNC_REJECTUBAH']);
Route::post('/saverejectubah/{id}', [LemburController::class, 'FUNC_SAVEREJECTUBAH']);
Route::get('/listlemburanselesai', [LemburController::class, 'FUNC_LISTLEMBURANSELESAI']);
Route::get('/pdflembur', [PdfController::class, 'FUNC_PDFLEMBUR']);

// DOKUMEN //
Route::get('/tambahdok', [DokumenController::class, 'FUNC_TAMBAHDOK']);
Route::post('/savedok', [DokumenController::class, 'FUNC_SAVEDOK']);
Route::get('/editdok/{id}', [DokumenController::class, 'FUNC_EDITDOK']);
Route::post('/updatedok/{id}', [DokumenController::class, 'FUNC_UPDATEDOK']);
Route::get('/listdok', [DokumenController::class, 'FUNC_LISTDOK']);
Route::get('/download/{id}', [DokumenController::class, 'FUNC_DOWNDOK']);
Route::get('/preview/{id}', [DokumenController::class, 'FUNC_PREVIEWPDF']);
Route::post('/previewimg', [[DokumenController::class, 'FUNC_PREVIEWIMG']]);

Route::get('/deletedok/{id}', [DokumenController::class, 'FUNC_DELETEDOK']);
Route::get('/daftarabsen', [AbsenController::class, 'FUNC_DAFTARABSEN']);
Route::get('/daftarpeg', [AbsenController::class, 'FUNC_DAFTARPEG']);
Route::get('/viewabsenpeg/{id}/{bulan}/{tahun}', [AbsenController::class, 'FUNC_VIEWABSENPEG']);
Route::post('/saveabsen', [AbsenController::class, 'FUNC_SAVEABSEN']);
Route::get('/approveabsen/{id}/{bulan}/{tahun}', [AbsenController::class, 'FUNC_APPROVEABSEN']);
Route::post('/appabsen/{id}/{bulan}/{tahun}', [AbsenController::class, 'FUNC_APPABSEN']);
Route::get('/apprabsen/{id}/{bulan}/{tahun}', [AbsenController::class, 'FUNC_APPRABSEN']);
Route::get('/rejectabsen/{id}/{bulan}/{tahun}', [AbsenController::class, 'FUNC_REJECTABSEN']);
Route::get('/searchabsen/{bulan}/{tahun}', [AbsenController::class, 'FUNC_SEARCHABSEN']);
Route::get('/searchabsenpeg/{id}/{bulan}/{tahun}', [AbsenController::class, 'FUNC_VIEWABSENPEG']);
Route::get('/listappabsen', [AbsenController::class, 'FUNC_LISTAPPABSEN']);

Route::get('/test_appcuti/{id}', [CutiController::class, 'TEST_CUTI']);
Route::get('/test_catatancuti/{id}', [CutiController::class, 'TEST_CATATANCUTI']);
Route::get('/test_rejectcuti/{id}', [CutiController::class, 'TEST_REJECTCUTI']);
Route::post('/test_saveapprovecuti/{id}', [CutiController::class, 'TEST_SAVEAPPROVECUTI']);
Route::post('/test_saverejectcuti/{id}', [CutiController::class, 'TEST_SAVEREJECTCUTI']);
