<?php

namespace Core;

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $customMessages = [];

    // Discriminatory phrases to block
    private const DISCRIMINATORY_PHRASES = [
        'en' => [
            'locals only',
            'no foreigners',
            'no expats',
            'estonians only',
            'only estonians',
            'no immigrants',
            'citizens only'
        ],
        'et' => [
            'ainult kohalikud',
            'välismaalased ei sobi',
            'ainult eestlased',
            'kohalikud ainult',
            'ei sobi välismaalased'
        ],
        'ru' => [
            'только местные',
            'иностранцам нет',
            'только для граждан',
            'местные только'
        ]
    ];

    public function __construct(array $data, array $rules, array $customMessages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $customMessages;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            $rulesList = is_string($rules) ? explode('|', $rules) : $rules;

            foreach ($rulesList as $rule) {
                $this->applyRule($field, $rule);
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, string $rule): void
    {
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $params = $parts[1] ?? null;

        $value = $this->data[$field] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'min':
                if ($value && strlen($value) < (int) $params) {
                    $this->addError($field, $ruleName, ['min' => $params]);
                }
                break;

            case 'max':
                if ($value && strlen($value) > (int) $params) {
                    $this->addError($field, $ruleName, ['max' => $params]);
                }
                break;

            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'integer':
                if ($value && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'min_value':
                if ($value !== null && $value !== '' && (float) $value < (float) $params) {
                    $this->addError($field, $ruleName, ['min' => $params]);
                }
                break;

            case 'max_value':
                if ($value !== null && $value !== '' && (float) $value > (float) $params) {
                    $this->addError($field, $ruleName, ['max' => $params]);
                }
                break;

            case 'in':
                $allowed = explode(',', $params);
                if ($value && !in_array($value, $allowed)) {
                    $this->addError($field, $ruleName, ['values' => $params]);
                }
                break;

            case 'unique':
                [$table, $column] = explode(',', $params);
                $db = new Database();
                $existing = $db->selectOne($table, [$column => $value]);
                if ($existing) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'password':
                if (!$this->validatePassword($value)) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'no_discrimination':
                if ($value && $this->containsDiscriminatoryContent($value)) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'url':
                if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, $ruleName);
                }
                break;

            case 'youtube':
                if ($value && !$this->isYoutubeUrl($value)) {
                    $this->addError($field, $ruleName);
                }
                break;
        }
    }

    private function validatePassword(string $password): bool
    {
        // Minimum 12 characters
        if (strlen($password) < 12) {
            return false;
        }

        // At least one uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // At least one lowercase
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // At least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // No sequential characters
        if (preg_match('/123|234|345|456|567|678|789|abc|bcd|cde|def/i', $password)) {
            return false;
        }

        // No repeated characters (3 or more)
        if (preg_match('/(.)\1{2,}/', $password)) {
            return false;
        }

        return true;
    }

    private function containsDiscriminatoryContent(string $text): bool
    {
        $text = strtolower($text);

        foreach (self::DISCRIMINATORY_PHRASES as $phrases) {
            foreach ($phrases as $phrase) {
                if (strpos($text, strtolower($phrase)) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isYoutubeUrl(string $url): bool
    {
        return preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//', $url);
    }

    private function addError(string $field, string $rule, array $params = []): void
    {
        $message = $this->customMessages[$field . '.' . $rule]
            ?? $this->getDefaultMessage($field, $rule, $params);

        $this->errors[$field][] = $message;
    }

    private function getDefaultMessage(string $field, string $rule, array $params = []): string
    {
        $field = ucfirst(str_replace('_', ' ', $field));

        return match ($rule) {
            'required' => "{$field} is required",
            'email' => "{$field} must be a valid email address",
            'min' => "{$field} must be at least {$params['min']} characters",
            'max' => "{$field} must not exceed {$params['max']} characters",
            'numeric' => "{$field} must be a number",
            'integer' => "{$field} must be an integer",
            'min_value' => "{$field} must be at least {$params['min']}",
            'max_value' => "{$field} must not exceed {$params['max']}",
            'in' => "{$field} must be one of: {$params['values']}",
            'unique' => "{$field} already exists",
            'password' => "Password must be at least 12 characters with uppercase, lowercase, number, and no common patterns",
            'no_discrimination' => "Please ensure your content is welcoming to all residents. We noticed language that may exclude certain groups.",
            'url' => "{$field} must be a valid URL",
            'youtube' => "{$field} must be a valid YouTube URL",
            default => "{$field} is invalid"
        };
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(?string $field = null): ?string
    {
        if ($field) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }

        return null;
    }

    public function failed(): bool
    {
        return !empty($this->errors);
    }
}
