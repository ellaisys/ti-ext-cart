<?php namespace SamPoyigi\Cart\Models;

use Model;

class CartSettings extends Model
{
    public $implement = ['System\Actions\SettingsModel'];

    // A unique code
    public $settingsCode = 'sampoyigi_cart_settings';

    // Reference to field configuration
    public $settingsFieldsConfig = 'cartsettings';

    /**
     * @var array An array of registered conditions.
     */
    protected static $registeredConditions;

    protected static $registeredConditionHints = [];

    /**
     * @var array Cache of cart conditions registration callbacks.
     */
    protected static $registeredConditionsCallbacks = [];

    protected static $conditions;

    public function getConditionsAttribute($value)
    {
        $registeredConditions = $this->listRegisteredConditions();

        foreach ($registeredConditions as $registeredCondition) {
            $name = array_get($registeredCondition, 'name');
            $dbCondition = $value[$name] ?? [];
            $value[$name] = array_merge($registeredCondition, $dbCondition);
        }

        return $value;
    }

    //
    //
    //

    public function findCondition($name)
    {
        $conditions = $this->listConditions();
        if (empty($conditions[$name])) {
            return null;
        }

        return $conditions[$name];
    }

    public function listConditions()
    {
        if (self::$conditions)
            return self::$conditions;

        $result = [];

        $availableConditions = (array)self::get('conditions');
        foreach ($availableConditions as $name => $condition) {
            $className = array_get($condition, 'className');
            if (!class_exists($className))
                continue;

            $result[$name] = new $className($condition);
        }

        return self::$conditions = $result;
    }

    public function listRegisteredConditions()
    {
        if (self::$registeredConditions === null) {
            foreach (self::$registeredConditionsCallbacks as $callback) {
                $callback($this);
            }
        }

        if (!is_array(self::$registeredConditions)) {
            return [];
        }

        return self::$registeredConditions;
    }

    public static function registerConditions(callable $definitions)
    {
        self::$registeredConditionsCallbacks[] = $definitions;
    }

    public function registerCondition($className, $conditionInfo = null)
    {
        if (self::$registeredConditions === null)
            self::$registeredConditions = [];

        $defaults = [
            'name'        => 'default',
            'label'       => '',
            'description' => '',
        ];

        $condition = array_merge($defaults, $conditionInfo);
        $conditionName = array_get($condition, 'name');
        $condition['className'] = $className;

        self::$registeredConditions[$className] = $condition;
        self::$registeredConditionHints[$conditionName] = $className;
    }
}
