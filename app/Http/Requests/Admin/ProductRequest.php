<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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
        switch ($this->method()){
            case 'PUT':case 'PATCH':
                $id = $this->route('product')->id ? ',' . $this->route('product')->id : '';
                $rules = [
                    'image' => 'required',
                    'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'name' => 'string|unique:products,name'.$id,
                    'description' => 'string',
                    'price' => 'numeric',
                    'quantities' => 'numeric|min:0',
                    'categories' => 'required',
                    'categories.*' => 'int'
                ];
            break;
            default:
                $rules = [
                    'image' => 'required',
                    'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'name' => 'string|unique:products',
                    'description' => 'string',
                    'price' => 'numeric',
                    'quantities' => 'numeric|min:0',
                    'categories' => 'required',
                    'categories.*' => 'int'
                ];
                break;
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
