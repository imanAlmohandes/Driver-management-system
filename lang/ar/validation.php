<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'               => 'يجب قبول حقل :attribute.',
    'accepted_if'            => 'The :attribute field must be accepted when :other is :value.',
    'active_url'             => 'حقل :attribute ليس رابطاً صحيحاً.',
    'after'                  => 'حقل :attribute يجب أن يكون تاريخاً بعد :date.',
    'after_or_equal'         => 'حقل :attribute يجب أن يكون تاريخاً بعد أو يساوي :date.',
    'alpha'                  => 'حقل :attribute يجب أن يحتوي على حروف فقط.',
    'alpha_dash'             => 'حقل :attribute يجب أن يحتوي على حروف، أرقام، شرطات، وشرطات سفلية فقط.',
    'alpha_num'              => 'حقل :attribute يجب أن يحتوي على حروف وأرقام فقط.',
    'any_of'                 => 'The :attribute field is invalid.',
    'array'                  => 'حقل :attribute يجب أن يكون مصفوفة.',
    'ascii'                  => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before'                 => 'حقل :attribute يجب أن يكون تاريخاً قبل :date.',
    'before_or_equal'        => 'حقل :attribute يجب أن يكون تاريخاً قبل أو يساوي :date.',
    'between'                => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم ملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف :attribute بين :min و :max.',
        'array'   => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max.',
    ],
    'boolean'                => 'حقل :attribute يجب أن يكون صحيحاً أو خاطئاً.',
    'can'                    => 'The :attribute field contains an unauthorized value.',
    'confirmed'              => 'تأكيد حقل :attribute غير مطابق.',
    'contains'               => 'The :attribute field is missing a required value.',
    'current_password'       => 'كلمة المرور غير صحيحة.',
    'date'                   => 'حقل :attribute ليس تاريخاً صحيحاً.',
    'date_equals'            => 'حقل :attribute يجب أن يكون تاريخاً مساوياً لـ :date.',
    'date_format'            => 'حقل :attribute لا يطابق الصيغة :format.',
    'decimal'                => 'The :attribute field must have :decimal decimal places.',
    'declined'               => 'يجب رفض حقل :attribute.',
    'declined_if'            => 'The :attribute field must be declined when :other is :value.',
    'different'              => 'حقل :attribute و :other يجب أن يكونا مختلفين.',
    'digits'                 => 'حقل :attribute يجب أن يتكون من :digits أرقام.',
    'digits_between'         => 'حقل :attribute يجب أن يتكون من عدد أرقام بين :min و :max.',
    'dimensions'             => 'حقل :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct'               => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_contain'         => 'The :attribute field must not contain any of the following: :values.',
    'doesnt_end_with'        => 'The :attribute field must not end with one of the following: :values.',
    'doesnt_start_with'      => 'The :attribute field must not start with one of the following: :values.',
    'email'                  => 'حقل :attribute يجب أن يكون عنوان بريد إلكتروني صحيح.',
    'encoding'               => 'The :attribute field must be encoded in :encoding.',
    'ends_with'              => 'حقل :attribute يجب أن ينتهي بإحدى القيم التالية: :values.',
    'enum'                   => 'قيمة :attribute المختارة غير صالحة.',
    'exists'                 => 'قيمة :attribute المختارة غير صالحة.',
    'extensions'             => 'The :attribute field must have one of the following extensions: :values.',
    'file'                   => 'حقل :attribute يجب أن يكون ملفاً.',
    'filled'                 => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt'                     => [
        'array'   => 'The :attribute field must have more than :value items.',
        'file'    => 'The :attribute field must be greater than :value kilobytes.',
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value.',
        'string'  => 'يجب أن يكون طول :attribute أكبر من :value حروف.',

    ],
    'gte'                    => [
        'array'   => 'The :attribute field must have :value items or more.',
        'file'    => 'The :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :value.',
        'string'  => 'يجب أن يكون طول :attribute أكبر من أو يساوي :value حروف.',
    ],
    'hex_color'              => 'The :attribute field must be a valid hexadecimal color.',
    'image'                  => 'حقل :attribute يجب أن يكون صورة.',
    'in'                     => 'قيمة :attribute المختارة غير صالحة.',
    'in_array'               => 'The :attribute field must exist in :other.',
    'in_array_keys'          => 'The :attribute field must contain at least one of the following keys: :values.',
    'integer'                => 'حقل :attribute يجب أن يكون عدداً صحيحاً.',
    'ip'                     => 'حقل :attribute يجب أن يكون عنوان IP صحيحاً.',
    'ipv4'                   => 'The :attribute field must be a valid IPv4 address.',
    'ipv6'                   => 'The :attribute field must be a valid IPv6 address.',
    'json'                   => 'The :attribute field must be a valid JSON string.',
    'list'                   => 'The :attribute field must be a list.',
    'lowercase'              => 'The :attribute field must be lowercase.',
    'lt'                     => [
        'array'   => 'The :attribute field must have less than :value items.',
        'file'    => 'The :attribute field must be less than :value kilobytes.',
        'numeric' => 'The :attribute field must be less than :value.',
        'string'  => 'The :attribute field must be less than :value characters.',
    ],
    'lte'                    => [
        'array'   => 'The :attribute field must not have more than :value items.',
        'file'    => 'The :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be less than or equal to :value.',
        'string'  => 'The :attribute field must be less than or equal to :value characters.',
    ],
    'mac_address'            => 'The :attribute field must be a valid MAC address.',
    'max'                    => [
        'array'   => 'The :attribute field must not have more than :max items.',
        'file'    => 'The :attribute field must not be greater than :max kilobytes.',
        'numeric' => 'يجب ألا تكون قيمة :attribute أكبر من :max.',
        'string'  => 'يجب ألا يكون طول :attribute أكبر من :max حروف.',
    ],
    'max_digits'             => 'The :attribute field must not have more than :max digits.',
    'mimes'                  => 'The :attribute field must be a file of type: :values.',
    'mimetypes'              => 'The :attribute field must be a file of type: :values.',
    'min'                    => [
        'array'   => 'The :attribute field must have at least :min items.',
        'file'    => 'The :attribute field must be at least :min kilobytes.',
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'string'  => 'يجب أن يكون طول :attribute على الأقل :min حروف.',
    ],
    'min_digits'             => 'The :attribute field must have at least :min digits.',
    'missing'                => 'The :attribute field must be missing.',
    'missing_if'             => 'The :attribute field must be missing when :other is :value.',
    'missing_unless'         => 'The :attribute field must be missing unless :other is :value.',
    'missing_with'           => 'The :attribute field must be missing when :values is present.',
    'missing_with_all'       => 'The :attribute field must be missing when :values are present.',
    'multiple_of'            => 'حقل :attribute يجب أن يكون من مضاعفات :value.',
    'not_in'                 => 'قيمة :attribute المختارة غير صالحة.',
    'not_regex'              => 'صيغة حقل :attribute غير صالحة.',
    'numeric'                => 'حقل :attribute يجب أن يكون رقماً.',
    'password'               => [
        'letters'       => 'حقل :attribute يجب أن يحتوي على حرف واحد على الأقل.',
        'mixed'         => 'حقل :attribute يجب أن يحتوي على حرف كبير وحرف صغير واحد على الأقل.',
        'numbers'       => 'حقل :attribute يجب أن يحتوي على رقم واحد على الأقل.',
        'symbols'       => 'حقل :attribute يجب أن يحتوي على رمز واحد على الأقل.',
        'uncompromised' => 'كلمة المرور :attribute المستخدمة قد تم تسريبها. يرجى اختيار كلمة مرور مختلفة.',
    ],
    'present'                => 'حقل :attribute يجب أن يكون موجوداً.',
    'present_if'             => 'The :attribute field must be present when :other is :value.',
    'present_unless'         => 'The :attribute field must be present unless :other is :value.',
    'present_with'           => 'The :attribute field must be present when :values is present.',
    'present_with_all'       => 'The :attribute field must be present when :values are present.',
    'prohibited'             => 'حقل :attribute ممنوع.',
    'prohibited_if'          => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_if_accepted' => 'The :attribute field is prohibited when :other is accepted.',
    'prohibited_if_declined' => 'The :attribute field is prohibited when :other is declined.',
    'prohibited_unless'      => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits'              => 'The :attribute field prohibits :other from being present.',
    'regex'                  => 'صيغة حقل :attribute غير صالحة.',
    'required'               => 'حقل :attribute مطلوب.',
    'required_array_keys'    => 'The :attribute field must contain entries for: :values.',
    'required_if'            => 'حقل :attribute مطلوب عندما تكون قيمة :other هي :value.',
    'required_if_accepted'   => 'The :attribute field is required when :other is accepted.',
    'required_if_declined'   => 'The :attribute field is required when :other is declined.',
    'required_unless'        => 'The :attribute field is required unless :other is in :values.',
    'required_with'          => 'The :attribute field is required when :values is present.',
    'required_with_all'      => 'The :attribute field is required when :values are present.',
    'required_without'       => 'The :attribute field is required when :values is not present.',
    'required_without_all'   => 'The :attribute field is required when none of :values are present.',
    'same'                   => 'حقل :attribute و :other يجب أن يتطابقا.',
    'size'                   => [
        'array'   => 'The :attribute field must contain :size items.',
        'file'    => 'The :attribute field must be :size kilobytes.',
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size.',
        'string'  => 'يجب أن يكون طول :attribute مساوياً لـ :size حروف.',
    ],
    'starts_with'            => 'حقل :attribute يجب أن يبدأ بإحدى القيم التالية: :values.',
    'string'                 => 'حقل :attribute يجب أن يكون نصاً.',
    'timezone'               => 'حقل :attribute يجب أن يكون منطقة زمنية صحيحة.',
    'unique'                 => 'قيمة :attribute مستخدمة من قبل.',
    'uploaded'               => 'فشل تحميل :attribute.',
    'url'                    => 'صيغة :attribute غير صحيحة.',
    'uuid'                   => 'حقل :attribute يجب أن يكون UUID صحيحاً.',
    'uppercase'              => 'The :attribute field must be uppercase.',
    'ulid'                   => 'The :attribute field must be a valid ULID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'                 => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes'             => [
        'name'           => 'الاسم',
        'email'          => 'البريد الإلكتروني',
        'password'       => 'كلمة المرور',
        'license_number' => 'رقم الرخصة',
        'service_date'   => 'تاريخ الخدمة',
        'cost'           => 'التكلفة',
        'vehicle_id'     => 'المركبة',
        'company_id'     => 'الشركة',
        'trip_id'        => 'الرحلة',
    ],

];
