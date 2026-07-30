<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'badge_text'         => 'required|string|max:255',
            'title'              => 'required|string|max:255',
            'cta_text'           => 'required|string|max:255',
            'affiliate_link_id'  => 'nullable|exists:affiliate_links,id',
            'desktop_image'      => ($isCreate ? 'required' : 'nullable') . '|image|max:2048',
            'mobile_image'       => 'nullable|image|max:2048',
            'placement'          => 'required|string|in:homepage_banner,homepage_sidebar,hotel_detail,restaurant_detail,attraction_detail,blog_detail,footer_banner',
            'priority'           => 'required|integer|min:1',
            'starts_at'          => 'nullable|date',
            'ends_at'            => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public function messages(): array
    {
        return [
            'ends_at.after_or_equal' => 'The end date must be a date after or equal to the start date.',
            'desktop_image.required' => 'The desktop banner image is required.',
        ];
    }
}
