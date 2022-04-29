<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\EmployeesRequest;
use App\Http\Requests\Admin\HistoryContractsRequest;
use App\Http\Requests\Admin\HistorySalariesRequest;
use App\Http\Requests\Admin\HistoryPositionsRequest;
use App\Models\Admin\Employees;
use App\Models\Admin\Companies;
use App\Models\Admin\WorkingHours;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use App\Models\Admin\Areas;
use App\Models\Admin\HistoryContracts;
use App\Models\Admin\HistoryPositions;
use App\Models\Admin\HistorySalaries;
use App\Models\Admin\HistoryTrainingInternals;
use App\Models\Admin\HistoryTrainingEksternals;
use App\Models\Admin\HistoryFamilies;
use App\Models\Admin\InventoryLaptops;
use App\Models\Admin\InventoryMotorcycles;
use App\Models\Admin\InventoryCars;
use App\Models\Admin\MaksimalBpjskesehatans;
use App\Models\Admin\MaksimalBpjsketenagakerjaans;
use File;
use Storage;
use Alert;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'ACCOUNTING') {
            abort(403);
        }

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        //Produksi
        if ($divisi == 11) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [11, 19, 20,21])->get();
        }
        //PDC
        elseif ($divisi == 19) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [19, 20,21])->get();
        }
        //IC
        elseif ($divisi == 2) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [2])->get();
        }
        //Engineering
        elseif ($divisi == 7) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [7])->get();
        }
        //Quality
        elseif ($divisi == 8) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [8])->get();
        }
        //Purchasing
        elseif ($divisi == 9) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [9])->get();
        }
        //PPC
        elseif ($divisi == 10) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [12,13,14,15,18])->get();
        }
        //HRD-GA
        elseif ($divisi == 4) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->get();
        }
        //Accounting
        elseif ($divisi == 1) {
            $items = Employees::with([
                'areas',
                'divisions',
                'positions'
                ])->get();
        }
        else{
            abort(403);
        }
    
        return view('pages.admin.employees.index',[
            'items' => $items
        ]);
    }
    
    public function export_excel()
	{
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'ACCOUNTING') {
            abort(403);
        }
		return Excel::download(new EmployeesExport, 'databasekaryawan.xlsx');
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $companies      = Companies::all();
        $workinghours   = WorkingHours::all();
        $divisions      = Divisions::all();
        $positions      = Positions::all();
        $areas          = Areas::all();
        
        return view ('pages.admin.employees.create',[
            'companies'     => $companies,
            'workinghours'  => $workinghours,
            'divisions'     => $divisions,
            'positions'     => $positions,
            'areas'         => $areas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeesRequest $request)
    {
        //Input Employees
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $data = $request->all();

        $data['foto_karyawan'] = $request->file('foto_karyawan')->store(
            'assets/foto/karyawan','public'
        );
        $data['foto_ktp'] = $request->file('foto_ktp')->store(
            'assets/foto/ktp','public'
        );
        $data['foto_npwp'] = $request->file('foto_npwp')->store(
            'assets/foto/npwp','public'
        );
        $data['foto_kk'] = $request->file('foto_kk')->store(
            'assets/foto/kk','public'
        );
        Employees::create($data);
        //Input Employees

        //Input History Contracts
        //Hitung Bulan
        $date1 = date_create($request->input('tanggal_mulai_kerja')); 
        $date2 = date_create($request->input('tanggal_akhir_kerja')); 
        $interval = date_diff($date1,$date2);
        $masa_kontrak = $interval->m+1;
        if ($masa_kontrak == 12) {
            $masakontrak = "1 Tahun";
        }
        elseif ($masa_kontrak > 12) {
            $masakontrak = "Salah";
        }
        else{
            $masakontrak = $masa_kontrak." Bulan";
        }
        //Hitung Bulan
    
        // Input History Contracts
        HistoryContracts::create([
            'employees_id'                  => $request->input('nik_karyawan'),
            'tanggal_awal_kontrak'          => $request->input('tanggal_mulai_kerja'),
            'tanggal_akhir_kontrak'         => $request->input('tanggal_akhir_kerja'),
            'status_kontrak_kerja'          => $request->input('status_kerja'),
            'masa_kontrak'                  => $masakontrak,
            'jumlah_kontrak'                => 1,
            'input_oleh'                    => $request->input('input_oleh')
        ]);
        // Input History Contracts

        // Input History Positions
        $data['foto_karyawan'] = $request->file('foto_karyawan')->store(
            'assets/suratmutasi','public'
        );
        HistoryPositions::create([
            'employees_id'          => $request->input('nik_karyawan'),
            'companies_id_history'  => $request->input('companies_id'),
            'areas_id_history'      => $request->input('areas_id'),
            'divisions_id_history'  => $request->input('divisions_id'),
            'positions_id_history'  => $request->input('positions_id'),
            'tanggal_mutasi'        => $request->input('tanggal_mulai_kerja'),
            'file_surat_mutasi'     => $data['foto_karyawan'],
            'input_oleh'            => $request->input('input_oleh')
        ]);
        // Input History Positions

        // Rumus Gaji
        $gaji_pokok         = $request->input('gaji_pokok');
        $uang_makan         = $request->input('uang_makan');
        $uang_transport     = $request->input('uang_transport');
        $tunjangan_tugas    = $request->input('tunjangan_tugas');
        $tunjangan_pulsa    = $request->input('tunjangan_pulsa');
        $tunjangan_jabatan  = $request->input('tunjangan_jabatan');

        $jht                = $request->input('jht');
        $jp                 = $request->input('jp');
        $jkk                = $request->input('jkk');
        $jkm                = $request->input('jkm');
        $jkn                = $request->input('jkn');

        //Ikut Semua Kepesertaan BPJS Ketenagakerjaan Dan Kesehatan
        if ($jht != 0 && $jp != 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {       
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {

                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jp_perusahaan         = $jumlah_upah*2/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_karyawan           = $jumlah_upah*1/100;
            }
            elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
                $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
                $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
    
                $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
                $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
                $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = round($potongan_jp_perusahaan,0);
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = round($potongan_jp_karyawan,0);

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut Semua Kepesertaan BPJS Ketenagakerjaan Dan Kesehatan
        elseif ($jht == 0 && $jp == 0 && $jkk == 0 && $jkm == 0 && $jkn == 0) {
            
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = 0;
            $hasil_potongan_jkk_perusahaan      = 0;
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Ikut Kepesertaan BPJS Ketenagakerjaan Dan Tidak Ikut Kepesertaan BPJS Kesehatan
        elseif ($jht != 0 && $jp != 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jp_perusahaan         = $jumlah_upah*2/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_karyawan           = $jumlah_upah*1/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
                $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
                $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = round($potongan_jp_perusahaan,0);
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = round($potongan_jp_karyawan,0);

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Ikut Kepesertaan BPJS Kesehatan Dan Tidak Ikut Kepesertaan BPJS Ketenagakerjaan 
        elseif ($jht == 0 && $jp == 0 && $jkk == 0 && $jkm == 0 && $jkn != 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = 0;
            $hasil_potongan_jkk_perusahaan      = 0;
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut JHT Dan JP, Hanya Ikut JKK Dan JKM Dan Ikut Kepesertaan BPJS Kesehatan
        elseif ($jht == 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;

                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut JHT, JP, dan BPJS Kesehatan, Hanya Ikut JKK Dan JKM
        elseif ($jht == 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {       
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {    
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        }

        //Tidak Ikut JP, Hanya Ikut JHT, JKK Dan JKM Dan Ikut Kepesertaan BPJS Kesehatan
        elseif ($jht != 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
                $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
    
                $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
                $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut JP, dan BPJS Kesehatan, Hanya Ikut JHT, JKK , Dan JKM
        elseif ($jht != 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {       
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
                $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
                $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
                $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 
        
        //Kondisi Salah
        else {
            dd('Kondisi Salah');
        }
        // Rumus Gaji

        // Input History Salary
        HistorySalaries::create([
            'employees_id'                  => $request->input('nik_karyawan'),
            'gaji_pokok'                    => $gaji_pokok,
            'uang_makan'                    => $uang_makan,
            'uang_transport'                => $uang_transport,
            'tunjangan_tugas'               => $tunjangan_tugas,
            'tunjangan_pulsa'               => $tunjangan_pulsa,
            'tunjangan_jabatan'             => $tunjangan_jabatan,
            'jumlah_upah'                   => $jumlah_upah,
            'upah_lembur_perjam'            => $hasil_upah_lembur_perjam,
            'potongan_bpjsks_perusahaan'    => $hasil_potongan_bpjsks_perusahaan,
            'potongan_jht_perusahaan'       => $hasil_potongan_jht_perusahaan,
            'potongan_jp_perusahaan'        => $hasil_potongan_jp_perusahaan,
            'potongan_jkm_perusahaan'       => $hasil_potongan_jkm_perusahaan,
            'potongan_jkk_perusahaan'       => $hasil_potongan_jkk_perusahaan,
            'jumlah_bpjstk_perusahaan'      => $jumlah_bpjstk_perusahaan,
            'potongan_bpjsks_karyawan'      => $hasil_potongan_bpjsks_karyawan,
            'potongan_jht_karyawan'         => $hasil_potongan_jht_karyawan,
            'potongan_jp_karyawan'          => $hasil_potongan_jp_karyawan,
            'jumlah_bpjstk_karyawan'        => $jumlah_bpjstk_karyawan,
            'take_home_pay'                 => $take_home_pay,
            'input_oleh'                    => $request->input('input_oleh')
        ]);
        // Input History Salary

        Alert::success('Success Input Data Karyawan','Oleh '.auth()->user()->name);
        //Redirect
        return redirect()->route('employees.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'ACCOUNTING') {
            abort(403);
        }
        $item           = Employees::findOrFail($id);
        
        $companies      = Companies::all();
        $divisions      = Divisions::all();
        $positions      = Positions::all();
        $workinghours   = WorkingHours::all();
        $areas          = Areas::all();
        
        //
        $nikkaryawan    = $item->nik_karyawan;
        $salaries       = HistorySalaries::where('employees_id', $nikkaryawan)->first();

        //History Kontrak
        $historycontracts = HistoryContracts::with([
            'employees'
            ])->where('employees_id', $nikkaryawan)
            ->orderBy('tanggal_awal_kontrak', 'ASC')->get();
        
        //History Jabatan
        $historyjabatans = HistoryPositions::with([
            'employees',
            'companies',
            'divisions',
            'positions',
            'areas'
            ])->where('employees_id', $nikkaryawan)->get();
        //

        //History Training Internal
        $historytraininginternals = HistoryTrainingInternals::with([
            'employees'
            ])->where('employees_id', $nikkaryawan)->get();

        //History Training Eksternal
        $historytrainingeksternals = HistoryTrainingEksternals::with([
            'employees'
            ])->where('employees_id', $nikkaryawan)->get();

        //History Training Eksternal
        $historyfamilies = HistoryFamilies::with([
            'employees'
            ])->where('employees_id', $nikkaryawan)->get();

        return view ('pages.admin.employees.show',[
            'item'                      => $item,
            'companies'                 => $companies,
            'divisions'                 => $divisions,
            'positions'                 => $positions,
            'workinghours'              => $workinghours,
            'salaries'                  => $salaries,
            'historycontracts'          => $historycontracts,
            'historytraininginternals'  => $historytraininginternals,
            'historytrainingeksternals' => $historytrainingeksternals,
            'historyjabatans'           => $historyjabatans,
            'historyfamilies'           => $historyfamilies,
            'areas'                     => $areas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $item           = Employees::findOrFail($id);
        
        $companies      = Companies::all();
        $divisions      = Divisions::all();
        $positions      = Positions::all();
        $workinghours   = WorkingHours::all();
        $areas          = Areas::all();
        
        //
        $nikkaryawan    = $item->nik_karyawan;
        $salary         = HistorySalaries::where('employees_id', $nikkaryawan)->first();
        // 

        return view ('pages.admin.employees.edit',[
            'item'          => $item,
            'companies'     => $companies,
            'divisions'     => $divisions,
            'positions'     => $positions,
            'workinghours'  => $workinghours,
            'salary'        => $salary,
            'areas'         => $areas
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeesRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $data                               = $request->all();

        $item                               = Employees::findOrFail($id);
        
        $nikkaryawan                        = $item->nik_karyawan;
        
        //Jika ingin update Salary, Unlink Coment Berikut
        // $salary                             = HistorySalaries::where('employees_id', $nikkaryawan)->first();

        // // Rumus Gaji
        // $gaji_pokok         = $request->input('gaji_pokok');
        // $uang_makan         = $request->input('uang_makan');
        // $uang_transport     = $request->input('uang_transport');
        // $tunjangan_tugas    = $request->input('tunjangan_tugas');
        // $tunjangan_pulsa    = $request->input('tunjangan_pulsa');
        // $tunjangan_jabatan  = $request->input('tunjangan_jabatan');

        // $jht                = $request->input('jht');
        // $jp                 = $request->input('jp');
        // $jkk                = $request->input('jkk');
        // $jkm                = $request->input('jkm');
        // $jkn                = $request->input('jkn');

        // //Ikut Semua Kepesertaan BPJS Ketenagakerjaan Dan Kesehatan
        // if ($jht != 0 && $jp != 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {       
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {

        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
        //         $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
        //         $potongan_jp_perusahaan         = $jumlah_upah*2/100;
        //         $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
        //         $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
        //         $potongan_jht_karyawan          = $jumlah_upah*2/100;
        //         $potongan_jp_karyawan           = $jumlah_upah*1/100;
        //     }
        //     elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
        //         $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
        //         $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //         $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //         $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
        //         $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
    
        //         $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
        //         $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //         $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //         $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
        //     $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
        //     $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
        //     $hasil_potongan_jp_perusahaan       = round($potongan_jp_perusahaan,0);
        //     $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
        //     $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
        //     $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
        //     $hasil_potongan_jp_karyawan         = round($potongan_jp_karyawan,0);

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 

        // //Tidak Ikut Semua Kepesertaan BPJS Ketenagakerjaan Dan Kesehatan
        // elseif ($jht == 0 && $jp == 0 && $jkk == 0 && $jkm == 0 && $jkn == 0) {
            
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $hasil_potongan_bpjsks_perusahaan   = 0;
        //     $hasil_potongan_bpjsks_karyawan     = 0;
        //     $hasil_potongan_jht_perusahaan      = 0;
        //     $hasil_potongan_jp_perusahaan       = 0;
        //     $hasil_potongan_jkm_perusahaan      = 0;
        //     $hasil_potongan_jkk_perusahaan      = 0;
        //     $hasil_potongan_jht_karyawan        = 0;
        //     $hasil_potongan_jp_karyawan         = 0;

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 

        // //Ikut Kepesertaan BPJS Ketenagakerjaan Dan Tidak Ikut Kepesertaan BPJS Kesehatan
        // elseif ($jht != 0 && $jp != 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
        //         $potongan_jp_perusahaan         = $jumlah_upah*2/100;
        //         $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
        //         $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
        //         $potongan_jht_karyawan          = $jumlah_upah*2/100;
        //         $potongan_jp_karyawan           = $jumlah_upah*1/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
        //         $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //         $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //         $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = 0;
        //     $hasil_potongan_bpjsks_karyawan     = 0;
        //     $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
        //     $hasil_potongan_jp_perusahaan       = round($potongan_jp_perusahaan,0);
        //     $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
        //     $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
        //     $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
        //     $hasil_potongan_jp_karyawan         = round($potongan_jp_karyawan,0);

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 

        // //Ikut Kepesertaan BPJS Kesehatan Dan Tidak Ikut Kepesertaan BPJS Ketenagakerjaan 
        // elseif ($jht == 0 && $jp == 0 && $jkk == 0 && $jkm == 0 && $jkn != 0) {
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan) {
        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
        //         $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
        //     $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
        //     $hasil_potongan_jht_perusahaan      = 0;
        //     $hasil_potongan_jp_perusahaan       = 0;
        //     $hasil_potongan_jkm_perusahaan      = 0;
        //     $hasil_potongan_jkk_perusahaan      = 0;
        //     $hasil_potongan_jht_karyawan        = 0;
        //     $hasil_potongan_jp_karyawan         = 0;

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 

        // //Tidak Ikut JHT Dan JP, Hanya Ikut JKK Dan JKM Dan Ikut Kepesertaan BPJS Kesehatan
        // elseif ($jht == 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
        //         $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
        //         $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
        //     }
        //     elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
        //         $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;

        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
        //     $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
        //     $hasil_potongan_jht_perusahaan      = 0;
        //     $hasil_potongan_jp_perusahaan       = 0;
        //     $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
        //     $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
        //     $hasil_potongan_jht_karyawan        = 0;
        //     $hasil_potongan_jp_karyawan         = 0;

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 

        // //Tidak Ikut JHT, JP, dan BPJS Kesehatan, Hanya Ikut JKK Dan JKM
        // elseif ($jht == 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {       
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {    
        //         $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
        //         $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = 0;
        //     $hasil_potongan_bpjsks_karyawan     = 0;
        //     $hasil_potongan_jht_perusahaan      = 0;
        //     $hasil_potongan_jp_perusahaan       = 0;
        //     $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
        //     $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
        //     $hasil_potongan_jht_karyawan        = 0;
        //     $hasil_potongan_jp_karyawan         = 0;

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // }

        // //Tidak Ikut JP, Hanya Ikut JHT, JKK Dan JKM Dan Ikut Kepesertaan BPJS Kesehatan
        // elseif ($jht != 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
        //         $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
        //         $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
        //         $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
        //         $potongan_jht_karyawan          = $jumlah_upah*2/100;
        //     }
        //     elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
        //         $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
        //         $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //         $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
        //         $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
    
        //         $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //         $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
        //     $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
        //     $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
        //     $hasil_potongan_jp_perusahaan       = 0;
        //     $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
        //     $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
        //     $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
        //     $hasil_potongan_jp_karyawan         = 0;

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 

        // //Tidak Ikut JP, dan BPJS Kesehatan, Hanya Ikut JHT, JKK , Dan JKM
        // elseif ($jht != 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {       
        //     //End Rumus
        //     $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
        //     $upah_lembur_perjam                 = $jumlah_upah/173;
        //     $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

        //     $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
        //     $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

        //     if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
        //         $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
        //         $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
        //         $potongan_jht_karyawan          = $jumlah_upah*2/100;
        //     }
        //     elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
        //         $potongan_jht_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*3.7/100;
        //         $potongan_jkm_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.3/100;
        //         $potongan_jkk_perusahaan        = $maksimal_upah_bpjs_ketenagakerjaan*0.24/100;
        //         $potongan_jht_karyawan          = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
        //     }
        //     else{
        //         dd('Salah');
        //     }
            
        //     $hasil_potongan_bpjsks_perusahaan   = 0;
        //     $hasil_potongan_bpjsks_karyawan     = 0;
        //     $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
        //     $hasil_potongan_jp_perusahaan       = 0;
        //     $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
        //     $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
        //     $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
        //     $hasil_potongan_jp_karyawan         = 0;

        //     $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
        //     $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
        //     $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
        //     //End Rumus
        // } 
        
        // //Kondisi Salah
        // else {
        //     dd('Kondisi Salah');
        // }
        // // Rumus Gaji

        //Update Gaji
        // $salary->update([
        //     'employees_id'                  => $request->input('nik_karyawan'),
        //     'gaji_pokok'                    => $gaji_pokok,
        //     'uang_makan'                    => $uang_makan,
        //     'uang_transport'                => $uang_transport,
        //     'tunjangan_tugas'               => $tunjangan_tugas,
        //     'tunjangan_pulsa'               => $tunjangan_pulsa,
        //     'tunjangan_jabatan'             => $tunjangan_jabatan,
        //     'jumlah_upah'                   => $jumlah_upah,
        //     'upah_lembur_perjam'            => $hasil_upah_lembur_perjam,
        //     'potongan_bpjsks_perusahaan'    => $hasil_potongan_bpjsks_perusahaan,
        //     'potongan_jht_perusahaan'       => $hasil_potongan_jht_perusahaan,
        //     'potongan_jp_perusahaan'        => $hasil_potongan_jp_perusahaan,
        //     'potongan_jkm_perusahaan'       => $hasil_potongan_jkm_perusahaan,
        //     'potongan_jkk_perusahaan'       => $hasil_potongan_jkk_perusahaan,
        //     'jumlah_bpjstk_perusahaan'      => $jumlah_bpjstk_perusahaan,
        //     'potongan_bpjsks_karyawan'      => $hasil_potongan_bpjsks_karyawan,
        //     'potongan_jht_karyawan'         => $hasil_potongan_jht_karyawan,
        //     'potongan_jp_karyawan'          => $hasil_potongan_jp_karyawan,
        //     'jumlah_bpjstk_karyawan'        => $jumlah_bpjstk_karyawan,
        //     'take_home_pay'                 => $take_home_pay,
        //     'edit_oleh'                     => $request->input('edit_oleh')
        // ]);
        //Update Gaji

        //Unlink / Tambah Storage Images
        $karyawan       = $item->foto_karyawan;
        $ktp            = $item->foto_ktp;
        $npwp           = $item->foto_npwp;
        $kk             = $item->foto_kk;

        $foto_karyawan  = $request->file('foto_karyawan');
        $foto_ktp       = $request->file('foto_ktp');
        $foto_npwp      = $request->file('foto_npwp');
        $foto_kk        = $request->file('foto_kk');

        if(Storage::exists('public/'.$karyawan) && $foto_karyawan <> null){
            Storage::delete('public/'.$karyawan);
            $data['foto_karyawan'] = $request->file('foto_karyawan')->store(
                'assets/foto/karyawan','public'
            );
        }
        elseif (Storage::exists('public/'.$karyawan) && $foto_karyawan == null ) {
            $data['foto_karyawan'] = $karyawan;
        }
        else{
            dd('File does not exists.');
        }

        if(Storage::exists('public/'.$ktp) && $foto_ktp <> null){
            Storage::delete('public/'.$ktp);
            $data['foto_ktp'] = $request->file('foto_ktp')->store(
                'assets/foto/ktp','public'
            );
        }elseif (Storage::exists('public/'.$ktp) && $foto_ktp == null ) {
            $data['foto_ktp'] = $ktp;
        }else{
            dd('File does not exists.');
        }

        if(Storage::exists('public/'.$npwp) && $foto_npwp <> null){
            Storage::delete('public/'.$npwp);
            $data['foto_npwp'] = $request->file('foto_npwp')->store(
                'assets/foto/npwp','public'
            );
        }elseif (Storage::exists('public/'.$npwp) && $foto_npwp == null ) {
            $data['foto_npwp'] = $npwp;
        }else{
            dd('File does not exists.');
        }

        if(Storage::exists('public/'.$kk)&& $foto_kk <> null){
            Storage::delete('public/'.$kk);
            $data['foto_kk'] = $request->file('foto_kk')->store(
                'assets/foto/kk','public'
            );
        }elseif (Storage::exists('public/'.$kk) && $foto_kk == null ) {
            $data['foto_kk'] = $kk;
        }else{
            dd('File does not exists.');
        }
        //Unlink / Tambah Storage Images
        
        //Update Karyawan
        $item->update($data);
        Alert::info('Success Edit Data Karyawan','Oleh '.auth()->user()->name);
        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $item                   = Employees::findOrFail($id);
        $nikkaryawan            = $item->nik_karyawan;

        $contracts              = HistoryContracts::where('employees_id', $nikkaryawan)->get();
        $salary                 = HistorySalaries::where('employees_id', $nikkaryawan)->first();
        $familys                = HistoryFamilies::where('employees_id', $nikkaryawan)->get();
        $positions              = HistoryPositions::where('employees_id', $nikkaryawan)->get();
        $trainingeksternals     = HistoryTrainingEksternals::where('employees_id', $nikkaryawan)->get();
        $traininginternals      = HistoryTrainingInternals::where('employees_id', $nikkaryawan)->get();
        $inventorylaptop        = InventoryLaptops::where('employees_id', $nikkaryawan)->first();
        $inventorymotorcycle    = InventoryMotorcycles::where('employees_id', $nikkaryawan)->first();
        $inventorycar           = InventoryCars::where('employees_id', $nikkaryawan)->first();

        // dd($contracts);

        //Foto Karyawan
        $karyawan               = $item->foto_karyawan;
        $ktp                    = $item->foto_ktp;
        $npwp                   = $item->foto_npwp;
        $kk                     = $item->foto_kk;
        //End Foto Karyawan

        //Foto Keluarga
        // $dokumenhistorykeluarga = $family->dokumen_history_keluarga;
        
        // if ($dokumenhistorykeluarga <> "") {
        //     if(Storage::exists('public/'.$dokumenhistorykeluarga)){
        //         Storage::delete('public/'.$dokumenhistorykeluarga);
        //     }
        //     else{
        //         dd('File does not exists.');
        //     }
        // } else {
      
        // }
        //End Foto Keluarga

        //Foto Surat Mutasi
        // $suratmutasi            = $position->file_surat_mutasi;

        // if ($suratmutasi <> null) {
        //     if(Storage::exists('public/'.$suratmutasi)){
        //         Storage::delete('public/'.$suratmutasi);
        //     }
        //     else{
        //         dd('File does not exists.');
        //     }
        // } else {
    
        // }
        //End Foto Surat Mutasi

        // Aktifkan ini Jika Foto Mau Di Unlink
        // if(Storage::exists('public/'.$karyawan)){
        //     Storage::delete('public/'.$karyawan);
        // }
        // else{
        //     dd('File does not exists.');
        // }
        // if(Storage::exists('public/'.$ktp)){
        //     Storage::delete('public/'.$ktp);
        // }
        // else{
        //     dd('File does not exists.');
        // }
        // if(Storage::exists('public/'.$npwp)){
        //     Storage::delete('public/'.$npwp);
        // }
        // else{
        //     dd('File does not exists.');
        // }
        // if(Storage::exists('public/'.$kk)){
        //     Storage::delete('public/'.$kk);
        // }
        // else{
        //     dd('File does not exists.');
        // }
        // Aktifkan ini Jika Foto Mau Di Unlink
        
        $salary->delete();
        
        if ($contracts <> null) {
        foreach ($contracts as $contract ) {
            $contract->delete();
        }
        } else {}
        
        if ($familys <> null) {
            foreach ($familys as $family ) {
                $family->delete();
            }
        } else {}

        if ($positions <> null) {
            foreach ($positions as $position ) {
                $position->delete();
            }
        } else {}

        if ($trainingeksternals <> null) {
            foreach ($trainingeksternals as $trainingeksternal ) {
                $trainingeksternal->delete();
            }
        } else {}

        if ($traininginternals <> null) {
            foreach ($traininginternals as $traininginternal ) {
                $traininginternal->delete();
            }
        } else {}

        if ($inventorylaptop <> null) {
            $inventorylaptop->delete();
        } else {}

        if ($inventorymotorcycle <> null) {
            $inventorymotorcycle->delete();
        } else {}

        if ($inventorycar <> null) {
            $inventorycar->delete();
        } else {}

        $item->delete();
        Alert::error('Menghapus Data Karyawan','Oleh '.auth()->user()->name);
        return redirect()->route('employees.index');
    }
}
