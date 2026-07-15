<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CommentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_detail_id' => ['required', 'integer', 'exists:order_details,id'],
            'rating' => ['required', 'numeric', 'min:0.5', 'max:5'],
            'content' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $rating = (float) $this->input('rating');
            $halfSteps = round($rating * 2);

            if ($halfSteps !== $rating * 2) {
                $validator->errors()->add('rating', 'Puan yalnızca yarım yıldız artışlarla seçilebilir (ör. 3.5, 4.5).');
            }
        });
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Lütfen bir yıldız puanı seçin.',
            'rating.min' => 'Puan en az 0.5 olmalıdır.',
            'rating.max' => 'Puan en fazla 5 olabilir.',
            'content.required' => 'Yorum metni zorunludur.',
        ];
    }
}
