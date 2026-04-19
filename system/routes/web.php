<?php

use Illuminate\Support\Facades\Route;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

if (App::environment('production')) {
    URL::forceScheme('https');
}
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

Route::get('/', function () {
    return redirect("login");
})->name('home');

Route::get('/logout', function () {
    return redirect("login");
});

Route::get('/home', function () {
    return redirect("admin");
});

Route::get('about-us', function () {
    return view('web.about-us');
});

Route::get('contact-us', function () {
    return view('web.contact-us');
});

Route::get('admin', function () {
    return redirect("admin/dashboard");
});

Route::get('/cron', [App\Http\Controllers\Admin\CronController::class, 'index'])->name('public.cron');

Auth::routes();

Route::get('/restaurants/pdf/view/{restaurant_id}', function ($restauratn_id) {
    $row = Restaurant::find($restauratn_id);
    return view("admin.restaurants.viewPdf", compact("row"));
})->name('download.pdf');

Route::get('/restaurants/pdf/download/{restaurant_id}', function ($restauratn_id) {
    $row = Restaurant::find($restauratn_id);
    return view("admin.restaurants.viewPdf", compact("row"));
    // return Storage::disk("public")->download($row->pdf_menu,Str::slug($row->name,"-")."_menu.pdf");
})->name('download.pdf');

// Public QR Code Verification Route
Route::get('/verify-labour-card/{id}', [App\Http\Controllers\Admin\LabourController::class, 'qrVerifyPage'])->name('public.labour.verify');

Route::post('/update-test-report/{id}', [App\Http\Controllers\Admin\LabourController::class, 'updateTestReport'])->name('update-test-report');


