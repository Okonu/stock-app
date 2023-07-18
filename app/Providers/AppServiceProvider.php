<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            try {
                $phoneNumber = $phoneNumberUtil->parse($value, 'KE');

                return $phoneNumberUtil->isValidNumber($phoneNumber) && $this->isMobileNumber($phoneNumberUtil, $phoneNumber);
            } catch (\libphonenumber\NumberParseException $e) {
                return false;
            }
        });

        
    }

    /**
     * Check if the given phone number is a mobile number.
     *
     * @param \libphonenumber\PhoneNumberUtil $phoneNumberUtil
     * @param \libphonenumber\PhoneNumber     $phoneNumber
     *
     * @return bool
     */
    private function isMobileNumber($phoneNumberUtil, $phoneNumber)
    {
        $type = $phoneNumberUtil->getNumberType($phoneNumber);

        return $type === \libphonenumber\PhoneNumberType::MOBILE || $type === \libphonenumber\PhoneNumberType::FIXED_LINE_OR_MOBILE;
    }
    
}
