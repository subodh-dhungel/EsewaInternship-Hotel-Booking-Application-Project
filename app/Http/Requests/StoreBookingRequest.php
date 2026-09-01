<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
    public function rules():array
    {
        return [
            // checkin date validate garne
            'check-in' => [
                'required',
                'date',
                'after_or_equal:today'
            ],

            // checkout date validate garne
            'check-out' => [
                'required',
                'date',
                'after:check-in',
            ],

            // Kati jana adult guest chan vanera validate garne
            'adults' => [
                'required',
                'integer',
                'min:1'
            ],

            // children ko number validate garne
            'children' => [
                'required',
                'integer',
                'min:0'
            ],

            // customer le kati ota room book garna khojeko ho validate garne
            'number_of_rooms' => [
                'required',
                'integer',
            ],

            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^9[678]\d{8}$/',
            ],
        ];
    }
}
