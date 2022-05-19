<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CRMController extends Controller
{
    public function processToCRM()
    {
        // get last invoice
        $LastInvoice = DB::connection('pgsql')->table('invoice')->whereBetween('id', [203897, 204073])->get();
        foreach ($LastInvoice as $invoice) {

            $npwp = str_replace("-", "", str_replace(".", "", $invoice->npwp));
            $checkincrm = DB::connection('crm')->table('companies')->where('npwp', $npwp)->count();

            if ($checkincrm <= 0) {
                $customer_data = DB::connection('pgsql')
                                    ->table('customer')
                                    ->select('*',DB::raw('TRIM(BOTH FROM city) as city'), DB::raw('TRIM(BOTH FROM propinsi) as propinsi'))
                                    ->whereRaw("REPLACE(REPLACE(npwpno,'-',''),'.','') = '".$npwp."'")
                                    ->first();
                $customer_id = $customer_data->id;
                $customer_address = DB::connection('pgsql')
                                    ->table('customeraddress')
                                    ->select('customeraddress.address1',DB::raw('TRIM(BOTH FROM customeraddress.city) as city'),'customeraddress.createddate','customeraddress.fax','customeraddress.kecamatan',
                                    'customeraddress.kelurahan','contactperson.phone',DB::raw('TRIM(BOTH FROM customeraddress.propinsi) as propinsi'),
                                    'contactperson.email','contactperson.name','contactperson.mobile')
                                    ->leftjoin('contactperson','contactperson.customeraddressid','=','customeraddress.id')
                                    ->where('customeraddress.type','like','%AGIH%')
                                    ->where('contactperson.type','like','%AGIH%')
                                    ->whereNotNull('contactperson.email')
                                    ->where('customeraddress.customerid','=',$customer_id)
                                    ->orderby('contactperson.id','DESC')
                                    ->first();

                // dd(preg_replace('/\s+/', '', $customer_data->propinsi));
                // if ($customer_address) {
                //     dd($customer_data, $customer_address);
                // }
                if ($customer_data->propinsi == 'D K I JAKARTA') {
                    $customer_data->propinsi = 'DKI JAKARTA';
                }

                $state_cdbs = strtolower($customer_data->propinsi);
                $select_state = DB::connection('crm')->table('states')->whereRaw('LOWER(name) = "'.$state_cdbs.'"')->where('country_id', '102')->first();

                $city_cdbs = str_replace("kabupaten ", "", str_replace("kota ", "", strtolower($customer_data->city)));
                $select_city = DB::connection('crm')->table('cities')->whereRaw('LOWER(name) = "'.$city_cdbs.'"')->where('state_id', $select_state->id)->first();


                if ($customer_data->sales_group == '01') { // jkt
                    $salesgroup = '1';
                }elseif ($customer_data->sales_group == '06') { // bdg
                    $salesgroup = '2';
                }elseif ($customer_data->sales_group == '03') { // sby
                    $salesgroup = '5';
                }elseif ($customer_data->sales_group == '05') { // medan
                    $salesgroup = '6';
                }elseif ($customer_data->sales_group == '04') { // smg
                    $salesgroup = '7';
                }else{
                    $salesgroup = '';
                }

                /***** Start insert to CRM ******/

                if ($customer_address) {
                    // dd($customer_data, $customer_address, $select_state, $select_city);
                    // insert table user
                    $arrayUser = array(
                                        "email"         => $customer_address->email,
                                        "first_name"    => isset($customer_address->name) ? $customer_address->name : '-',
                                        "phone_number"  => isset($customer_address->phone) ? $customer_address->phone : '-',
                                        "user_id"       => '1',
                                    );

                    $insertUser = DB::connection("crm")->table("users")->insertGetId($arrayUser);
                    $id_user = $insertUser;

                    // insert table role users
                    $arrayRoleUsers = array(
                                            "user_id" => $id_user,
                                            "role_id" => '3',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        );

                    $insertRoleUsers = DB::connection("crm")->table('role_users')->insert($arrayRoleUsers);

                    // insert company
                    $arrayCompany = array(
                                        "name" => $customer_data->customername,
                                        "email" => $customer_address->email,
                                        "address" => $customer_data->address1,
                                        "website" => $customer_data->website,
                                        "phone" => $customer_data->phone,
                                        "mobile" => $customer_data->phone,
                                        "fax" => $customer_data->fax,
                                        "title" => '',
                                        "sales_team_id" => $salesgroup,
                                        "country_id" => "102",
                                        "state_id" => $select_state->id,
                                        "city_id" => $select_city->id,
                                        "company_scale_id" => '2',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                        "npwp" => $npwp,
                                        "kecamatan" => $customer_data->kecamatan,
                                        "kelurahan" => $customer_data->kelurahan
                                    );
                    $insertCompany = DB::connection('crm')->table('companies')->insertGetId($arrayCompany);
                    $id_company = $insertCompany;

                    // insert customers
                    $arrayCustomers = array(
                                            "user_id" => $id_user,
                                            "address" => $customer_address->address1,
                                            "website" => $customer_data->website,
                                            "mobile" => $customer_address->mobile,
                                            "fax" => $customer_address->fax,
                                            "title" => '',
                                            "company_id" => $id_company,
                                            "sales_team_id" => $salesgroup,
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                            "first_name" => $customer_address->name,
                                            "email" => $customer_address->email,
                                            "phone_number" => isset($customer_address->phone) ? $customer_address->phone : '',
                                        );
                    $insertCustomers = DB::connection('crm')->table('customers')->insertGetId($arrayCustomers);
                }


                // return $npwp.' - '.$customer_data->customername;
            }
        }

        return 'done';
    }

}
