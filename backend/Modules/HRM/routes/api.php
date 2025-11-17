<?php

use Illuminate\Support\Facades\Route;
use Modules\HRM\Http\Controllers\EmployeeController;
use Modules\HRM\Http\Controllers\SalaryController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::apiResource('employees', EmployeeController::class)->names('employee');
    Route::get('employees/department/{departmentId}', [EmployeeController::class, 'getByDepartment'])->name('employee.department');
    Route::get('employees/shift/{shiftId}', [EmployeeController::class, 'getByShift'])->name('employee.shift');
    Route::get('employees-active', [EmployeeController::class, 'getActive'])->name('employee.active');
    Route::get('employees-on-leave', [EmployeeController::class, 'getOnLeave'])->name('employee.on_leave');

    Route::apiResource('salaries', SalaryController::class)->names('salary');
    Route::post('salaries/{id}/approve', [SalaryController::class, 'approveSalary'])->name('salary.approve');
    Route::post('salaries/{id}/mark-paid', [SalaryController::class, 'markAsPaid'])->name('salary.mark_paid');
    Route::get('salaries/employee/{employeeId}', [SalaryController::class, 'getByEmployee'])->name('salary.employee');
    Route::get('salaries-pending', [SalaryController::class, 'getPending'])->name('salary.pending');
    Route::get('salaries/{year}/{month}', [SalaryController::class, 'getByMonth'])->name('salary.month');
});
