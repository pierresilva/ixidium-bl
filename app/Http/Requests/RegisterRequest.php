<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
      //
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ];
  }

  /**
   * Response if validation fails
   *
   * @param Validator $validator
   */
  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(
      response()->json([
        'message' => 'Existen errores en el formulario!',
        'errors' => $validator->errors()
      ], 422));
  }
}
