<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Services\FinancialService;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;

class IncomeController extends Controller
{

    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    //GET
        // ALL
        public function index(ProjectRequest $request)
        {
            return $this->financialService->getFinancialRecord(Income::class, $request);
        }

        // ALL FROM USER
        public function show(Request $request){
            return $this->financialService->getFinancialRecord(Income::class, $request, auth()->id());
        }

        // ONE BY ID
        public function getIncomeById(Request $request){
            try{
                return $this->financialService->getOneFinancialRecord(Income::class, $request);
            } catch (\Exception $e) {
                return parent::LogError($e->getMessage());
            }
        }
    
    // CREATE
    public function store(Request $request)
    {
        try{
            return $this->financialService->createFinancialRecord(Income::class, $request);
        } catch (\Exception $e) {
            return parent::LogError($e->getMessage());
        }
    }
    
    
    // UPDATE
        public function update(Request $request){
            try {
                return $this->financialService->updateFinancialRecord(Income::class, $request);
            } catch (\Exception $e) {
                return parent::LogError($e->getMessage());
            }
        }
    
    // DESTROY
        public function destroy(Request $request){
            try{
                return $this->financialService->deleteFinancialRecord(Income::class, $request);
            } catch (\Exception $e){
                return parent::LogError($e->getMessage());
            }
        }
    
}
