<?php

namespace App\HTML;

use Error;

class Form
{

    /** @var Object|array */
    protected $data;
    protected $errors;

    public function __construct($data, $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function input(string $name, string $label, bool $required = true)
    {
        $value = $this->getValue($name);
        $required = $required ? 'required' : '';
        [$fieldClass, $fieldFeedback] = $this->getFieldClassAndFeedback($name);
        return <<<HTML
            <div class="mb-3">
                <label for="field{$name}" class="form-label"> {$label}</label>
                <input {$required} type="text" name="{$name}" value="{$value}" class="{$fieldClass}" id="field{$name}">
                {$fieldFeedback}
            </div>
            HTML;
    }
    public function file(string $name, string $label, bool $required = true)
    {
        $required = $required ? 'required' : '';
        [$fieldClass, $fieldFeedback] = $this->getFieldClassAndFeedback($name);
        return <<<HTML
            <div class="mb-3">
                <label for="field{$name}" class="form-label"> {$label}</label>
                <input {$required} type="file" name="{$name}" class="{$fieldClass}" id="field{$name}">
                {$fieldFeedback}
            </div>
            HTML;
    }
    public function password(string $name, string $label, bool $required = true)
    {
        $value = $this->getValue($name);
        $required = $required ? 'required' : '';
        [$fieldClass, $fieldFeedback] = $this->getFieldClassAndFeedback($name);
        return <<<HTML
            <div class="mb-3">
                <label for="field{$name}" class="form-label"> {$label}</label>
                <input {$required} type="password" name="{$name}" value="{$value}" class="{$fieldClass}" id="field{$name}">
                {$fieldFeedback}
            </div>
            HTML;
    }

    public function select(string $name, string $label, array $options = [], bool $required = true, string|array $value = null, bool $multiple = false)
    {
        $multiple = $multiple ? 'multiple' : '';
        $required = $required ? 'required' : '';

        $value = ($value ?? $this->getValue($name)) ?? [];
        $optionsHTML = '';
        foreach ($options as  $option) {
            $selected = in_array($option, $value) ? 'selected' : '';
            $optionsHTML .= "<option {$selected} value=\"{$option['id']}\" >{$option['name']}</option>";
        }
        [$fieldClass, $fieldFeedback] = $this->getFieldClassAndFeedback($name);
        return <<<HTML
            <label for="field{$name}" class="form-label"> {$label}</label>
            <select {$required} name="{$name}[]"  class="{$fieldClass}" id="field{$name}" {$multiple}>
                {$optionsHTML}
            </select>
            {$fieldFeedback}
            HTML;
    }
    public function textArea(string $name, string $label, bool $required = true, string $value = null, int $rows = 20)
    {
        $value = $value ?? $this->getValue($name);
        $required = $required ? 'required' : '';

        [$fieldClass, $fieldFeedback] = $this->getFieldClassAndFeedback($name);
        return <<<HTML
            <label for="field{$name}" class="form-label"> {$label}</label>
            <textarea {$required} name="{$name}"  class="{$fieldClass}" id="field{$name}" placeholder="write here..." rows="{$rows}">{$value}</textarea>
            {$fieldFeedback}
            HTML;
    }
    public function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }
        $method = 'get' . ucfirst($key);
        try {
            return $this->data->$method();
        } catch (Error $e) {
            return null;
        }
    }
    private function getFieldClassAndFeedback(string $name)
    {
        $fieldClass = 'form-control';
        $fieldFeedback = '';
        if (isset($this->errors[$name])) {
            $fieldClass .= ' is-invalid';
            $fieldFeedback = '<div class="invalid-feedback">' . implode('<br>', $this->errors[$name]) . '</div>';
        }
        return [$fieldClass, $fieldFeedback];
    }
}
