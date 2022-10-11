<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;

class UserUpdateRequest extends BasicUserSaveRequest
{
    /**
     * @return string[]
     */
    protected function getEmailRule(): array
    {
        $userId = isset($this->user) && $this->user instanceOf User ? (int)$this->user->id : 0;
        return ['required', 'string', 'email', 'max:255', 'unique:users,email,'. $userId];
    }

    /**
     * @return string[]
     */
    protected function getPasswordRule(): array
    {
        return ['nullable', 'string', 'min:8', 'confirmed'];
    }
}
