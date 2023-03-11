<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelEventLogger
 * @package App\Traits
 *
 *  Automatically Log Add, Update, Delete events of Model.
 */
trait ModelEventLogger
{

    /**
     * Automatically boot with Model, and register Events handler.
     */
    protected static function bootModelEventLogger()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $reflect = new \ReflectionClass($model);
                    return Log::create([
                        'admin_id' => auth()->id(),
                        'user_id'     => auth()->id(),
                        'model_id' => isset($model->id)? $model->id : null,
                        'model' => get_class($model),
                        'action'      => static::getActionName($eventName),
                        'description' => ucfirst($eventName) . " a " . $reflect->getShortName() . '.',
                        'before_details'     => json_encode($model->getOriginal()),
                        'after_details'     => json_encode($model->getDirty()),
                        'ip_address'     => $_SERVER['REMOTE_ADDR']
                    ]);
                } catch (\Exception $e) {
                    return true;
                }
            });
        }
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created',
            'updated',
            'deleted',
        ];
    }

    /**
     * Return Suitable action name for Supplied Event
     *
     * @param $event
     * @return string
     */
    protected static function getActionName($event)
    {
        switch (strtolower($event)) {
            case 'created':
                return 'create';
                break;
            case 'updated':
                return 'update';
                break;
            case 'deleted':
                return 'delete';
                break;
            default:
                return 'unknown';
        }
    }
}
