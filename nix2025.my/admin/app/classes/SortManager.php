<?php
namespace classes;

use \Exception;
use \flight\ActiveRecord;

class SortManager extends ActiveRecord
{

    protected string $tableName      = 'tableUnknown';
    protected string $primaryKey     = 'id';
    protected string $orderingFileld = 'sort_order';

    public function __construct( array $config = [] )
    {

        $this->tableName      = $config['tableName'];
        $this->primaryKey     = $config['primaryKey'];
        $this->orderingFileld = $config['orderingFileld'];

        $database_connection = $config['connection'] ?? \Flight::db();
        parent::__construct( $database_connection, null, ['table' => $this->tableName, 'primaryKey' => $this->primaryKey, 'orderingFileld' => $this->orderingFileld] );

    }

    public function findNextID( int $id ): ?int
    {
        $referenceRecord = $this->find( $id );
        if ( !$referenceRecord ) {
            return null;
        }

        $nextRecord = $this->greaterThan( "{$this->orderingFileld}", $referenceRecord->sort_order )
            ->orderBy( "{$this->orderingFileld} ASC" )
            ->find();

        return $nextRecord ? $nextRecord->{$this->primaryKey} : null;
    }

    public function findPreviousID( int $id ): ?int
    {
        $referenceRecord = $this->find( $id );
        if ( !$referenceRecord ) {
            return null;
        }

        $previousRecord = $this->lessThan( "{$this->orderingFileld}", $referenceRecord->sort_order )
            ->orderBy( "{$this->orderingFileld} DESC" )
            ->find();

        return $previousRecord ? $previousRecord->{$this->primaryKey} : null;
    }

    public function findMinID(): ?int
    {
        $record = $this->orderBy( "{$this->orderingFileld} ASC" )->find();

        return $record ? $record->{$this->primaryKey} : null;
    }

    public function findMaxID(): ?int
    {
        $record = $this->orderBy( "{$this->orderingFileld} DESC" )->find();

        return $record ? $record->{$this->primaryKey} : null;
    }

    public function findMinSortOrder(): ?int
    {
        $record = $this->orderBy( "{$this->orderingFileld} ASC" )->find();

        return $record ? $record->sort_order : null;
    }

    public function findMaxSortOrder(): ?int
    {
        $record = $this->orderBy( "{$this->orderingFileld} DESC" )->find();

        return $record ? $record->sort_order : null;
    }

    public function normalizeSortOrder( int $step = 1 ): bool
    {
        $records = $this->orderBy( "{$this->orderingFileld} ASC" )->findAll();
        if ( !$records ) {
            return false;
        }

        foreach ( $records as $index => $record ) {
            $record->sort_order = $index * $step;
            $record->save();
        }

        return true;
    }

    public function moveRow(
        int    $id,
        string $direction,
        int    $step = 1
    ): bool {

        return $this->performTransaction( function () use ( $id, $direction ) {

            $this->normalizeSortOrder( $step = 1 );

            $currentID  = $id;
            $maxID      = $this->findMaxID();
            $previousID = $this->findPreviousID( $currentID );
            $nextID     = $this->findNextID( $currentID );
            $minID      = $this->findMinID();

            if ( $direction === 'up' && $previousID ) {
                $newSortOrder             = $this->find( $previousID )->sort_order;
                $tmpSortOrder             = $this->find( $currentID )->sort_order;
                $curretRecord             = $this->find( $currentID );
                $curretRecord->sort_order = $newSortOrder;
                $curretRecord->save(); // Явно сохраняем изменения
                $previousRecord             = $this->find( $previousID );
                $previousRecord->sort_order = $tmpSortOrder;
                $previousRecord->save(); // Явно сохраняем изменения
            } else if ( $direction === 'down' && $nextID ) {
                $newSortOrder             = $this->find( $nextID )->sort_order;
                $tmpSortOrder             = $this->find( $currentID )->sort_order;
                $curretRecord             = $this->find( $currentID );
                $curretRecord->sort_order = $newSortOrder;
                $curretRecord->save(); // Явно сохраняем изменения
                $nextRecord             = $this->find( $nextID );
                $nextRecord->sort_order = $tmpSortOrder;
                $nextRecord->save(); // Явно сохраняем изменения
            } else if ( $direction === 'top' ) {
                $newSortOrder             = $this->find( $minID )->sort_order - $step;
                $curretRecord             = $this->find( $currentID );
                $curretRecord->sort_order = $newSortOrder;
                $curretRecord->save(); // Явно сохраняем изменения
            } else if ( $direction === 'bottom' ) {
                $newSortOrder             = $this->find( $maxID )->sort_order + $step;
                $curretRecord             = $this->find( $currentID );
                $curretRecord->sort_order = $newSortOrder;
                $curretRecord->save(); // Явно сохраняем изменения
            }

            return $this->normalizeSortOrder( $step = 1 );
        }, "Ошибка перемещения строки @ moveRow ({$direction})" );
    }

    private function performTransaction(
        callable $callback,
        string   $errorMessage
    ): mixed {
        try {
            $this->beginTransaction();
            $result = $callback();
            $this->commit();

            return $result;
        } catch ( Exception $e ) {
            $this->rollback();
            \Flight::logger()->warning( "[$errorMessage]: " . $e->getMessage() . ' - СОРТИРОВКА ОТМЕНЕНА - ' . pathinfo( $e->getFile() )['basename'] . ':' . $e->getLine() );

            return false;
            // throw $e; // <-- Пробрасываем исключение дальше

        }
    }

}
