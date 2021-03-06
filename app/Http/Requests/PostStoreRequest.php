<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'image' => ['required', 'string'],
            'description' => ['required', 'string'],
            'content' => ['required', 'string'],
            'views' => ['required', 'integer'],
            'score' => ['required', 'numeric', 'between:-0.99,0.99'],
            'is_featured' => ['required'],
            'slug' => ['required', 'string'],
            'publishet_at' => [''],
        ];
    }
}
