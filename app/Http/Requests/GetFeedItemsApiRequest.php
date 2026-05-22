<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetFeedItemsApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category_ids' => $this->normalizeIdList('category_ids'),
            'source_ids' => $this->normalizeIdList('source_ids'),
            // 'sort_by' => is_string($this->input('sort_by'))
            //     ? strtoupper($this->input('sort_by'))
            //     : $this->input('sort_by'),
        ]);
    }

    public function rules(): array
    {
        return [
            'language_code' => [
                'required',
                'string',
                Rule::exists('tbl_languages', 'code')->where('status', 1),
            ],
            // 'language_id' => [
            //     'nullable',
            //     'required_without:language_code',
            //     'integer',
            //     Rule::exists('tbl_languages', 'id')->where('status', 1),
            // ],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            // 'order_by' => ['nullable', Rule::in(['id', 'rss_source_id', 'title', 'published_at', 'fetched_at'])],
            // 'sort_by' => ['nullable', Rule::in(['ASC', 'DESC'])],
            'category_ids' => ['nullable', 'string'],
            // 'category_ids.*' => ['integer', Rule::exists('tbl_category', 'id')->where('status', 1)],
            'source_ids' => ['nullable', 'string'],
            // 'source_ids.*' => ['integer', Rule::exists('tbl_rss', 'id')->where('status', 1)],
        ];
    }

    private function normalizeIdList(string $key): array
    {
        $value = $this->input($key);

        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value, static fn ($item) => $item !== null && $item !== ''));
        }

        if (is_string($value)) {
            $parts = array_map('trim', explode(',', $value));

            return array_values(array_filter($parts, static fn ($item) => $item !== ''));
        }

        return [];
    }
}
