<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
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

Route::get('/','HomeController@index');

//Artisan Cpanel
// Route::get('/config-clear', function() {
//     Artisan::call('config:clear'); 
//     return 'Configuration cache cleared!';
// });
// Route::get('/config-cache', function() {
//     Artisan::call('config:cache');
//     return 'Configuration cache cleared! <br> Configuration cached successfully!';
// });
// Route::get('/cache-clear', function() {
//     Artisan::call('cache:clear');
//     return 'Application cache cleared!';
// });
// Route::get('/view-cache', function() {
//     Artisan::call('view:cache');
//     return 'Compiled views cleared! <br> Blade templates cached successfully!';
// });
// Route::get('/view-clear', function() {
//     Artisan::call('view:clear');
//     return 'Compiled views cleared!';
// });
// Route::get('/route-cache', function() {
//     Artisan::call('route:cache');
//     return 'Route cache cleared! <br> Routes cached successfully!';
// });
// Route::get('/route-clear', function() {
//     Artisan::call('route:clear');
//     return 'Route cache cleared!';
// });
// Route::get('/storage-link', function() {
//     Artisan::call('storage:link');
//     return 'The links have been created.';
// });
//Artisan Cpanel


Route::prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth','admin'])
    ->group(function()
    {
        Route::get('/','DashboardController@index')
        ->name('dashboard');

        Route::resource('users', 'UsersController');
        Route::resource('comingsoon', 'ComingsoonController');
        Route::resource('companies', 'CompaniesController');
        Route::resource('divisions', 'DivisionsController');
        Route::resource('areas', 'AreasController');
        Route::resource('positions', 'PositionsController');
        Route::resource('working-hours', 'WorkingHoursController');

        // Employees
        Route::get('employees/export_excel', 'EmployeesController@export_excel')->name('employees.export_excel');
        Route::resource('employees', 'EmployeesController');
        
        //History Kontrak
        Route::get('history_contracts/tambahhistorykontrak/{nik_karyawan}', 'HistoryContractsController@tambahhistorykontrak')->name('history_contracts.tambahhistorykontrak');
        Route::resource('history_contracts', 'HistoryContractsController');
        //History Jabatan
        Route::get('history_positions/tambahhistoryjabatan/{nik_karyawan}', 'HistoryPositionsController@tambahhistoryjabatan')->name('history_positions.tambahhistoryjabatan');
        Route::resource('history_positions', 'HistoryPositionsController');
        //History Training Internal
        Route::get('history_training_internal/tambahhistorytraininginternal/{nik_karyawan}', 'HistoryTrainingInternalsController@tambahhistorytraininginternal')->name('history_training_internal.tambahhistorytraininginternal');
        Route::resource('history_training_internal', 'HistoryTrainingInternalsController');
        //History Training Eksternal
        Route::get('history_training_eksternal/tambahhistorytrainingeksternal/{nik_karyawan}', 'HistoryTrainingEksternalsController@tambahhistorytrainingeksternal')->name('history_training_eksternal.tambahhistorytrainingeksternal');
        Route::resource('history_training_eksternal', 'HistoryTrainingEksternalsController');
        //History Keluarga
        Route::get('history_families/tambahhistoryfamily/{nik_karyawan}', 'HistoryFamiliesController@tambahhistoryfamily')->name('history_families.tambahhistoryfamily');
        Route::resource('history_families', 'HistoryFamiliesController');
        //Inventaris
        Route::resource('inventory_laptops', 'InventoryLaptopsController');
        Route::resource('inventory_motorcycles', 'InventoryMotorcyclesController');
        Route::resource('inventory_cars', 'InventoryCarsController');
        //Karyawan Keluar
        Route::resource('employees_outs', 'EmployeesOutsController');
        //Cetak Surat
        Route::get('cetak/aktifkerja/{nik_karyawan}', 'CetaksController@aktifkerja')->name('cetak.aktifkerja');
        Route::get('cetak/pkwt/{nik_karyawan}', 'CetaksController@pkwt')->name('cetak.pkwt');
        Route::get('cetak/penilaian_karyawan', 'CetaksController@penilaian_karyawan')->name('cetak.penilaian_karyawan');
        Route::post('cetak/tampil_penilaian_karyawan', 'CetaksController@tampil_penilaian_karyawan')->name('cetak.tampil_penilaian_karyawan');
        Route::get('cetak/pkwt_kontrak', 'CetaksController@pkwt_kontrak')->name('cetak.pkwt_kontrak');
        Route::post('cetak/tampil_pkwt_kontrak', 'CetaksController@tampil_pkwt_kontrak')->name('cetak.tampil_pkwt_kontrak');
        Route::get('cetak/pkwt_harian', 'CetaksController@pkwt_harian')->name('cetak.pkwt_harian');
        Route::post('cetak/tampil_pkwt_harian', 'CetaksController@tampil_pkwt_harian')->name('cetak.tampil_pkwt_harian');
        Route::resource('cetak', 'CetaksController');
        //Reports
        Route::get('reports/karyawan_masuk', 'ReportsController@karyawan_masuk')->name('reports.karyawan_masuk');
        Route::post('reports/tampil_karyawan_masuk', 'ReportsController@tampil_karyawan_masuk')->name('reports.tampil_karyawan_masuk');
        Route::get('reports/karyawan_keluar', 'ReportsController@karyawan_keluar')->name('reports.karyawan_keluar');
        Route::post('reports/tampil_karyawan_keluar', 'ReportsController@tampil_karyawan_keluar')->name('reports.tampil_karyawan_keluar');
        Route::get('reports/karyawan_kontrak', 'ReportsController@karyawan_kontrak')->name('reports.karyawan_kontrak');
        Route::get('reports/karyawan_tetap', 'ReportsController@karyawan_tetap')->name('reports.karyawan_tetap');
        Route::get('reports/karyawan_harian', 'ReportsController@karyawan_harian')->name('reports.karyawan_harian');
        Route::get('reports/karyawan_outsourcing', 'ReportsController@karyawan_outsourcing')->name('reports.karyawan_outsourcing');
        Route::get('reports/inventaris_laptop', 'ReportsController@inventaris_laptop')->name('reports.inventaris_laptop');
        Route::get('reports/inventaris_motor', 'ReportsController@inventaris_motor')->name('reports.inventaris_motor');
        Route::get('reports/inventaris_mobil', 'ReportsController@inventaris_mobil')->name('reports.inventaris_mobil');
        Route::resource('reports', 'ReportsController');
        // Schools
        Route::resource('schools', 'SchoolsController');
        // Students
        Route::resource('students', 'StudentsController');
        // Process
        //PKWT Harian
        Route::get('process/process_pkwt_harian', 'ProcessController@process_pkwt_harian')->name('process.process_pkwt_harian');
        Route::post('process/tampil_pkwt_harian', 'ProcessController@tampil_pkwt_harian')->name('process.tampil_pkwt_harian');
        Route::post('process/prosess_pkwt_harian/{akhir_kontrak}', 'ProcessController@prosess_pkwt_harian')->name('process.prosess_pkwt_harian');
        Route::post('process/perpanjang_pkwt_harian', 'ProcessController@perpanjang_pkwt_harian')->name('process.perpanjang_pkwt_harian');
        Route::get('process/process_magang', 'ProcessController@process_magang')->name('process.process_magang');
        Route::post('process/hasil_cetak_pkwt_magang', 'ProcessController@hasil_cetak_pkwt_magang')->name('process.hasil_cetak_pkwt_magang');
        //PKWT Kontrak
        Route::get('process/process_pkwt_kontrak', 'ProcessController@process_pkwt_kontrak')->name('process.process_pkwt_kontrak');
        Route::post('process/tampil_pkwt_kontrak', 'ProcessController@tampil_pkwt_kontrak')->name('process.tampil_pkwt_kontrak');
        Route::post('process/prosess_pkwt_kontrak/{akhir_kontrak}', 'ProcessController@prosess_pkwt_kontrak')->name('process.prosess_pkwt_kontrak');
        Route::post('process/perpanjang_pkwt_kontrak', 'ProcessController@perpanjang_pkwt_kontrak')->name('process.perpanjang_pkwt_kontrak');
        Route::resource('process', 'ProcessController');
        // Overtimes
        Route::resource('overtimes', 'OvertimesController');
        
        
        
});

Auth::routes(['verify' => true]);
