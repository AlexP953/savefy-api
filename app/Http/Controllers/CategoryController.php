<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Log;


class CategoryController extends Controller
{
// GET
    
    // All
    public function index(Request $request){
        return Category::all();
    }

    // CATEGORY BY USER
    public function show(Request $request){
        return Category::where('user_id', $request->user()->id)
        ->orWhereNull('user_id')
        ->get();
    }

    public function getCategoryById(Request $request){
        try {
            $category = Category::findOrFail($request->id);
            return response()->json($category);        
        } catch (\Throwable $e) {
            return parent::LogError('Category not found with the ID: ' . $request->id, 404);
        }
    }

    // ALL SPENTS
    public function getOneCategorySpents(Request $request)
    {
        try {
            $category = Category::where('name', $request->category)
                ->where(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->orWhereNull('user_id');
                })
                ->first();
    
            if (!$category) {
                throw new \Exception('Category not found');
            }

            $spents = $category->spents()
                ->with('month') 
                ->get()
                ->map(function ($spent) {
                    return [
                        'id' => $spent->id,
                        'amount' => $spent->amount,
                        'description' => $spent->description,
                        'category_name' => $spent->category->name,
                        'month_name' => $spent->month->month,
                        'year' => $spent->month->year,
                    ];
                });
    
            return response()->json($spents);
    
        } catch (\Exception $e) {
            return parent::LogError($e->getMessage());
        }
    }
    
    // CREATE
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name'=> 'required|string|max:50',
                'user_id' => 'required|integer'
            ]);

            $existingCategory = Category::where('name', $validated['name'])
            ->where('user_id', $validated['user_id'])
            ->first();

            if ($existingCategory) {
                throw new \Exception('Category exists');
            }

            if($validated['user_id'] !==  $request->user()->id && !$request->user()->hasRole('admin')){
                throw new \Exception('Forbidden');
            }
             
            $newCategory = Category::create([
                'name'=>$validated['name'],
                'user_id'=>$validated['user_id']
            ]);

            return response()->json([
                'message' => 'Categoria creada correctamente',
                'category' => $newCategory],201);
        } catch (\Exception $e) {
            return parent::LogError($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'user_id' => 'required|integer'
            ]);
    
            $category = Category::find($id);
    
            if (!$category) {
                throw new \Exception('Category not found');
            }
    
            if ($category->user_id !== $validated['user_id'] && !$request->user()->hasRole('admin')) {
                throw new \Exception('Forbidden');
            }
    
            $existingCategory = Category::where('name', $validated['name'])
                ->where('user_id', $validated['user_id'])
                ->where('id', '!=', $id) 
                ->first();
    
            if ($existingCategory) {
                throw new \Exception('Category exists');
            }
    
            $category->update($validated);
    
            return response()->json([
                'message' => 'Categoría actualizada correctamente',
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return parent::LogError($e->getMessage());
        }
    }

    // DELETE
    public function destroy(Request $request, $id){
        try {
            $category = Category::findOrFail($id);

            if ($category->user_id !== $request->user()->user_id && !$request->user()->hasRole('admin')) {
                throw new \Exception('Forbidden');
            }

            $category->delete();

            return response()->json([
                'message' => 'Categoría eliminada correctamente'
            ], 200);

        } catch (\Exception $e) {
            return parent::LogError($e->getMessage());
        }

    }
}
