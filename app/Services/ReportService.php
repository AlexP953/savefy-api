<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spent;
use App\Models\Income;
class ReportService
{

  // ALL
  public function getAllOf(string $modelClass, Request $request, string $type, string $value){
    $query = $modelClass::with([
        'category:id,name',
        'month:id,month,year,user_id',
        'month.user:id,name,surname,email'
    ])
    ->select('id', 'amount', 'description', 'month_id', 'category_id')

    ->when($type === 'year', function ($query) use ($value) {
        $query->whereHas('month', function ($q) use ($value) {
            $q->where("year", $value);
        });
    }, function ($query) use ($type, $value) {
        $query->where("{$type}_id", $value);
    })

    ->when(!$request->user()->hasRole('admin'), function ($query) use ($request) {
        $query->whereHas('month', function ($query) use ($request) {
            $query->where("user_id", $request->user()->id);
        });
    });
    $records = $query->get();
    $totalAmount = $records->sum('amount');

    return $this->getClearRecords($records, $modelClass, $totalAmount);
}

  // CLEAR ONLY
  public function getClearRecords( $records, $modelClass, $totalAmount ){
    return $records->map(fn ($record) => [
      'id' => $record->id,
      'user_id' => $record->month->user_id,
      'type' => trim(strrchr($modelClass, "\\"), "\\"), 
      'amount' => $record->amount,
      'description' => $record->description,
      'category_id' => $record->category->id,
      'category_name' => $record->category->name,
      'month_id' => $record->month->id,
      'month' => $record->month->month,
      'year' => $record->month->year,
      'percentage' => $totalAmount > 0 ? round(($record->amount / $totalAmount) * 100, 2) : 0, 
  ]);
}

//ANNUAL
public function getAnnualComparison(string $year, Request $request)
{
    $records = $this->getAnnualRecords($year, $request);
    $totalIncome = $records->where('type', 'Income')->sum('amount');
    $totalExpenses = $records->where('type', 'Spent')->sum('amount');

    return response()->json([
        'records' => $records, 
        'total_ingresos' => $totalIncome,
        'total_gastos' => $totalExpenses,
        'balance_neto' => round(($totalIncome - $totalExpenses),2)
    ]);
}

public function getAnnualRecords(string $year, Request $request)
{
    return $this->getAllOf(Spent::class, $request, 'year', $year)
        ->merge($this->getAllOf(Income::class, $request, 'year', $year)); 
}

public function getAnnualComparisonReport(string $year, Request $request){
  return $this->getReport($this->getAnnualComparison($year, $request));
}


//MONTHLY

public function getMonthlyComparison(string $month, Request $request)
{
    $records = $this->getMonthlyRecords($month, $request);
    $totalIncome = $records->where('type', 'Income')->sum('amount');
    $totalExpenses = $records->where('type', 'Spent')->sum('amount');

    return response()->json([
        'records' => $records, 
        'total_ingresos' => $totalIncome,
        'total_gastos' => $totalExpenses,
        'balance_neto' => round(($totalIncome - $totalExpenses),2)
    ]);
}

public function getMonthlyRecords(string $month, $request){
  return $this->getAllOf(Spent::class, $request, 'month', $month) 
  ->merge($this->getAllOf(Income::class, $request, 'month', $month)); 
}

public function getMonthlyComparisonReport(string $month, $request){
  return $this->getReport($this->getMonthlyComparison($month, $request),true);
}

// OTHER
public function getReport($dataReport, $month = null){
  $name = 'basicReport';
  $data = json_decode($dataReport->getContent(), true);
  $records = $data['records'];
  $csvFile = fopen('php://memory', 'w');
  $record = [];
  fputcsv($csvFile, [
      'ID', 'User ID', 'Type', 'Amount', 'Description', 'Category ID', 
      'Category Name', 'Month ID', 'Month', 'Year', 'Percentage'
  ]);

  foreach ($records as $record) {
      fputcsv($csvFile, [
          $record['id'], $record['user_id'], $record['type'], $record['amount'],
          $record['description'], $record['category_id'], $record['category_name'],
          $record['month_id'], $record['month'], $record['year'], $record['percentage']
      ]);
  }

  if($record){
  $name = $record['year'];
  }

  if($month && !empty($record)){
    $name = $record['month'].$record['year'];
}
  
  rewind($csvFile);
    return response()->stream(
      function () use ($csvFile) {
          fpassthru($csvFile); 
      },200,
      [ "Content-Type" => "text/csv",
        'Content-Disposition' => "attachment; filename={$name}Report.csv"]
  );
}
};
