<?php

namespace App\Http\Forms;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ThrottleException;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Gate::allows('create', new \App\Reply);
    }

    protected function failedAuthorization() 
    {
        throw new ThrottleException('You are replying too frequently.');
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body'  => 'required|spamfree'
        ];
    }

    // public function persist($thread)
    // {
    //     return $thread->addReply([
    //         'body' => request('body'),
    //         'user_id' => auth()->id(),
    //     ])->load('owner');
    // }
}
