<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;


class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(!auth()->user()->hasRole('admin')){
            Log::error('Unauthorized' . 'User: ' . auth()->user());
        }
        return auth()->user() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [];
    }
}
