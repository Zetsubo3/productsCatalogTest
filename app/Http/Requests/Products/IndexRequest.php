<?php

namespace App\Http\Requests\Products;

use App\Contracts\Requests\FilterableRequestInterface;
use App\Http\Requests\MainRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class IndexRequest extends MainRequest implements FilterableRequestInterface
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
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return  [
            'page' => 'nullable',
            'count' => 'nullable',
            'category_id' => 'nullable',
            'price_min' => 'nullable',
            'price_max' => 'nullable',
            'name' => 'nullable',
            'price_sort' => 'nullable',
            'created_at_sort' => 'nullable',
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

        ];
    }

    public function getRequestParams(): array
    {
        return [
            'page' => $this->normalizeIntParam('page', PHP_INT_MAX, 1),
            'count' => $this->normalizeIntParam('count', 100, 15),
            'category_id' => $this->normalizeIntParam('category_id', PHP_INT_MAX, null),
            'price_min' => $this->normalizeFloatParam('price_min', 0, null, null),
            'price_max' => $this->normalizeFloatParam('price_max', 0, null, null),
            'name' => $this->input('name'),
            'price_sort' => $this->normalizeSortParam('price_sort'),
            'created_at_sort' => $this->normalizeSortParam('created_at_sort'),
        ];
    }
}
