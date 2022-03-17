<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Employees;
use App\Models\Admin\Companies;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use App\Models\Admin\HistoryContracts;
use App\Models\Admin\HistoryFamilies;
use Alert;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        toast('Hello '.auth()->user()->name,'success');


        //Halaman Karyawan
        $nik_karyawan = auth()->user()->nik;

        $datakaryawan = Employees::with([
            'companies',
            'areas',
            'divisions',
            'positions'
            ])->where('nik_karyawan', $nik_karyawan)->first();
        $datahistorykontraks = HistoryContracts::with([
            'employees'
            ])->where('employees_id', $nik_karyawan)->get();
        $datahistorykeluargas = HistoryFamilies::with([
            'employees'
            ])->where('employees_id', $nik_karyawan)->get();

        $historykontrak = HistoryContracts::with([
            'employees'
            ])->where('employees_id', $nik_karyawan)->first();
        $historykeluarga = HistoryFamilies::with([
            'employees'
            ])->where('employees_id', $nik_karyawan)->first();
        //Halaman Karyawan

        //Halaman Leader

        $caridivisi     = Employees::all()->where('nik_karyawan', $nik_karyawan)->first();
        $divisi         = $caridivisi->divisions_id;
        
        $itemleaders     = Employees::with([
            'areas',
            'divisions',
            'positions'
            ])->where('divisions_id', $divisi)->orderBy('positions_id')->get();

        //Halaman Leader

        //Halaman Admin HRD Accounting
        $itembsd = Employees::with([
            'areas'
            ])->where('areas_id', 2)->count();
        $itemaw = Employees::with([
            'areas'
            ])->where('areas_id', 1)->count();
        $itemsunter = Employees::with([
            'areas'
            ])->where('areas_id', 3)->count();
        $itemcibinong = Employees::with([
            'areas'
            ])->where('areas_id', 4)->count();
        $itemcibitung = Employees::with([
            'areas'
            ])->where('areas_id', 5)->count();
        $itemkarawangtimur = Employees::with([
            'areas'
            ])->where('areas_id', 6)->count();
        $itembl = Employees::with([
            'areas'
            ])->where('areas_id', 7)->count();
        
        $itempdc = $itemsunter+$itemcibinong+$itemcibitung+$itemkarawangtimur;
        $itemall = $itembsd+$itemaw+$itembl+$itemsunter+$itemcibinong+$itemcibitung+$itemkarawangtimur;
        
        // Penempatan
        $itemaccounting = Employees::with([
            'divisions'
            ])->where('divisions_id', 1)->count();
        $itemic = Employees::with([
            'divisions'
            ])->where('divisions_id', 2)->count();
        $itemit = Employees::with([
            'divisions'
            ])->where('divisions_id', 3)->count();
        $itemhrd = Employees::with([
            'divisions'
            ])->where('divisions_id', 4)->count();
        $itemdoccontrol = Employees::with([
            'divisions'
            ])->where('divisions_id', 5)->count();
        $itemmarketing = Employees::with([
            'divisions'
            ])->where('divisions_id', 6)->count();
        $itemengineering = Employees::with([
            'divisions'
            ])->where('divisions_id', 7)->count();
        $itemquality = Employees::with([
            'divisions'
            ])->where('divisions_id', 8)->count();
        $itempurchasing = Employees::with([
            'divisions'
            ])->where('divisions_id', 9)->count();
        $itemppc = Employees::with([
            'divisions'
            ])->where('divisions_id', 10)->count();
        $itemproduksi = Employees::with([
            'divisions'
            ])->where('divisions_id', 11)->count();
        $itemdeliveryproduksi = Employees::with([
            'divisions'
            ])->where('divisions_id', 12)->count();
        $itemgudangrm = Employees::with([
            'divisions'
            ])->where('divisions_id', 13)->count();
        $itemgudangfg = Employees::with([
            'divisions'
            ])->where('divisions_id', 14)->count();
        $itemdelivery = Employees::with([
            'divisions'
            ])->where('divisions_id', 15)->count();
        $itemsecurity = Employees::with([
            'divisions'
            ])->where('divisions_id', 16)->count();
        $itemblokbl = Employees::with([
            'divisions'
            ])->where('divisions_id', 17)->count();
        $itembloke = Employees::with([
            'divisions'
            ])->where('divisions_id', 18)->count();
        $itempdcdaihatsusunter = Employees::with([
            'divisions'
            ])->where('divisions_id', 19)->count();
        $itempdcdaihatsucibinong = Employees::with([
            'divisions'
            ])->where('divisions_id', 20)->count();
        $itempdcdaihatsucibitung = Employees::with([
            'divisions'
            ])->where('divisions_id', 21)->count();
        $itempdcdaihatsukarawangtimur = Employees::with([
            'divisions'
            ])->where('divisions_id', 22)->count();
        $itemjumlahgreenville   = $itemaccounting+$itembl+$itemic+$itemit;
        $itemjumlahhrd          = $itemhrd+$itemsecurity;
        $itemjumlahppc          = $itemppc+$itemdelivery+$itemdeliveryproduksi+$itembloke+$itemgudangrm+$itemgudangfg;
        $itemjumlahproduksi     = $itemproduksi+$itempdcdaihatsusunter+$itempdcdaihatsucibinong+$itempdcdaihatsucibitung+$itempdcdaihatsukarawangtimur;
        // Penempatan

        //Status Kontrak
        $itemkontrak = Employees::all()
            ->where('status_kerja', 'PKWT')
            ->count();
        $itemtetap = Employees::all()
            ->where('status_kerja', 'PKWTT')
            ->count();
        $itemharian = Employees::all()
            ->where('status_kerja', 'Harian')
            ->count();
        $itemoutsourcing = Employees::all()
            ->where('status_kerja', 'Outsourcing')
            ->count();
        //Status Kontrak
        
        //Status Menikah
        $itemsingle = Employees::all()
        ->where('status_nikah', 'Single')
        ->count();
        $itemmenikah = Employees::all()
        ->where('status_nikah', 'Menikah')
        ->count();
        $itemjanda = Employees::all()
        ->where('status_nikah', 'Janda')
        ->count();
        $itemduda = Employees::all()
        ->where('status_nikah', 'Duda')
        ->count();
        //Status Menikah

        //Jenis Kelamin
        $itempria = Employees::all()
        ->where('jenis_kelamin', 'Pria')
        ->count();
        $itemwanita = Employees::all()
        ->where('jenis_kelamin', 'Wanita')
        ->count();
        //Jenis Kelamin

        //Agama
        $itemislam = Employees::all()
        ->where('agama', 'Islam')
        ->count();
        $itemkristenprotestan = Employees::all()
        ->where('agama', 'Kristen Protestan')
        ->count();
        $itemkristenkatholik = Employees::all()
        ->where('agama', 'Kristen Katholik')
        ->count();
        $itemhindu = Employees::all()
        ->where('agama', 'Hindu')
        ->count();
        $itembudha = Employees::all()
        ->where('agama', 'Budha')
        ->count();
        //Agama

        // Penempatan Detail
        $itemaccountingpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 1)->where('status_kerja', 'PKWTT')->count();
        $itemaccountingpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 1)->where('status_kerja', 'PKWT')->count();
        $itemaccountingharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 1)->where('status_kerja', 'Harian')->count();
        $itemaccountingoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 1)->where('status_kerja', 'Outsourcing')->count();

        $itemicpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 2)->where('status_kerja', 'PKWTT')->count();
        $itemicpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 2)->where('status_kerja', 'PKWT')->count();
        $itemicharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 2)->where('status_kerja', 'Harian')->count();
        $itemicoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 2)->where('status_kerja', 'Outsourcing')->count();

            
        $itemitpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 3)->where('status_kerja', 'PKWTT')->count();
        $itemitpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 3)->where('status_kerja', 'PKWT')->count();
        $itemitharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 3)->where('status_kerja', 'Harian')->count();
        $itemitoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 3)->where('status_kerja', 'Outsourcing')->count();

        $itemhrdpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 4)->where('status_kerja', 'PKWTT')->count();
        $itemhrdpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 4)->where('status_kerja', 'PKWT')->count();
        $itemhrdharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 4)->where('status_kerja', 'Harian')->count();
        $itemhrdoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 4)->where('status_kerja', 'Outsourcing')->count();

        $itemdoccontrolpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 5)->where('status_kerja', 'PKWTT')->count();
        $itemdoccontrolpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 5)->where('status_kerja', 'PKWT')->count();
        $itemdoccontrolharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 5)->where('status_kerja', 'Harian')->count();
        $itemdoccontroloutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 5)->where('status_kerja', 'Outsourcing')->count();

        $itemmarketingpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 6)->where('status_kerja', 'PKWTT')->count();
        $itemmarketingpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 6)->where('status_kerja', 'PKWT')->count();
        $itemmarketingharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 6)->where('status_kerja', 'Harian')->count();
        $itemmarketingoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 6)->where('status_kerja', 'Outsourcing')->count();

        $itemengineeringpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 7)->where('status_kerja', 'PKWTT')->count();
        $itemengineeringpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 7)->where('status_kerja', 'PKWT')->count();
        $itemengineeringharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 7)->where('status_kerja', 'Harian')->count();
        $itemengineeringoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 7)->where('status_kerja', 'Outsourcing')->count();

        $itemqualitypkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 8)->where('status_kerja', 'PKWTT')->count();
        $itemqualitypkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 8)->where('status_kerja', 'PKWT')->count();
        $itemqualityharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 8)->where('status_kerja', 'Harian')->count();
        $itemqualityoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 8)->where('status_kerja', 'Outsourcing')->count();

        $itempurchasingpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 9)->where('status_kerja', 'PKWTT')->count();
        $itempurchasingpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 9)->where('status_kerja', 'PKWT')->count();
        $itempurchasingharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 9)->where('status_kerja', 'Harian')->count();
        $itempurchasingoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 9)->where('status_kerja', 'Outsourcing')->count();

        $itemppcpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 10)->where('status_kerja', 'PKWTT')->count();
        $itemppcpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 10)->where('status_kerja', 'PKWT')->count();
        $itemppcharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 10)->where('status_kerja', 'Harian')->count();
        $itemppcoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 10)->where('status_kerja', 'Outsourcing')->count();

        $itemproduksipkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 11)->where('status_kerja', 'PKWTT')->count();
        $itemproduksipkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 11)->where('status_kerja', 'PKWT')->count();
        $itemproduksiharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 11)->where('status_kerja', 'Harian')->count();
        $itemproduksioutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 11)->where('status_kerja', 'Outsourcing')->count();

        $itemdeliveryproduksipkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 12)->where('status_kerja', 'PKWTT')->count();
        $itemdeliveryproduksipkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 12)->where('status_kerja', 'PKWT')->count();
        $itemdeliveryproduksiharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 12)->where('status_kerja', 'Harian')->count();
        $itemdeliveryproduksioutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 12)->where('status_kerja', 'Outsourcing')->count();
        
        //
        $itemgudangrmpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 13)->where('status_kerja', 'PKWTT')->count();
        $itemgudangrmpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 13)->where('status_kerja', 'PKWT')->count();
        $itemgudangrmharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 13)->where('status_kerja', 'Harian')->count();
        $itemgudangrmoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 13)->where('status_kerja', 'Outsourcing')->count();
        //
        $itemgudangfgpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 14)->where('status_kerja', 'PKWTT')->count();
        $itemgudangfgpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 14)->where('status_kerja', 'PKWT')->count();
        $itemgudangfgharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 14)->where('status_kerja', 'Harian')->count();
        $itemgudangfgoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 14)->where('status_kerja', 'Outsourcing')->count();
        //
        $itemdeliverypkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 15)->where('status_kerja', 'PKWTT')->count();
        $itemdeliverypkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 15)->where('status_kerja', 'PKWT')->count();
        $itemdeliveryharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 15)->where('status_kerja', 'Harian')->count();
        $itemdeliveryoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 15)->where('status_kerja', 'Outsourcing')->count();
        //
        $itemsecuritypkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 16)->where('status_kerja', 'PKWTT')->count();
        $itemsecuritypkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 16)->where('status_kerja', 'PKWT')->count();
        $itemsecurityharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 16)->where('status_kerja', 'Harian')->count();
        $itemsecurityoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 16)->where('status_kerja', 'Outsourcing')->count();
        //
        $itemblokblpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 17)->where('status_kerja', 'PKWTT')->count();
        $itemblokblpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 17)->where('status_kerja', 'PKWT')->count();
        $itemblokblharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 17)->where('status_kerja', 'Harian')->count();
        $itemblokbloutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 17)->where('status_kerja', 'Outsourcing')->count();
        //
        $itemblokepkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 18)->where('status_kerja', 'PKWTT')->count();
        $itemblokepkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 18)->where('status_kerja', 'PKWT')->count();
        $itemblokeharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 18)->where('status_kerja', 'Harian')->count();
        $itemblokeoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 18)->where('status_kerja', 'Outsourcing')->count();
        //
        $itempdcdaihatsusunterpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 19)->where('status_kerja', 'PKWTT')->count();
        $itempdcdaihatsusunterpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 19)->where('status_kerja', 'PKWT')->count();
        $itempdcdaihatsusunterharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 19)->where('status_kerja', 'Harian')->count();
        $itempdcdaihatsusunteroutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 19)->where('status_kerja', 'Outsourcing')->count();
        //
        $itempdcdaihatsucibinongpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 20)->where('status_kerja', 'PKWTT')->count();
        $itempdcdaihatsucibinongpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 20)->where('status_kerja', 'PKWT')->count();
        $itempdcdaihatsucibinongharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 20)->where('status_kerja', 'Harian')->count();
        $itempdcdaihatsucibinongoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 20)->where('status_kerja', 'Outsourcing')->count();
        //
        $itempdcdaihatsucibitungpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 21)->where('status_kerja', 'PKWTT')->count();
        $itempdcdaihatsucibitungpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 21)->where('status_kerja', 'PKWT')->count();
        $itempdcdaihatsucibitungharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 21)->where('status_kerja', 'Harian')->count();
        $itempdcdaihatsucibitungoutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 21)->where('status_kerja', 'Outsourcing')->count();
        //
        $itempdcdaihatsukarawangtimurpkwtt = Employees::with([
            'divisions'
            ])->where('divisions_id', 22)->where('status_kerja', 'PKWTT')->count();
        $itempdcdaihatsukarawangtimurpkwt = Employees::with([
            'divisions'
            ])->where('divisions_id', 22)->where('status_kerja', 'PKWT')->count();
        $itempdcdaihatsukarawangtimurharian = Employees::with([
            'divisions'
            ])->where('divisions_id', 22)->where('status_kerja', 'Harian')->count();
        $itempdcdaihatsukarawangtimuroutsourcing = Employees::with([
            'divisions'
            ])->where('divisions_id', 22)->where('status_kerja', 'Outsourcing')->count();
        //
        // Penempatan Detail
        
        return view('pages.admin.dashboard',[
            //Halaman Karyawan
            'datakaryawan'                  => $datakaryawan,
            'datahistorykontraks'           => $datahistorykontraks,
            'datahistorykeluargas'          => $datahistorykeluargas,
            'historykontrak'                => $historykontrak,
            'historykeluarga'               => $historykeluarga,
            //Halaman Karyawan

            //Halaman Leader
            'itemleaders'                   => $itemleaders,
            //Halaman Leader
            
            'itempdc'                       => $itempdc,
            'itemall'                       => $itemall,
            'itemaw'                        => $itemaw,
            'itembsd'                       => $itembsd,
            'itemkontrak'                   => $itemkontrak,
            'itemtetap'                     => $itemtetap,
            'itemharian'                    => $itemharian,
            'itemoutsourcing'               => $itemoutsourcing,
            'itemsingle'                    => $itemsingle,
            'itemmenikah'                   => $itemmenikah,
            'itemjanda'                     => $itemjanda,
            'itemduda'                      => $itemduda,
            'itempria'                      => $itempria,
            'itemwanita'                    => $itemwanita,
            'itemislam'                     => $itemislam,
            'itemkristenprotestan'          => $itemkristenprotestan,
            'itemkristenkatholik'           => $itemkristenkatholik,
            'itemhindu'                     => $itemhindu,
            'itembudha'                     => $itembudha,
            'itemaccounting'                => $itemaccounting,
            'itemic'                        => $itemic,
            'itemit'                        => $itemit,
            'itemhrd'                       => $itemhrd,
            'itemdoccontrol'                => $itemdoccontrol,
            'itemmarketing'                 => $itemmarketing,
            'itemengineering'               => $itemengineering,
            'itemquality'                   => $itemquality,
            'itempurchasing'                => $itempurchasing,
            'itemppc'                       => $itemppc,
            'itemproduksi'                  => $itemproduksi,
            'itemdeliveryproduksi'          => $itemdeliveryproduksi,
            'itemgudangrm'                  => $itemgudangrm,
            'itemgudangfg'                  => $itemgudangfg,
            'itemdelivery'                  => $itemdelivery,
            'itemsecurity'                  => $itemsecurity,
            'itemblokbl'                    => $itemblokbl,
            'itembloke'                     => $itembloke,
            'itempdcdaihatsusunter'         => $itempdcdaihatsusunter,
            'itempdcdaihatsucibinong'       => $itempdcdaihatsucibinong,
            'itempdcdaihatsucibitung'       => $itempdcdaihatsucibitung,
            'itempdcdaihatsukarawangtimur'  => $itempdcdaihatsukarawangtimur,
            'itemjumlahgreenville'          => $itemjumlahgreenville,
            'itemjumlahhrd'                 => $itemjumlahhrd,
            'itemjumlahppc'                 => $itemjumlahppc,
            'itemjumlahproduksi'            => $itemjumlahproduksi,
            'itemaccountingpkwtt'   => $itemaccountingpkwtt,
            'itemaccountingpkwt' => $itemaccountingpkwt,
            'itemaccountingharian' => $itemaccountingharian,
            'itemaccountingoutsourcing' => $itemaccountingoutsourcing,
            'itemicpkwtt' => $itemicpkwtt,
            'itemicpkwt' => $itemicpkwt,
            'itemicharian' => $itemicharian,
            'itemicoutsourcing' => $itemicoutsourcing,
            'itemitpkwtt' => $itemitpkwtt,
            'itemitpkwt' => $itemitpkwt,
            'itemitharian' => $itemitharian,
            'itemitoutsourcing' => $itemitoutsourcing,
            'itemhrdpkwtt' => $itemhrdpkwtt,
            'itemhrdpkwt' => $itemhrdpkwt,
            'itemhrdharian' => $itemhrdharian,
            'itemhrdoutsourcing' => $itemhrdoutsourcing,
            'itemdoccontrolpkwtt' => $itemdoccontrolpkwtt,
            'itemdoccontrolpkwt' => $itemdoccontrolpkwt,
            'itemdoccontrolharian' => $itemdoccontrolharian,
            'itemdoccontroloutsourcing' => $itemdoccontroloutsourcing,
            'itemmarketingpkwtt' => $itemmarketingpkwtt,
            'itemmarketingpkwt' => $itemmarketingpkwt,
            'itemmarketingharian' => $itemmarketingharian,
            'itemmarketingoutsourcing' => $itemmarketingoutsourcing,
            'itemengineeringpkwtt' => $itemengineeringpkwtt,
            'itemengineeringpkwt' => $itemengineeringpkwt,
            'itemengineeringharian' => $itemengineeringharian,
            'itemengineeringoutsourcing' => $itemengineeringoutsourcing,
            'itemqualitypkwtt' => $itemqualitypkwtt,
            'itemqualitypkwt' => $itemqualitypkwt,
            'itemqualityharian' => $itemqualityharian,
            'itemqualityoutsourcing' => $itemqualityoutsourcing,
            'itempurchasingpkwtt' => $itempurchasingpkwtt,
            'itempurchasingpkwt' => $itempurchasingpkwt,
            'itempurchasingharian' => $itempurchasingharian,
            'itempurchasingoutsourcing' => $itempurchasingoutsourcing,
            'itemppcpkwtt' => $itemppcpkwtt,
            'itemppcpkwt' => $itemppcpkwt,
            'itemppcharian' => $itemppcharian,
            'itemppcoutsourcing' => $itemppcoutsourcing,
            'itemproduksipkwtt' => $itemproduksipkwtt,
            'itemproduksipkwt' => $itemproduksipkwt,
            'itemproduksiharian' => $itemproduksiharian,
            'itemproduksioutsourcing' => $itemproduksioutsourcing,
            'itemdeliveryproduksipkwtt' => $itemdeliveryproduksipkwtt,
            'itemdeliveryproduksipkwt' => $itemdeliveryproduksipkwt,
            'itemdeliveryproduksiharian' => $itemdeliveryproduksiharian,
            'itemdeliveryproduksioutsourcing' => $itemdeliveryproduksioutsourcing,
            'itemdeliveryproduksipkwtt' => $itemdeliveryproduksipkwtt,
            'itemdeliveryproduksipkwt' => $itemdeliveryproduksipkwt,
            'itemdeliveryproduksiharian' => $itemdeliveryproduksiharian,
            'itemdeliveryproduksioutsourcing' => $itemdeliveryproduksioutsourcing,
            'itemgudangrmpkwtt' => $itemgudangrmpkwtt,
            'itemgudangrmpkwt' => $itemgudangrmpkwt,
            'itemgudangrmharian' => $itemgudangrmharian,
            'itemgudangrmoutsourcing' => $itemgudangrmoutsourcing,
            'itemgudangfgpkwtt' => $itemgudangfgpkwtt,
            'itemgudangfgpkwt' => $itemgudangfgpkwt,
            'itemgudangfgharian' => $itemgudangfgharian,
            'itemgudangfgoutsourcing' => $itemgudangfgoutsourcing,
            'itemdeliverypkwtt' => $itemdeliverypkwtt,
            'itemdeliverypkwt' => $itemdeliverypkwt,
            'itemdeliveryharian' => $itemdeliveryharian,
            'itemdeliveryoutsourcing' => $itemdeliveryoutsourcing,
            'itemsecuritypkwtt' => $itemsecuritypkwtt,
            'itemsecuritypkwt' => $itemsecuritypkwt,
            'itemdeliveryharian' => $itemdeliveryharian,
            'itemsecurityoutsourcing' => $itemsecurityoutsourcing,
            'itemblokblpkwtt' => $itemblokblpkwtt,
            'itemblokblpkwt' => $itemblokblpkwt,
            'itemblokblharian' => $itemblokblharian,
            'itemblokbloutsourcing' => $itemblokbloutsourcing,
            'itemblokepkwtt' => $itemblokepkwtt,
            'itemblokepkwt' => $itemblokepkwt,
            'itemblokeharian' => $itemblokeharian,
            'itemblokeoutsourcing' => $itemblokeoutsourcing,
            'itempdcdaihatsusunterpkwtt' => $itempdcdaihatsusunterpkwtt,
            'itempdcdaihatsusunterpkwt' => $itempdcdaihatsusunterpkwt,
            'itempdcdaihatsusunterharian' => $itempdcdaihatsusunterharian,
            'itempdcdaihatsusunteroutsourcing' => $itempdcdaihatsusunteroutsourcing,
            'itempdcdaihatsucibinongpkwtt' => $itempdcdaihatsucibinongpkwtt,
            'itempdcdaihatsucibinongpkwt' => $itempdcdaihatsucibinongpkwt,
            'itempdcdaihatsucibinongharian' => $itempdcdaihatsucibinongharian,
            'itempdcdaihatsucibinongoutsourcing' => $itempdcdaihatsucibinongoutsourcing,
            'itempdcdaihatsucibitungpkwtt' => $itempdcdaihatsucibitungpkwtt,
            'itempdcdaihatsucibitungpkwt' => $itempdcdaihatsucibitungpkwt,
            'itempdcdaihatsucibitungharian' => $itempdcdaihatsucibitungharian,
            'itempdcdaihatsucibitungoutsourcing' => $itempdcdaihatsucibitungoutsourcing,
            'itempdcdaihatsukarawangtimurpkwtt' => $itempdcdaihatsukarawangtimurpkwtt,
            'itempdcdaihatsukarawangtimurpkwt' => $itempdcdaihatsukarawangtimurpkwt,
            'itempdcdaihatsukarawangtimurharian' => $itempdcdaihatsukarawangtimurharian,
            'itempdcdaihatsukarawangtimuroutsourcing' => $itempdcdaihatsukarawangtimuroutsourcing
        ]);
    }
}
