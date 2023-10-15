<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class DnsRecord implements DataAwareRule, ValidationRule, ValidatorAwareRule
{
    protected array $data = [];

    protected Validator $validator;

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the current validator.
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $type = \Arr::get($this->data, 'type');

        if (!$type) {
            throw new \LogicException('No detected record type for DNS record validation');
        }

        switch (mb_strtoupper($type)) {
            case 'A':
                if (!$this->validator->validateIpv4($attribute, $value)) {
                    $fail('validation.dns_record')->translate();
                }
                break;
            case 'AAAA':
                if (!$this->validator->validateIpv6($attribute, $value)) {
                    $fail('validation.dns_record')->translate();
                }
                break;
            case 'CNAME':
            case 'NS':
            case 'MX':
            case 'SRV':
                // TODO
                break;
            case 'TXT':
                if (strlen($value) > 1000 || !ctype_print($value)) {
                    $fail('validation.dns_record')->translate();
                }
                break;
            default:
                $fail('validation.dns_record')->translate();
        }
    }
}
