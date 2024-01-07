<?php

namespace Imissher\Equinox\app\core;

abstract class Model
{
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_UNIQUE = 'unique';

    /**
     * Проверка на лишние данные в POST запросе
     * и инициализация нужных переменных
     *
     * @param array $data
     * @return array
     */
    public function getData(array $data): array
    {
        $userData = [];
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
                $userData[$key] = $value;
            }
        }

        return $userData;
    }

    abstract protected function rules(): array;

    private array $errors = [];

    /**
     * Валидация данных с правилами описанными в модели
     *
     * @param array $rules
     * @return bool
     */
    public function validate(array $rules = []): bool
    {
        if(count($rules) === 0) $rules = $this->rules();
        foreach ($rules as $key => $value) {
            foreach ($value as $rule) {
                $variable_value = $this->{$key};
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = array_keys($rule)[0];
                    $ruleValue = $rule[$ruleName] ?? null;
                }

                if ($ruleName === self::RULE_REQUIRED && !$variable_value) {
                    $this->addError(self::RULE_REQUIRED, $key, [$ruleName => $key]);
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($variable_value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError(self::RULE_EMAIL, $key, [$ruleName => $key]);
                }

                if ($ruleName === self::RULE_MIN && strlen($variable_value) < $ruleValue) {
                    $this->addError(self::RULE_MIN, $key, [$ruleName => $ruleValue]);
                }

                if ($ruleName === self::RULE_MAX && strlen($variable_value) > $ruleValue) {
                    $this->addError(self::RULE_MAX, $key, [$ruleName => $ruleValue]);
                }
                
                if($ruleName === self::RULE_UNIQUE){
                    $tableName = $this->tableName();
                    $db = Application::$app->db->pdo;
                    $statement = $db->prepare("SELECT COUNT(*) as count FROM $tableName WHERE $key = '$variable_value'");
                    $statement->execute();
                    $count = $statement->fetch()['count'];

                    if($count >= 1){
                        $this->addError(self::RULE_UNIQUE, $key, [$ruleName => $variable_value]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Возврат сообщения об ошибке
     *
     * @param string $rule
     * @param string $attr
     * @param array $params
     * @return void
     */
    private function addError(string $rule, string $attr, array $params = []): void
    {
        $error = $this->errorMessage()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $error = str_replace("{{$key}}", $value, $error);
        }
        $this->errors[$attr][] = $error;
    }

    /**
     * @return string[]
     */
    private function errorMessage(): array
    {
        return [
            self::RULE_REQUIRED => 'Поле {required} обязательное',
            self::RULE_EMAIL => 'В поле {email} должен быть действительный адрес электронной почты',
            self::RULE_MIN => 'Минимальная длина этого поля {min}',
            self::RULE_MAX => 'Максимальная длина этого поля {max}',
            self::RULE_UNIQUE => 'Поле со значение {unique} уже существует',
        ];
    }

    /**
     * Возвращает true / false в зависимости от наличия ошибки у данного поля
     *
     * @param string $field
     * @return bool Возвращает `true`, если ошибка есть, `false`, если нет
     */
    public function hasErrors(string $field): bool
    {
        return (bool)$this->errors[$field];
    }

    public function getFirstError(string $field = '')
    {
        $firstField = array_keys($this->errors)[0];
        if($field !== '') return $this->errors[$field][0];
        return $this->errors[$firstField][0];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}