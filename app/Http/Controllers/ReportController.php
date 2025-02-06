<?php

namespace App\Http\Controllers;
use App\Services\ReportService;
use App\Models\Spent;
use App\Models\Income;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getExpensesMonth(string $month, Request $request)
    {
        return $this->reportService->getAllOf(Spent::class, $request, 'month', $month);
    }
    

    public function getExpensesYear(string $year, Request $request)
    {
        return $this->reportService->getAllOf(Spent::class,$request,'year', $year);
    }

    public function getExpensesCategory(string $category, Request $request)
    {
        return $this->reportService->getAllOf(Spent::class,$request,'category', $category);
    }

    public function getIncomesMonth(string $month, Request $request)
    {
        return $this->reportService->getAllOf(Income::class,$request,'month', $month);
    }

    public function getIncomesYear(string $year, Request $request)
    {
        return $this->reportService->getAllOf(Income::class,$request,'year', $year);
    }

    public function getIncomesCategory(string $year, Request $request)
    {
        return $this->reportService->getAllOf(Income::class,$request,'category', $year);
    }

    public function getAnnualComparison(string $year, Request $request){
        return $this->reportService->getAnnualComparison($year, $request);
    }

    public function getAnnualComparisonReport(string $year, Request $request){
        return $this->reportService->getAnnualComparisonReport($year, $request);
    }

    public function getMonthlyComparison(string $month, Request $request){
        return $this->reportService->getMonthlyComparison($month, $request);
    }

    public function getMonthlyComparisonReport(string $month, Request $request){
        return $this->reportService->getMonthlyComparisonReport($month, $request);
    }

}
