<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;

class UniqueProductTag implements ValidationRule, DataAwareRule
{
    private int $ignoreId;

    public function __construct(int $ignore = 0)
    {
        $this->ignoreId = $ignore;
    }
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach(Product::all() as $product){
            if($product->id !== $this->ignoreId){
                $notUnique = $this->validateTag($product, $value);
                if($notUnique){
                    $fail('El producto ya esta registrado');
                }
            }
        }
    }

    private function validateTag(Product $product, mixed $value): bool
    {
        $sameTag =
            ($product->type_id == $this->data['type'])
            && ($product->name == strtoupper($value))
            && ($product->presentation_id == $this->data['presentation']);
        return $sameTag;
    }
}