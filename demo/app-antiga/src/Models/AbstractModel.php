<?php

declare(strict_types=1);

namespace App\Models;

use Medoo\Medoo;

abstract class AbstractModel implements \JsonSerializable
{
    private Medoo $database;

    private static AbstractModel $instance;

    public function getDatabase(): Medoo
    {
        if (!isset($this->database)) {
            $this->database = new Medoo([
                'type' => $_ENV['DATABASE_TYPE'],
                'host' => $_ENV['DATABASE_HOST'],
                'database' => $_ENV['DATABASE_DATABASE'],
                'username' => $_ENV['DATABASE_USERNAME'],
                'password' => $_ENV['DATABASE_PASSWORD'],
            ]);
        }
        return $this->database;
    }

    protected function model(array $row): AbstractModel
    {
        $model = new static();
        foreach ($row as $field => $value) {
            $method = 'set' . str_replace('_', '', ucfirst($field));
            $model->$method($value);
        }
        return $model;
    }

    public static function getInstance(): AbstractModel
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return AbstractModel[]
     */
    public static function findAll(): array
    {
        $baseModel = self::getInstance();
        $database = $baseModel->getDatabase();

        $data = [];
        $database->select($baseModel->getTableName(), '*', function ($row) use ($baseModel, &$data) {
            $model = $baseModel->model($row);
            $data[] = $model;
        });
        return $data;
    }

    public static function find($id): ?AbstractModel
    {
        $baseModel = self::getInstance();
        $database = $baseModel->getDatabase();

        $model = null;
        $database->select($baseModel->getTableName(), '*', ['id' => $id], function ($row) use ($baseModel, &$model) {
            $model = $baseModel->model($row);
        });
        return $model;
    }

    public function save(): void
    {
        $database = $this->getDatabase();
        $database->insert($this->getTableName(), $this->jsonSerialize());
        $id = (int) $database->id();
        if ($id < 1) {
            throw new \Exception('Erro ao salvar item');
        }
        $this->setId($id);
    }

    abstract public function getId(): ?int;

    abstract public function setId(int $id): void;

    abstract protected function getTableName(): string;
}
