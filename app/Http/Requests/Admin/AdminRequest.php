<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AdminRequest extends FormRequest
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
                $id = $this->route('admin')->id ? ',' . $this->route('admin')->id : '';
                $rules = [
                    'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg',
                    'name' => 'required|string',
                    'username' => 'required|unique:admins,username'.$id,
                    'email' => 'required|email|unique:admins,email'.$id,
                    'password' => 'min:6',
                ];
                break;
            default:
                $rules = [
                    'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg',
                    'name' => 'required|string',
                    'username' => 'required|unique:admins',
                    'email' => 'required|email|unique:admins',
                    'password' => 'required|min:6',
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
