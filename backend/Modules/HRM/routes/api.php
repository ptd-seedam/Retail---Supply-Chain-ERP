<?php

use Illuminate\Support\Facades\Route;
use Modules\HRM\Http\Controllers\EmployeeController;
use Modules\HRM\Http\Controllers\SalaryController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    // Employees
    Route::group(['middleware' => 'permission:view-hrm'], function () {
        Route::get('employees', [EmployeeController::class, 'index'])->name('employee.index');
        Route::get('employees/filter/active', [EmployeeController::class, 'getActive'])->name('employee.active');
        Route::get('employees/filter/on-leave', [EmployeeController::class, 'getOnLeave'])->name('employee.on_leave');
        Route::get('employees/department/{departmentId}', [EmployeeController::class, 'getByDepartment'])->name('employee.department');
        Route::get('employees/shift/{shiftId}', [EmployeeController::class, 'getByShift'])->name('employee.shift');
        Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employee.show');
    });
    Route::post('employees', [EmployeeController::class, 'store'])->name('employee.store')->middleware('permission:create-hrm');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employee.update')->middleware('permission:edit-hrm');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy')->middleware('permission:delete-hrm');

    // Salaries
    Route::group(['middleware' => 'permission:view-hrm'], function () {
        Route::get('salaries', [SalaryController::class, 'index'])->name('salary.index');
        Route::get('salaries/filter/pending', [SalaryController::class, 'getPending'])->name('salary.pending');
        Route::get('salaries/employee/{employeeId}', [SalaryController::class, 'getByEmployee'])->name('salary.employee');
        Route::get('salaries/{year}/{month}', [SalaryController::class, 'getByMonth'])->name('salary.month');
        Route::get('salaries/{salary}', [SalaryController::class, 'show'])->name('salary.show');
    });
    Route::post('salaries', [SalaryController::class, 'store'])->name('salary.store')->middleware('permission:create-hrm');
    Route::put('salaries/{salary}', [SalaryController::class, 'update'])->name('salary.update')->middleware('permission:edit-hrm');
    Route::delete('salaries/{salary}', [SalaryController::class, 'destroy'])->name('salary.destroy')->middleware('permission:delete-hrm');
    Route::post('salaries/{id}/approve', [SalaryController::class, 'approveSalary'])->name('salary.approve')->middleware('permission:edit-hrm');
    Route::post('salaries/{id}/mark-paid', [SalaryController::class, 'markAsPaid'])->name('salary.mark_paid')->middleware('permission:edit-hrm');

    // Reports (lazy loaded to avoid service resolution issues)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'report'])->name('index');
    //     Route::get('summary', [ReportController::class, 'summaryReport'])->name('summary');
    //     Route::get('detailed', [ReportController::class, 'detailedReport'])->name('detailed');
    //     Route::get('full', [ReportController::class, 'fullReport'])->name('full');
    // });
});
