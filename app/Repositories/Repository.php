<?php

/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/12/18
 * Time: 12:25 PM
 */

namespace App\Repositories;


abstract class Repository
{
    /**
     * Stores the model used for repository
     * @var Eloquent object
     */
    protected $model;

    public function all()
    {
        return $this->model->all();
    }

    public function allActive()
    {
        return $this->model->where('is_active', 1)->get();
    }

    public function allActiveWith($with = [])
    {
        return $this->model->where('is_active', 1)->with($with)->get();
    }

    public function paginate($items = 5)
    {
        return $this->model->paginate($items);
    }

    public function paginateActive($items = 5)
    {
        return $this->model->where('is_active', 1)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($items);
    }

    public function allWith($with = false, $orderBy = false)
    {
        $model = $this->model;
        if ($with) {
            $model = $model->with($with);
        }
        if ($orderBy) {
            $model = $model->orderBy($orderBy);
        }
        return $model->get();
    }

    public function first()
    {
        return $this->model->first();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function with($with = [])
    {
        return $this->model->with($with);
    }

    public function findWith($id, $with = [])
    {
        return $this->model->with($with)->find($id);
    }

    public function findBySlugWith($slug, $with = [])
    {
        return $this->model->with($with)
            ->where('slug', $slug)
            ->first();
    }

    public function findByWith($field, $value, $with = [])
    {
        return $this->model->with($with)
            ->where($field, $value)
            ->first();
    }

    public function create($inputs)
    {
        return $this->model->create($inputs);
    }

    public function update($id, $inputs)
    {
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }

    public function changeStatus($id, $status)
    {
        $update = $this->model->findOrFail($id);
        $inputs['is_active'] = (int) $status;
        $update->fill($inputs)->save();
        return $update;
    }

    public function delete()
    {
        return $this->model->delete();
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    public function orderBy($prop, $type = null)
    {
        return $this->model->orderBy($prop, $type);
    }

    public function where($column, $opOrVal, $value = "")
    {
        return $this->model->where($column, $opOrVal, $value);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->model->orWhere($column, $operator, $value);
    }

    public function orWhereBetween($column, $range)
    {
        return $this->model->orWhereBetween($column, $range);
    }

    public function whereBetween($column, $range)
    {
        return $this->model->whereBetween($column, $range);
    }

    public function where_array($array)
    {
        return $this->model->where($array);
    }

    public function whereIn($column, $array)
    {
        return $this->model->whereIn($column, $array);
    }

    public function whereNull($column)
    {
        return $this->model->whereNull($column);
    }

    public function firstOrNew($id)
    {
        return $this->model->firstOrNew(array('id' => $id));
    }

    public function model()
    {
        return $this->model;
    }

    public function findOrFail($id = '')
    {
        return $this->model->findOrFail($id);
    }

    public function get()
    {
        return $this->model->get();
    }

    /**
     * Checks if the given key exists in given config array and returns the val if exists
     * @param array $config list of config values
     * @param string $key the key that may exist in config array
     * @param string $default default value
     * @return string
     */
    protected function getConfigValue($config, $key, $default = "")
    {
        $value = $default;
        if (isset($config[$key])) {
            $value = $config[$key];
        }
        return $value;
    }

    public function get_all($active = false, $limit = '', $exclude = array())
    {
        $model = $this->model;
        if ($active) {
            $model = $model->where('is_active', '=', 1);
        }
        if ($limit) {
            $model = $model->take($limit);
        }
        if (!empty($exclude)) {
            $model = $model->whereNotIn('id', $exclude);
        }
        return $model->get();
    }

    public function findBy($field, $value)
    {
        return $this->model()->where($field, $value)->first();
    }
}
