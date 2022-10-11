<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;

abstract class BasicPostSaveRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'text'  => ['required', 'string', 'max:99999'],
            'active' => ['required', 'boolean'],
            'slug'  => $this->getSlugRule()
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('title'),
            'slug'  => __('slug'),
            'text'  => __('text'),
        ];
    }

    /**
     * @return string[]
     */
    abstract protected function getSlugRule(): array;
}
