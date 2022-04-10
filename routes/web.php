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
Route::get('/signs','SignsController@index')
        ->name('signs');

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
        //Dashboard
        Route::get('dashboard/form_slip_lembur_karyawan', 'DashboardController@form_slip_lembur_karyawan')->name('dashboard.form_slip_lembur_karyawan');
        Route::post('dashboard/cetak_slip_lembur_karyawan', 'DashboardController@cetak_slip_lembur_karyawan')->name('dashboard.cetak_slip_lembur_karyawan');
        Route::get('dashboard/form_absensi_karyawan', 'DashboardController@form_absensi_karyawan')->name('dashboard.form_absensi_karyawan');
        Route::post('dashboard/cetak_absensi_karyawan', 'DashboardController@cetak_absensi_karyawan')->name('dashboard.cetak_absensi_karyawan');
        Route::get('dashboard/form_ganti_foto_karyawan', 'DashboardController@form_ganti_foto_karyawan')->name('dashboard.form_ganti_foto_karyawan');
        Route::post('dashboard/hasil_ganti_foto_karyawan', 'DashboardController@hasil_ganti_foto_karyawan')->name('dashboard.hasil_ganti_foto_karyawan');
        Route::get('/','DashboardController@index')
        ->name('dashboard');

        //Topbar
        Route::get('privacypolicy','PrivacypolicyController@index')
        ->name('privacypolicy');

        //Master
        Route::resource('users', 'UsersController');
        Route::resource('comingsoon', 'ComingsoonController');
        Route::resource('companies', 'CompaniesController');
        Route::resource('divisions', 'DivisionsController');
        Route::resource('areas', 'AreasController');
        Route::resource('positions', 'PositionsController');
        Route::resource('working-hours', 'WorkingHoursController');
        Route::resource('temporarys', 'TemporarysController');

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
        Route::post('history_training_internal/storemultipletraininginternal', 'HistoryTrainingInternalsController@storemultipletraininginternal')->name('history_training_internal.storemultipletraininginternal');
        Route::post('history_training_internal/tampilmultipletraininginternal', 'HistoryTrainingInternalsController@tampilmultipletraininginternal')->name('history_training_internal.tampilmultipletraininginternal');
        Route::resource('history_training_internal', 'HistoryTrainingInternalsController');
        //History Training Eksternal
        Route::get('history_training_eksternal/tambahhistorytrainingeksternal/{nik_karyawan}', 'HistoryTrainingEksternalsController@tambahhistorytrainingeksternal')->name('history_training_eksternal.tambahhistorytrainingeksternal');
        Route::post('history_training_eksternal/storemultipletrainingeksternal', 'HistoryTrainingEksternalsController@storemultipletrainingeksternal')->name('history_training_eksternal.storemultipletrainingeksternal');
        Route::post('history_training_eksternal/tampilmultipletrainingeksternal', 'HistoryTrainingEksternalsController@tampilmultipletrainingeksternal')->name('history_training_eksternal.tampilmultipletrainingeksternal');
        Route::resource('history_training_eksternal', 'HistoryTrainingEksternalsController');
        //History Keluarga
        Route::get('history_families/tambahhistoryfamily/{nik_karyawan}', 'HistoryFamiliesController@tambahhistoryfamily')->name('history_families.tambahhistoryfamily');
        Route::resource('history_families', 'HistoryFamiliesController');
        //Inventaris
        Route::resource('inventory_laptops', 'InventoryLaptopsController');
        Route::resource('inventory_motorcycles', 'InventoryMotorcyclesController');
        Route::resource('inventory_cars', 'InventoryCarsController');
        //Karyawan Keluar
        Route::get('employees_outs/export_excel', 'EmployeesOutsController@export_excel')->name('employees_outs.export_excel');
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
        Route::get('reports/absensi_karyawan', 'ReportsController@absensi_karyawan')->name('reports.absensi_karyawan');
        Route::get('reports/absensi_department_pdc', 'ReportsController@absensi_department_pdc')->name('reports.absensi_department_pdc');
        Route::get('reports/absensi_department_produksi', 'ReportsController@absensi_department_produksi')->name('reports.absensi_department_produksi');
        Route::get('reports/absensi_department_ppc', 'ReportsController@absensi_department_ppc')->name('reports.absensi_department_ppc');
        Route::get('reports/absensi_department_accicit', 'ReportsController@absensi_department_accicit')->name('reports.absensi_department_accicit');
        Route::get('reports/absensi_department_hrdgadc', 'ReportsController@absensi_department_hrdgadc')->name('reports.absensi_department_hrdgadc');
        Route::get('reports/absensi_department_marketing', 'ReportsController@absensi_department_marketing')->name('reports.absensi_department_marketing');
        Route::get('reports/absensi_department_purchasing', 'ReportsController@absensi_department_purchasing')->name('reports.absensi_department_purchasing');
        Route::get('reports/absensi_department_engineering', 'ReportsController@absensi_department_engineering')->name('reports.absensi_department_engineering');
        Route::get('reports/absensi_department_quality', 'ReportsController@absensi_department_quality')->name('reports.absensi_department_quality');
        Route::post('reports/tampil_absensi_karyawan', 'ReportsController@tampil_absensi_karyawan')->name('reports.tampil_absensi_karyawan');
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
        Route::get('overtimes/lihat_overtime', 'OvertimesController@lihat_overtime')->name('overtimes.lihat_overtime');
        Route::post('overtimes/tampil_overtime', 'OvertimesController@tampil_overtime')->name('overtimes.tampil_overtime');
        Route::get('overtimes/edit_overtime', 'OvertimesController@edit_overtime')->name('overtimes.edit_overtime');
        Route::post('overtimes/tampiledit_overtime', 'OvertimesController@tampiledit_overtime')->name('overtimes.tampiledit_overtime');
        Route::get('overtimes/form_hapus_overtime', 'OvertimesController@form_hapus_overtime')->name('overtimes.form_hapus_overtime');
        Route::post('overtimes/tampilhapus_overtime', 'OvertimesController@tampilhapus_overtime')->name('overtimes.tampilhapus_overtime');
        Route::get('overtimes/form_approve_overtime', 'OvertimesController@form_approve_overtime')->name('overtimes.form_approve_overtime');
        Route::post('overtimes/tampil_approve_overtime', 'OvertimesController@tampil_approve_overtime')->name('overtimes.tampil_approve_overtime');
        Route::post('overtimes/proses_approve_overtime', 'OvertimesController@proses_approve_overtime')->name('overtimes.proses_approve_overtime');
        Route::get('overtimes/form_cancel_approve_overtime', 'OvertimesController@form_cancel_approve_overtime')->name('overtimes.form_cancel_approve_overtime');
        Route::post('overtimes/tampil_cancel_approve_overtime', 'OvertimesController@tampil_cancel_approve_overtime')->name('overtimes.tampil_cancel_approve_overtime');
        Route::post('overtimes/proses_cancel_approve_overtime', 'OvertimesController@proses_cancel_approve_overtime')->name('overtimes.proses_cancel_approve_overtime');
        Route::get('overtimes/form_cetak_slip_overtime', 'OvertimesController@form_cetak_slip_overtime')->name('overtimes.form_cetak_slip_overtime');
        Route::get('overtimes/form_cetak_slip_karyawan_overtime', 'OvertimesController@form_cetak_slip_karyawan_overtime')->name('overtimes.form_cetak_slip_karyawan_overtime');
        Route::post('overtimes/hasil_slipkaryawan_overtime', 'OvertimesController@hasil_slipkaryawan_overtime')->name('overtimes.hasil_slipkaryawan_overtime');
        Route::get('overtimes/form_cetak_slip_department_overtime', 'OvertimesController@form_cetak_slip_department_overtime')->name('overtimes.form_cetak_slip_department_overtime');
        Route::post('overtimes/hasil_slipdepartment_overtime', 'OvertimesController@hasil_slipdepartment_overtime')->name('overtimes.hasil_slipdepartment_overtime');
        Route::get('overtimes/form_cetak_rekap_overtime', 'OvertimesController@form_cetak_rekap_overtime')->name('overtimes.form_cetak_rekap_overtime');
        Route::post('overtimes/form_lihat_rekap_overtime', 'OvertimesController@form_lihat_rekap_overtime')->name('overtimes.form_lihat_rekap_overtime');
        Route::get('overtimes/export_excel_rekap_overtime', 'OvertimesController@export_excel_rekap_overtime')->name('overtimes.export_excel_rekap_overtime');
        Route::post('overtimes/export_pdf_rekap_overtime', 'OvertimesController@export_pdf_rekap_overtime')->name('overtimes.export_pdf_rekap_overtime');
        Route::resource('overtimes', 'OvertimesController');
        //Absensi
        Route::get('absensi/lihat_absensi', 'AttendancesController@lihat_absensi')->name('absensi.lihat_absensi');
        Route::post('absensi/tampil_absensi', 'AttendancesController@tampil_absensi')->name('absensi.tampil_absensi');
        Route::get('absensi/form_edit', 'AttendancesController@form_edit')->name('absensi.form_edit');
        Route::post('absensi/tampil_edit', 'AttendancesController@tampil_edit')->name('absensi.tampil_edit');
        Route::get('absensi/form_hapus', 'AttendancesController@form_hapus')->name('absensi.form_hapus');
        Route::post('absensi/tampil_hapus', 'AttendancesController@tampil_hapus')->name('absensi.tampil_hapus');
        Route::resource('absensi', 'AttendancesController');
        
        
});

Auth::routes(['verify' => true]);
