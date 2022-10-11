<?php

namespace App\Http\Requests\Admin\User;

class UserStoreRequest extends BasicUserSaveRequest
{
    /**
     * @return string[]
     */
    protected function getEmailRule(): array
    {
        return ['required', 'string', 'email', 'max:255', 'unique:users'];
    }

    /**
     * @return string[]
     */
    protected function getPasswordRule(): array
    {
        return ['required', 'string', 'min:8', 'confirmed'];
    }
}
