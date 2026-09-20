<?php

declare(strict_types=1);

namespace Domains\User;

/**
 * The fields a User is made of, read off a request body and checked.
 *
 * Store and Update both take a payload and apply the same rules, so the rules
 * live here rather than twice. There is no validation layer in the framework;
 * this class is the whole mechanism for this feature, and it stays readable
 * because it does nothing general.
 */
final readonly class Attributes
{
    /**
     * @param array<string, string> $values
     * @param array<string, string> $errors field => message
     */
    private function __construct(
        public array $values,
        public array $errors,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        $values = [];

        // TODO: name the fields. Each one is read off the payload here so that
        // nothing else in the body is carried into a write by accident:
        //
        //     $values['title'] = self::string($payload['title'] ?? '');
        //     $values['body'] = self::string($payload['body'] ?? '');

        $errors = [];

        // TODO: the rules. A rule is a condition and the message shown beside
        // the field when it fails:
        //
        //     if ($values['title'] === '') {
        //         $errors['title'] = 'A title is required.';
        //     }

        return new self($values, $errors);
    }

    public function isValid(): bool
    {
        return $this->errors === [];
    }

    /**
     * A form field is a string, but a JSON body can send anything. Anything
     * that is not a scalar is treated as absent rather than cast to "Array".
     */
    private static function string(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }
}
