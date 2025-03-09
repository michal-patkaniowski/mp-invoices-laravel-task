<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Services;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Validators\InvoiceValidatorInterface;
use Exception;
use InvalidArgumentException;

class InvoiceValidatorService
{
    /**
    * @param string[] $validatorClasses Array of validator class names as strings
     * @param Invoice $invoice
     * @return string[]
     */
    public function validate(array $validatorClasses, Invoice $invoice): array
    {
        $errors = [];
        foreach ($validatorClasses as $validatorClass) {
            if (!class_exists($validatorClass)) {
                throw new InvalidArgumentException("Validator class $validatorClass does not exist.");
            }

            $validator = new $validatorClass();

            if (!$validator instanceof InvoiceValidatorInterface) {
                throw new InvalidArgumentException(
                    "Validator class $validatorClass must implement InvoiceValidatorInterface."
                );
            }

            if (!$validator->validate($invoice)) {
                $errors[] = $validator->getValidationError();
            }
        }

        return $errors;
    }

    public function validateOrFail(array $validatorClasses, Invoice $invoice): void
    {
        $errors = $this->validate($validatorClasses, $invoice);
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors));
        }
    }
}
