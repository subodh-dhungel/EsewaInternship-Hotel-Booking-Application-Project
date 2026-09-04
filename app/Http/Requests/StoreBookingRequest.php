<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            // Check-in date
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            // Check-out date
            'check_out' => [
                'required',
                'date',
                'after:check_in',
            ],

            // Number of adults
            'adults' => [
                'required',
                'integer',
                'min:1',
            ],

            // Number of children
            'children' => [
                'required',
                'integer',
                'min:0',
            ],

            // Number of rooms
            'number_of_rooms' => [
                'required',
                'integer',
                'min:1',
            ],

            // Customer phone number
            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^9[678]\d{8}$/',
            ],
        ];
    }
}