<?php
namespace classes;

use Exception;

/**
 *
 *
 * Класс ActiveRecord обычно единственное число.
 *
 * @property int    $id
 * @property string $primaryKey
 * @property string $sortOrder
 */
abstract class StandartSub extends \flight\ActiveRecord
{

    public SortManager $sortManager;

    protected string $tableName      = 'tableUnknown';
    protected string $primaryKey     = 'id';
    protected string $orderingFileld = 'sort_order';

    protected array $defaultValues = [];
    protected string $fieldsList   = '';
    private array $ColumnFields    = [];
    private array $ColumnTypes     = [];

    protected array $subRules      = [];
    protected array $specificRules = [];

    public function __construct( array $config = [] )
    {
        $database_connection = $config['connection'] ?? \Flight::db();
        parent::__construct( $database_connection, null, ['table' => $this->tableName] );

        $this->fetchColumnMetadata( $database_connection );
        $this->fieldsList  = implode( ', ', array_filter( $this->ColumnFields, fn( $f ) => $f !== $this->primaryKey ) );
        $this->sortManager = new SortManager( [
            'tableName'      => $this->tableName,
            'primaryKey'     => $this->primaryKey,
            'orderingFileld' => $this->orderingFileld,
        ] );

        $this->defaultValues['update_time'] = date( 'Y-m-d H:i:s' );
    }

    public function getTableName(): string
    {return $this->tableName;}
    public function getPrimaryKey(): string
    {return $this->primaryKey;}
    public function getOrderBy(): string
    {return $this->orderingFileld;}

    public function setTableName( string $new ): self
    {
        $this->tableName = $new;

        return $this;
    }
    public function setPrimaryKey( string $new ): self
    {
        $this->primaryKey = $new;

        return $this;
    }
    public function setOrderBy( string $new ): self
    {
        $this->orderingFileld = $new;

        return $this;
    }

    public function generateConfigForTableDataSmartyControl(
        string $key,
        array  $params
    ): array {
        $rules  = [];
        $config = [];
        // Merge specific rules with defaults
        $rules = array_merge( $this->subRules, ['key' => $key], $this->specificRules[$key] ?? [] );
        // jlog( $rules );
        $config = array_merge( $params, $rules );
        // jlog( $config );

        return $config;

    }

    public function sortBy(
        int     $page = 1,
        int     $lim = null,
        bool    $reverse = false,
        ?string $sortField = null
    ): array {
        if ( !in_array( $sortField, $this->ColumnFields ) ) {
            $sortField = 'sort_order';
            \Flight::set( 'sortField', 'sort_order' );
            \Flight::set( 'sortDirection', 'ASC' );

            $ex = throw new \Exception( "Поле сортировки `$sortField` не найдено в таблице." );
            $this->logError( "Поле сортировки `$sortField` не найдено в таблице.", $ex );
        }

        $limit         = $lim ?? 16;
        $offset        = ( $page - 1 ) * $limit;
        $offset        = ( $offset > 0 ) ? $offset : 0;
        $sortDirection = ( $reverse ) ? ' DESC' : ' ASC';

        return array_map(
            fn( $item ) => $item->data,
            $this->orderBy( "$sortField $sortDirection" )
                ->limit( $offset, $limit )
                ->findAll()
        );

    }

    public function viewPage(
        int     $page = 1,
        int     $limit = null,
        bool    $reverse = false,
        ?string $sortField = null
    ): array {
        $field = $sortField ?? $this->orderingFileld;

        return $this->sortBy( $page, $limit, $reverse, $field );
    }

    public function showAll(
        bool    $reverse = false,
        ?string $sortField = null
    ): array {
        $page   = 1;
        $limit  = null;
        $offset = null;

        $field         = $sortField ?? $this->orderingFileld;
        $sortDirection = ( $reverse ) ? ' DESC' : ' ASC';

        // return $this->getSortedRecords( $reverse, $offset, $limit );

        return $this->sortBy( $page, $limit, $reverse, $field );

    }

#################
#### C R U D ####
#################

    public function duplicateRecord( int $id ): array | bool
    {
        return $this->performTransaction( function () use ( $id ) {

            if ( !in_array( $this->primaryKey, $this->ColumnFields ) ) {
                throw new Exception( 'Некорректный первичный ключ.' );
            }

            $record = $this->find( $id );
            if ( !$record->data ) {
                throw new Exception( "Запись id:$id не найдена." );
            }
            $sql = "INSERT INTO {$this->tableName} ({$this->fieldsList})
                    SELECT {$this->fieldsList} FROM {$this->tableName}
                    WHERE {$this->primaryKey} = :id";

            $this->execute( $sql, ['id' => $id] );

            return ['newID' => $this->databaseConnection->lastInsertId(), 'sourceID' => $id];
        }, 'Ошибка дублирования записи @ function duplicateRecord' );
    }

