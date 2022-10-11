<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

abstract class BasicUserSaveRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => $this->getEmailRule(),
            'password' => $this->getPasswordRule(),
        ];
    }

    public function attributes()
    {
        return [
            'name'     => __('name'),
            'email'    => __('email'),
            'password' => __('password')
        ];
    }

    /**
     * @return string[]
     */
    abstract protected function getEmailRule(): array;

    /**
     * @return string[]
     */
    abstract protected function getPasswordRule(): array;
}
