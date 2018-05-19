<?php

namespace App\Http\Requests;

use Auth;

class ExerciseStoreRequest extends Request
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
            'name' => 'required|unique:exercises,name,NULL,id,user_id,' . Auth::id(),
            'priority' => 'required',
        ];
    }


    /**
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'That name already exists.'
        ];
    }
}
