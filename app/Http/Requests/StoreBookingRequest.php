<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',

          //  'user_id' => 'required|exists:users,id',
            //'staus' => 'required|in:pending,confirmed,cancelled',
            'payment_method'=> 'required|in:paymob,cash,myfatoorah',
        ];
    }
}
