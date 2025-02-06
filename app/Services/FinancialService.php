<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Month;
use Illuminate\Support\Facades\Auth;

class FinancialService
{

  //CREATE

  public function createFinancialRecord(string $modelClass, Request $request)
  {     
      $userId = auth()->id();
  
      $validated = $request->validate([
          'amount' => 'required|numeric',
          'description' => 'required|string|max:50',
          'category_id' => 'required|integer',
          'month_id' => 'required|integer',
      ]);
  
      $month = Month::where('id', $validated['month_id'])
          ->where('user_id', $userId)
          ->first();
  
      if (!$month) {
          $month = Month::create([
              'user_id' => $userId,
              'month' => date('F', mktime(0, 0, 0, $validated['month_id'], 10)),
              'year' => now()->year,
          ]);
      }
  
      $isCurrentUser = $month->user_id === $userId;
  
      if (!$isCurrentUser && !$request->user()->hasRole('admin')) {
          throw new \Exception('Forbidden for current user: ' . $userId, 403);
      }
  
      $record = $modelClass::create([
          'amount' => $validated['amount'],
          'description' => $validated['description'],
          'category_id' => $validated['category_id'],
          'month_id' => $month->id,  
      ]);
  
      return response()->json([
          'message' => 'Creado correctamente.',
          'record' => $record,
      ]);
  }
  

    //UPDATE

    public function updateFinancialRecord(string $modelClass, Request $request)
    {
      $validated = $request->validate([
          'id' => 'required|integer',
          'amount' => 'required|numeric',
          'description' => 'required|string|max:50',
          'category_id' => 'required|integer',
          'month_id' => 'required|integer',
      ]);
  
      $record = $modelClass::with('month.user')->findOrFail($validated['id']);
  
      if (!$this->isAllowedUser($record, $request)) {
        throw new \Exception('Forbidden for current user', 403);
    }
  
      $record->fill([
          'amount' => $validated['amount'],
          'description' => $validated['description'],
      ]);
  
      $record->save();
      return response()->json([
          'message' => 'Actualizado correctamente.',
          'record' => $record,
        ]);
      }
    
      // DESTROY
    public function deleteFinancialRecord(string $modelClass, Request $request){
        $record = $modelClass::findOrFail($request->id);
        
        if (!$this->isAllowedUser($record, $request)) {
          throw new \Exception('Forbidden for current user', 403);
      }

        $record->delete();

        return response()->json([
            'message' => 'Registro eliminado correctamente',
        ], 200);
    }

    public function isAllowedUser($record, Request $request): bool{
      return $record->month->user_id === $request->user()->id || $request->user()->hasRole('admin');
    }

    // GET ALL
    public function getFinancialRecord(string $modelClass, Request $request, $userId = null){
      $query = $modelClass::with([
        'category:id,name', 
        'month:id,month,year,user_id', 
        'month.user:id,name,surname,email'
      ])
      ->select('id', 'amount', 'description', 'month_id', 'category_id');

      if ($userId) {
          $query->whereHas('month', function ($param) use ($userId) {
              $param->where('user_id', $userId);
          });
      }

      return $query->get()->map(fn ($record) => [
          'id' => $record->id,
          'type' => trim(strrchr($modelClass, "\\"), "\\"),
          'amount' => $record->amount,
          'description' => $record->description,
          'category_id' => $record->category->id,
          'category_name' => $record->category->name,
          'month_id' => $record->month->id,
          'month' => $record->month->month,
          'year' => $record->month->year,
          'user_id' => $record->month->user_id,
          'user_name' => $record->month->user->name,
          'surname' => $record->month->user->surname,
          'email' => $record->month->user->email,
      ]);
    }

    // GET ONE BY ID
    public function getOneFinancialRecord(string $modelClass, Request $request){
      $record = $modelClass::with([
        'category:id,name', 
        'month:id,month,year,user_id', 
        'month.user:id,name,surname,email'
      ])
      ->select('id', 'amount', 'description', 'month_id', 'category_id')
      ->where('id', $request->id)
      ->firstOrFail();

      if (!$this->isAllowedUser($record, $request)) {
        throw new \Exception('Forbidden for current user', 403);
      }
      
      return [
            'id' => $record->id,
            'type' => trim(strrchr($modelClass, "\\"), "\\"),
            'amount' => $record->amount,
            'description' => $record->description,
            'category_name' => $record->category->name,
            'month' => $record->month->month,
            'year' => $record->month->year,
            'user_id' => $record->month->user_id,
            'user_name' => $record->month->user->name,
            'surname' => $record->month->user->surname,
            'email' => $record->month->user->email,
        ];
    }
}
