<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ActionType;
use Illuminate\Validation\Rule;

/**
 * Class StoreSearchShortcutRequest
 *
 * Validates the incoming HTTP request when an administrator creates a new dynamic search shortcut.
 */
class StoreSearchShortcutRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:search_shortcuts,slug',
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
