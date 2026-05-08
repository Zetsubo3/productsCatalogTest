<?php

namespace App\Http\Requests\Products;

use App\Contracts\Requests\FilterableRequestInterface;
use App\Http\Requests\MainRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Validator;

class EditRequest extends MainRequest implements FilterableRequestInterface
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|min:2|max:255',
            'price' => 'nullable|decimal:0,2|min:0|max:9999999999.99',
            'category_id' => 'nullable|integer|min:1|max:9999999999',
        ];
    }

    /**
     * Возвращает сообщения об ошибках для валидации.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The "name" parameter must be a valid string.',
            'name.min' => 'The "name" parameter must be at least 2 characters.',
            'name.max' => 'The "name" parameter must not exceed 255 characters.',

            'price.decimal' => 'The "price" parameter must be a number with up to 2 decimal places.',
            'price.min' => 'The "price" parameter must be at least 0.',
            'price.max' => 'The "price" parameter must not exceed 9999999999.99.',

            'category_id.integer' => 'The "category_id" parameter must be an integer.',
            'category_id.min' => 'The "category_id" parameter must be at least 1.',
            'category_id.max' => 'The "category_id" parameter must not exceed 9999999999.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $hasName = !empty($this->name);
            $hasPrice = $this->price !== null && $this->price !== '';
            $hasCategoryId = !empty($this->category_id);

            if (!$hasName && !$hasPrice && !$hasCategoryId) {
                $validator->errors()->add(
                    'fields',
                    'At least one field (name, price, category_id) must be provided for update.'
                );
            }
        });
    }

    public function getRequestParams(): array
    {
        return [
            'name' => $this->input('name'),
            'price' => $this->input('price'),
            'category_id' => $this->input('category_id'),
        ];
    }
}
