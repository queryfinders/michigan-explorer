<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ActionType;
use Illuminate\Validation\Rule;

class UpdateSearchShortcutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:search_shortcuts,slug,' . $this->route('search_shortcut')->id,
            'icon' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:255',
            'action_type' => ['required', Rule::enum(ActionType::class)],
            'action_value' => 'nullable|string|max:255',
            'open_in' => 'required|in:same_tab,new_tab',
            'sort_order' => 'required|integer',
            'status' => 'boolean',
        ];
    }
}
