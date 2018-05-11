<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use Illuminate\Validation\Rule;

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
//        dd(Auth::id());
//        dd($this->all());
//        'required|unique:exercises,name,NULL,id,user_id,'. Auth::id(),
        return [
//            'name' => [
//                'required',
//                Rule::unique('exercises')->where(function ($query) {
//                    dd($query)->toSql();
//                    $query->where('user_id', '!=', Auth::id());
//                })
//            ],
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
