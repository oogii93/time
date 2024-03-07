<?php

namespace App\Rules;

use Closure;
use App\Models\ArrivalRecord;
use Illuminate\Contracts\Validation\ValidationRule;

class RecordedAtExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $arrival = ArrivalRecord::where('user_id',request()->user()->id)->whereDate('recorded_at', date("Y-m-d", strtotime($value)))->first();

        if(!empty($arrival))
        {
            $fail("出勤時間が登録されています");
        }

    }
}