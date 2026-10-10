<?php

namespace App\Models;

use CodeIgniter\Model;

class LandingSettingModel extends Model
{
    protected $table = 'landing_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'setting_key',
        'setting_value',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public static function getMap(): array
    {
        $rows = (new self())->findAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['setting_key']] = $row['setting_value'];
        }
        return $map;
    }

    public static function getVal(string $key, $default = null)
    {
        $row = (new self())->where('setting_key', $key)->first();
        return $row ? $row['setting_value'] : $default;
    }

    public static function setVal(string $key, string $value): void
    {
        $model = new self();
        $row = $model->where('setting_key', $key)->first();
        if ($row) {
            $model->update($row['id'], ['setting_value' => $value]);
        } else {
            $model->insert(['setting_key' => $key, 'setting_value' => $value]);
        }
    }
}
