<?php

/**
 * AbstractRequest
 *
 * @author xuanhieupd
 * @package Base
 * @copyright 04.10.2020
 */

namespace App\Base;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AbstractRequest extends FormRequest
{

    /**
     * Validate thêm các logic phức tạp
     *
     * @param Validator $validator
     * @return Validator
     * @author xuanhieupd
     */
    public function afterValidator(Validator $validator)
    {
        return $validator;
    }

    /**
     * Get the validator instance for the request.
     *
     * @return Validator
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return $validator;
            }

            $afterValidator = $this->afterValidator($validator);
            if (!is_null($afterValidator)) {
                return $validator;
            }
        });

        return $validator;
    }

}