// Test route for card debugging (remove in production)
Route::get('/test-card/{id}', function($id) {
    $labour = \App\Models\Labour::withoutGlobalScopes()->with(['district', 'work', 'mineral', 'bank'])->find($id);
    if (!$labour) {
        return 'Labour not found';
    }
    return view('admin.layouts.partials.card.labour-card', ['row' => $labour]);
})->name('test.card');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::post('/loginAs', [App\Http\Controllers\Admin\UserController::class, 'loginAs'])->name('loginAs');
    Route::post('/switchOffice', [App\Http\Controllers\Admin\UserController::class, 'switchOffice'])->name('switchOffice');

    Route::post('/changeStatus', [App\Http\Controllers\Admin\AdminController::class, 'changeStatus'])->name('change.app.status');
    Route::get('/fyYear', [App\Http\Controllers\Admin\AdminController::class, 'ChangeFyYear'])->name('change.fyYear');
    Route::get('/labours/print/bulk/cards', [App\Http\Controllers\Admin\LabourController::class, 'printBulkCards'])->name('labours.printBulkCards');

    Route::resource(
        'labours',
        App\Http\Controllers\Admin\LabourController::class
    );

    Route::prefix('labours/{labour}')->name('labours.')->group(function () {
        Route::get('/pulmonary-annual-reports', [App\Http\Controllers\Admin\PulmonaryAnnualReportController::class, 'index'])
            ->name('pulmonary-annual-reports.list');
        Route::get('/pulmonary-annual-reports/create', [App\Http\Controllers\Admin\PulmonaryAnnualReportController::class, 'create'])
            ->name('pulmonary-annual-reports.create');
        Route::post('/pulmonary-annual-reports', [App\Http\Controllers\Admin\PulmonaryAnnualReportController::class, 'store'])
            ->name('pulmonary-annual-reports.store');
    });

    Route::name('labours.')->group(function () {
        Route::get('/pulmonary-annual-reports/{pulmonary_annual_report}/edit', [App\Http\Controllers\Admin\PulmonaryAnnualReportController::class, 'edit'])
            ->name('pulmonary-annual-reports.edit');
        Route::put('/pulmonary-annual-reports/{pulmonary_annual_report}', [App\Http\Controllers\Admin\PulmonaryAnnualReportController::class, 'update'])
            ->name('pulmonary-annual-reports.update');
        Route::delete('/pulmonary-annual-reports/{pulmonary_annual_report}', [App\Http\Controllers\Admin\PulmonaryAnnualReportController::class, 'destroy'])
            ->name('pulmonary-annual-reports.destroy');
    });

    Route::get('/view-labour-history/{labour}', [App\Http\Controllers\Admin\LabourController::class, 'labourHistory'])->name('view_labour_history');
    Route::post('/export-labour', [App\Http\Controllers\Admin\LabourController::class, 'export'])->name('exportLabour');
    Route::post('/getMineralTitle', [App\Http\Controllers\Admin\LabourLeaseController::class, 'getMineralTitle'])->name('getMineralTitle');
    Route::post('/getMiningArea', [App\Http\Controllers\Admin\LabourLeaseController::class, 'getMiningArea'])->name('getMiningArea');
    Route::post('/updateLabourMineralTitle/{labour}', [App\Http\Controllers\Admin\LabourLeaseController::class, 'updateLabourLeaseDetail'])->name('updateLabourMineralTitle');
    Route::post('/updateLabourCNIC/{labour}', [App\Http\Controllers\Admin\LabourController::class, 'updateCNIC'])->name('updateLabourCNIC');
    Route::get('/labours/qr-verify/{id}', [App\Http\Controllers\Admin\LabourController::class, 'qrVerify'])->name('labours.qr-verify');
    Route::get('/labours/qr-verify-page/{id}', [App\Http\Controllers\Admin\LabourController::class, 'qrVerifyPage'])->name('labours.qr-verify-page');
    Route::get('/labours/print/{id}', [App\Http\Controllers\Admin\LabourController::class, 'printCard'])->name('labours.print');

    Route::resource(
        'mineral-titles',
        App\Http\Controllers\Admin\MineralTitleController::class
    );

    Route::resource(
        'complaints',
        App\Http\Controllers\Admin\ComplaintController::class
    );

    Route::post(
        'labours/action/{id}',
        [App\Http\Controllers\Admin\LabourController::class, "updateStatus"]
    )->name("labour.action");

    Route::resource(
        'children',
        App\Http\Controllers\Admin\ChildrenController::class
    );

    Route::prefix('scholarships')->name('scholarships.')->group(function () {
        Route::resource('general', App\Http\Controllers\Admin\Scholarship\GeneralScholarshipController::class);

        Route::post(
            'export/{scheme}',
            [App\Http\Controllers\Admin\AdminController::class, "exportScholarship"]
        )->name("export");

        Route::post(
            'action/{id}',
            // function(){
            //     return redirect()->back()->with("warning","Not Allowed!");
            // }
            [App\Http\Controllers\Admin\Scholarship\GeneralScholarshipController::class, "updateAction"]
        )->name("action");

        Route::resource(
            'top-position',
            App\Http\Controllers\Admin\Scholarship\TopPositionScholarshipController::class
        );

        Route::resource(
            'special-education',
            App\Http\Controllers\Admin\Scholarship\SpecialEducationScholarshipController::class
        );

        Route::resource(
            'quality-education',
            App\Http\Controllers\Admin\Scholarship\QEScholarshipController::class
        );
    });

    Route::resource(
        'skill-development',
        App\Http\Controllers\Admin\Scholarship\DiplomaController::class
    );

    Route::post(
        'skill-development/export/{scheme}',
        [App\Http\Controllers\Admin\AdminController::class, "exportDiploma"]
    )->name("diploma.export");

    Route::post(
        'skill-development/{id}',
        function(){
                return redirect()->back()->with("warning","Not Allowed!");
            }
        // [App\Http\Controllers\Admin\Scholarship\DiplomaController::class, "updateAction"]
    )->name("skill-development.action");

    Route::name("grants.")->prefix('grants')->group(function () {
        Route::resource(
            'disabled-mine-labour',
            App\Http\Controllers\Admin\Grant\DisabledLabourController::class
        );

        Route::post('/export-disabled-labour', [App\Http\Controllers\Admin\Grant\DisabledLabourController::class, 'export'])->name('export-disabled-labour');

        Route::post('/changeDisableStatus/{id}',
        // function(){
        //         return redirect()->back()->with("warning","Not Allowed!");
        //     }
        [App\Http\Controllers\Admin\AdminController::class, 'updateDisableAction']
        )->name('disable.status');

        Route::resource(
            'pulmonary-mine-labour',
            App\Http\Controllers\Admin\Grant\PulmonaryLabourController::class
        );

        Route::post('/export-pulmonary-labour', [App\Http\Controllers\Admin\Grant\PulmonaryLabourController::class, 'export'])->name('export-pulmonary-labour');

        Route::post('/changePulmonaryStatus/{id}',
        // function(){
        //         return redirect()->back()->with("warning","Not Allowed!");
        //     }
        [App\Http\Controllers\Admin\AdminController::class, 'updatePulmonaryAction']
        )->name('pulmonary.status');

        //Marriage Grant
        Route::resource(
            'marriage-mine-labour',
            App\Http\Controllers\Admin\Grant\MarriageLabourController::class
        );

        Route::post('/export-marriage-labour', [App\Http\Controllers\Admin\Grant\MarriageLabourController::class, 'export'])->name('export-marriage-labour');

        Route::post('/changeMarriageStatus/{id}',
        // function(){
        //         return redirect()->back()->with("warning","Not Allowed!");
        //     }
        [App\Http\Controllers\Admin\AdminController::class, 'updateMarriageAction']
        )->name('marriage.status');


        Route::resource(
            'deceased-mine-labour',
            App\Http\Controllers\Admin\Grant\DeceasedLabourController::class
        );

        Route::post('/export-deceased-labour', [App\Http\Controllers\Admin\Grant\DeceasedLabourController::class, 'export'])->name('export-deceased-labour');

        Route::post('/changeDeceasedStatus/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateDeceasedAction'])->name('deceased.status');
    });

    Route::resource(
        'users',
        App\Http\Controllers\Admin\UserController::class
    );

    Route::resource(
        'profile',
        App\Http\Controllers\Admin\ProfileController::class
    );

    Route::resource(
        'roles',
        App\Http\Controllers\Admin\RoleController::class
    );

    Route::resource(
        'schemes',
        App\Http\Controllers\Admin\SchemeController::class
    );

    Route::resource(
        'object-heads',
        App\Http\Controllers\Admin\ObjectHeadController::class
    );

    Route::resource(
        'compilations',
        App\Http\Controllers\Admin\CompilationController::class
    );

    Route::put(
        '/compilations/status/{id}',
        [App\Http\Controllers\Admin\CompilationController::class, 'compiliationStatus']
    )
        ->name('compilations.status');

    Route::get(
        '/compilations/print/{id}',
        [App\Http\Controllers\Admin\CompilationController::class, 'print']
    )
        ->name('compilations.print');

    Route::resource(
        'reconciliations',
        App\Http\Controllers\Admin\ReconciliationController::class
    );

    Route::resource(
        'budgets',
        App\Http\Controllers\Admin\BudgetController::class
    );

    Route::resource(
        'accounts',
        App\Http\Controllers\Admin\AccountController::class
    );

    Route::resource(
        'districts',
        App\Http\Controllers\Admin\DistrictController::class
    );

    Route::resource(
        'offices',
        App\Http\Controllers\Admin\OfficeController::class
    );

    Route::resource(
        'posts',
        App\Http\Controllers\Admin\PostController::class
    );

    Route::resource(
        'staffs',
        App\Http\Controllers\Admin\StaffController::class
    );

    Route::resource(
        'organizations',
        App\Http\Controllers\Admin\OrganizationController::class
    );

    Route::put('/organization/function-head/{id}',
    [App\Http\Controllers\Admin\OrganizationController::class, 'addFunctionHead'])->name('organizations.addFunctionHead');

    Route::delete('/organization/function-head/{org_id}/{id}',
    [App\Http\Controllers\Admin\OrganizationController::class, 'removeFunctionHead'])->name('organizations.removeFunctionHead');

    Route::resource(
        'permissions',
        App\Http\Controllers\Admin\PermissionController::class
    );

    Route::resource(
        'modules',
        App\Http\Controllers\Admin\ModuleController::class
    );

    Route::resource(
        'setting',
        App\Http\Controllers\Admin\SettingController::class
    );

    Route::post('/role-permission/{role_id}', [App\Http\Controllers\Admin\RoleController::class, 'permission'])->name('role.permission');

    Route::get('/user-permission/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'permission'])->name('user.permission');

    Route::post('/user-permission/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'savePermission'])->name('save.user.permission');

    Route::post('/user-roles/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'saveRoles'])->name('save.user.roles');
});
