<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //cambiamos de false a true para que pueda ingresar a esta hoja y realizar las validaciones
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $slug = request()->isMethod('put') ? 'required|unique:categories,slug,' . $this->id : 'required|unique:categories';
        $image = request()->isMethod('put') ? 'nullable|image' : 'required|image';

        return [
            //reglas de validacion
            'name' => 'required|max:40',
            'slug' => $slug,
            'image' => $image,
            'status' => 'required|boolean',
            'is_featured' => 'required|boolean',
        ];
    }
}
