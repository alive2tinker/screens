<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachment extends FormRequest
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
            'title' => "required",
            'type' => "required",
            'text' => "required_if:type,quote",
            'image' => "required_if:type,quote|required_if:type,image",
            'link' => "required_if:type,youtube|required_if:type,tweet"
        ];
    }
}
