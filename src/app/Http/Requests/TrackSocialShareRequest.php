<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackSocialShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint, no authentication required
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'in:facebook,twitter,whatsapp,telegram,email'],
            'page_url' => ['required', 'url', 'max:2048'],
            'page_type' => ['nullable', 'string', 'in:home,news'],
            'news_post_id' => ['nullable', 'integer', 'exists:news_posts,id'],
        ];
    }
}
