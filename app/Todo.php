<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;

class Todo extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'todos';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = ['for_user_id', 'text', 'priority', 'completed'];
    private static $todoCompletedValueArray = Array('1' => 'Completed', '0' => 'Opened');
    private static $todoPriorityLabelValueArray = Array(1 => 'No', 2 => 'Low', 3 => 'Normal', 4 => 'High', 5 => 'Urgent', 6 => 'Immediate');

    public function for_user()
    {
        return $this->belongsTo('App\User', 'for_user_id');
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($user) {
        });
    }

    public static function getTodoCompletedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$todoCompletedValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getTodoCompletedLabel(string $completed): string
    {
        if ( ! empty(self::$todoCompletedValueArray[$completed])) {
            return self::$todoCompletedValueArray[$completed];
        }
        return '';
    }

    public static function getTodoPrioritiesValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$todoPriorityLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getTodoPriorityLabel(string $priority):string
    {
        if (!empty(self::$todoPriorityLabelValueArray[$priority])) {
            return self::$todoPriorityLabelValueArray[$priority];
        }
        return '';
    }

    public function scopeGetByForUserId($query, $for_user_id = null)
    {
        if (empty($for_user_id)) {
            return $query;
        }
        return $query->where(with(new Todo)->getTable() . '.for_user_id', $for_user_id);
    }

    public function scopeGetByCompleted($query, $completed = null)
    {
        if (!isset($completed) or strlen($completed) == 0) {
            return $query;
        }
        return $query->where(with(new Todo)->getTable().'.completed', $completed);
    }

    public static function getValidationRulesArray($todo_id = null, array $options= []): array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new Todo)->getTable())->ignore($todo_id),
            ],

            'description'  => 'required',
            'completed'    => 'required|in:' . with(new Todo)->getValueLabelKeys(Todo::getTodoCompletedValueArray(false)),
            'for_user_id'   => 'required|integer|exists:' . (with(new User)->getTable()) . ',id',
        ];

        return $validationRulesArray;
    }

}