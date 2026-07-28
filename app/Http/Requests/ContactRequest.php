<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'nullable|string|max:25',
            'subject'   => 'nullable|string|max:255',
            'message'   => 'required|string',
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                // Bypass external API call locally to avoid XAMPP DNS lookup timeouts
                if (config('app.env') === 'local') {
                    if (empty($value)) {
                        $fail('Please verify that you are not a robot.');
                    }
                    return;
                }

                $secret = env('RECAPTCHA_SECRET_KEY');
                $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
                
                if (!$response->json('success')) {
                    $fail('The reCAPTCHA verification failed. Please try again.');
                }
            }]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'email.required'     => 'Email address is required.',
            'email.email'        => 'Please provide a valid email address.',
            'subject.required'   => 'Please provide a subject for your message.',
            'message.required'   => 'Please enter your message.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.'
        ];
    }
}
