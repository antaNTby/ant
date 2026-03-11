<?php

declare ( strict_types = 1 );

namespace app\controllers;

use flight\database\SimplePdo;
use flight\Engine;

class BaseSubController
{
    protected Engine $app;
    protected SimplePdo $db;

    public string $subName;
    public string $table;

    public function __construct( Engine $app )
    {
        $this->app = $app;
        $this->db  = $app->db();
    }

    /**
     * GET: Получить все записи
     */
    public function getAll(): void
    {
        $data = $this->db->fetchAll( "SELECT * FROM {$this->table}" );
        $this->app->json( $data );
    }

    /**
     * GET: Получить одну запись по ID
     */
    public function getById( int $id ): void
    {
        $item = $this->db->fetchRow( "SELECT * FROM {$this->table} WHERE id = ?", [$id] );

        if ( !$item ) {
            $this->app->halt( 404, json_encode( ['error' => 'Not found'] ) );
        }

        $this->app->json( $item );
    }

    /**
     * POST: Создать новую запись
     */
    public function create(): void
    {
        $data = $this->app->request()->data->getData();

        // SimplePdo позволяет вставлять данные передавая массив
        $this->db->insert( $this->table, $data );
        $newId = $this->db->lastInsertId();

        $this->app->json( ['status' => 'success', 'id' => $newId], 201 );
    }

    /**
     * PUT: Обновить запись
     */
    public function update( int $id ): void
    {
        $data = $this->app->request()->data->getData();

        $this->db->update( $this->table, $data, ['id' => $id] );

        $this->app->json( ['status' => 'updated'] );
    }

    /**
     * DELETE: Удалить запись
     */
    public function delete( int $id ): void
    {
        $this->db->run( "DELETE FROM {$this->table} WHERE id = ?", [$id] );

        $this->app->json( ['status' => 'deleted'] );
    }
}
