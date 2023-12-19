<?php

namespace Imissher\Equinox\app\core;

abstract class Model
{
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';

    /**
     * Проверка на лишние данные в POST запросе
     * и инициализация нужных переменных
     *
     * @param array $data
     * @return void
     */
    public function getData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract protected function rules(): array;

    protected array $errors;
    public function validate()
    {
        foreach ($this->rules() as $key => $value) {
            foreach ($value as $rule){
                $variable_value = $this->{$key};
                if($rule === self::RULE_REQUIRED && !$variable_value){
                    $this->errors[$key] = self::RULE_REQUIRED;
                }

                if($rule === self::RULE_EMAIL && !filter_var($variable_value, FILTER_VALIDATE_EMAIL)){
                    $this->errors[$key] = self::RULE_REQUIRED;
                }

                //TODO продолжаем здесь
            }
        }

        echo "<pre>";
        print_r($this->errors);
        echo "</pre>";
        exit;
    }
}