    public function deleteRecord( int $id ): bool
    {
        return $this->performTransaction( function () use ( $id ) {
            $record = $this->find( $id );
            if ( !$record->data ) {
                throw new Exception( "Запись id:$id не найдена." );
            }
            if ( !$record->delete() ) {
                throw new Exception( 'Запись не удалена.' );
            }

            return true;
        }, 'Ошибка удаления записи @ function deleteRecord' );
    }

    public function updateRecord(
        int   $id,
        array $data
    ): bool {

        // dd( $data );

        return $this->performTransaction( function () use ( $id, $data ) {
            $record = $this->find( $id );

            if ( !$data ) {
                throw new Exception( 'Нет данных для обновления.' );
            }
            if ( !$record->data ) {
                throw new Exception( "Запись id:$id не найдена." );
            }
            foreach ( $data as $key => $value ) {
                $record->$key = $value;
            }
            if ( !$record->save() ) {
                throw new Exception( 'Ошибка сохранения записи.' );
            }

            return true;
        }, 'Ошибка обновления записи @ function updateRecord' );
    }

    public function saveAllTableData(
        array $dataAll
    ): bool {
        return $this->performTransaction( function () use ( $dataAll ) {

            if ( !$dataAll ) {
                throw new Exception( 'Нет данных для сохранения.' );
            }
            foreach ( $dataAll as $key => $row_data ) {
                $id     = $row_data["$this->primaryKey"];
                $record = $this->find( $id );
                if ( !$record->data ) {
                    throw new Exception( "Запись id:$id не найдена." );
                }
                foreach ( $row_data as $key => $value ) {
                    if ( $record->$key != $value ) {
                        \Flight::logger()->info( "[$id][$key] value changed from {$record->$key} to {$value}" );
                    }
                    $record->$key = $value;
                }
                if ( !$record->save() ) {
                    throw new Exception( "Ошибка сохранения записи. $key => $value" );
                }
            }

            return true;
        }, 'Ошибка обновления записи @ function saveAllTableData' );

        foreach ( $row_data as $key => $value ) {

            $record->$key = $value;
        }
    }

    public function insertRecord( array $data ): array | bool
    {
        return $this->performTransaction( function () use ( $data ) {

            if ( !$data ) {
                throw new Exception( 'Нет данных для добавления.' );
            }

            $data = $this->removePrefixForColumnFieds( $data ?? [] );

            foreach ( $data as $key => $value ) {
                $this->$key = $value;
            }
            $this->insert();

            return ['newID' => $this->getLastInsertId()];
        }, 'Ошибка вставки записи @ function insertRecord' );
    }

#################
## end C R U D ##
#################

    private function performTransaction(
        callable $callback,
        string   $errorMessage
    ): mixed {
        try {
            $this->beginTransaction();
            $result = $callback();
            $this->commit();

            return $result;
        } catch ( \Throwable $e ) {
            $this->rollback();
            $this->logError( $errorMessage, $e );

            return false;
        }
    }

    private function fetchColumnMetadata( $databaseConnection ): void
    {
        try {
            $rows = $databaseConnection
                ->query( "SHOW COLUMNS FROM {$this->tableName};" )
                ->fetchAll( \PDO::FETCH_ASSOC );

            $this->ColumnFields = array_column( $rows, 'Field' ) ?: [];
            $this->ColumnTypes  = array_map( fn( $row ) => strtok( $row['Type'], '(' ), $rows ) ?: [];

        } catch ( \Exception $e ) {
            $this->logError( "[Ошибка получения колонок таблицы {$this->tableName}]: {$e->getMessage()} _ " . pathinfo( $e->getFile() )['basename'] . ':' . $e->getLine() );
        }
    }

    private function getSortedRecords(
        bool $reverse = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $sort  = $this->orderingFileld . ( $reverse ? ' DESC' : ' ASC' );
        $query = $this->orderBy( $sort );

        if ( !is_null( $offset ) && !is_null( $limit ) ) {
            $query = $query->limit( $offset, $limit );
        }

        return array_map( fn( $item ) => $item->data, $query->findAll() );
    }

    private function logError(
        string     $message,
        \Throwable $e
    ): void {
        \Flight::logger()->error( "[ActiveRecordError] $message: {$e->getMessage()} _ " . pathinfo( $e->getFile() )['basename'] . ':' . $e->getLine() );
    }

    private function removePrefixForColumnFieds(
        array  $array,
        string $prefix = 'add_'
    ): array {
        // Удаляем указанный префикс из ключей массива
        $data = array_map( fn( $key ) => preg_replace( "/^{$prefix}/", '', $key ), array_keys( $array ) );
        $data = array_combine( $data, array_values( $array ) );
        // Оставляем только те поля, которые есть в таблице
        $validFields = array_intersect( array_keys( $data ), $this->ColumnFields );

        return array_filter( $data, fn( $key ) => in_array( $key, $validFields ), ARRAY_FILTER_USE_KEY );
    }

#######################
#######################
#######################
#######################
} //class
