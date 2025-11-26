<?php
Flight::group( '/admin', function () {

    Flight::post( '/action/sub/@sub/@action(/@id)(/page/@page(/limit/@lim))', function (
        $sub,
        $action,
        $id = -1,
        $page = 1,
        $lim = DEFAULT_ITEMS_PER_PAGE
    ) {

        if ( !isCorrectFlightItem( $sub, 'arrSubs' ) ) {
            throw new Exception( 'Неправильная страница' );
        }

        Flight::set( 'current_sub', $sub );
        Flight::set( 'current_page', $page ?? 1 );
        Flight::set( 'items_per_page', $lim ?? DEFAULT_ITEMS_PER_PAGE );

        $requestBody = json_decode( Flight::request()->getBody(), true ) ?: [];
        if ( !$requestBody ) {
            throw new Exception( "Неверный запрос: $sub/$action(/$id)(/page/$page(/limit/$lim))" );
        }
        $operation = strtolower( $action );

        include_once __SUBS__ . DIRECTORY_SEPARATOR . Flight::get( 'current_sub' ) . '_sub.php';

        $operationResult         = compact( 'sub', 'action', 'operation', 'id', 'page', 'lim' );
        $operationResult['date'] = date( 'Y-m-d H:i:s' );

        try {
            $status = 304;

            if ( in_array( $operation, ['insert', 'addnew'] ) && $id <= 0 ) {
                $operationResult['transactionResult'] = $records->insertRecord( $requestBody['rowData'] );
            } else {
                $operationsMap = [
                    'duplicate'  => fn()  => $records->duplicateRecord( $id ),
                    'clone'      => fn()      => $records->duplicateRecord( $id ),
                    'remove'     => fn()     => $records->deleteRecord( $id ),
                    'delete'     => fn()     => $records->deleteRecord( $id ),
                    'update'     => fn()     => $records->updateRecord( $id, $requestBody['rowData'] ?? [] ),
                    'saverow'    => fn()    => $records->updateRecord( $id, $requestBody['rowData'] ?? [] ),

                    'sorttop'    => fn()    => $records->sortManager->moveRow( $id, 'top' ),
                    'sortup'     => fn()     => $records->sortManager->moveRow( $id, 'up' ),
                    'sortdown'   => fn()   => $records->sortManager->moveRow( $id, 'down' ),
                    'sortbottom' => fn() => $records->sortManager->moveRow( $id, 'bottom' ),
                    'fixsort'    => fn()    => $records->sortManager->normalizeSortOrder(),

                    ######## All
                    'saveall'    => fn()    => $records->saveAllTableData( $requestBody['allData'] ?? [] ),
                ];

                if ( !isset( $operationsMap[$operation] ) ) {
                    throw new Exception( "Неприемлемая операция: $operation" );
                }
                $operationResult['transactionResult'] = $operationsMap[$operation]();

            }

            if ( !$operationResult['transactionResult'] ) {
                throw new Exception( "Ошибка операции с БД: 'sub' => `$sub`, 'action' => `$action`, 'id' => `$id`" );
            }

            if ( is_array( $operationResult['transactionResult'] ) && in_array( $operation, ['insert', 'addnew', 'duplicate', 'clone'] ) ) {
                $operationResult['transactionData']   = $operationResult['transactionResult'];
                $operationResult['transactionResult'] = true;
                $status                               = 201;
            } else {
                $status = 200;
            }

            $operationResult['redirect'] = DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . Flight::get( 'current_sub' ) . DIRECTORY_SEPARATOR . 'page' . DIRECTORY_SEPARATOR . Flight::get( 'current_page' ) . DIRECTORY_SEPARATOR . 'limit' . DIRECTORY_SEPARATOR . Flight::get( 'items_per_page' ) . DIRECTORY_SEPARATOR . Flight::get( 'sort_field' ) . DIRECTORY_SEPARATOR . Flight::get( 'sort_direction' );

        } catch ( Exception $ex ) {
            $status       = 501;
            $errorMessage = $ex->getMessage() . ' _ ' . pathinfo( $ex->getFile() )['basename'] . ':' . $ex->getLine();
            Flight::logger()->error( $errorMessage, ['error' => $ex->getMessage(), 'file' => pathinfo( $ex->getFile() )['basename'] . ':' . $ex->getLine()] );
            Flight::json( ['error' => $ex->getMessage(), 'file' => pathinfo( $ex->getFile() )['basename'] . ':' . $ex->getLine()], $status, JSON_PRETTY_PRINT );
        } finally {

            $operationResult['code']               = $status;
            $operationResult['status_description'] = \CODES [$status];

            $operationResult['toast'] = ( ( $status === 200 ) || ( $status === 201 ) )
            ? "Ok! операция: $action " . ( ( isset( $id ) ) ? "/ 'id' => `$id`" : '' )
            : "Problem! операция: $action " . ( ( isset( $id ) ) ? "/ 'id' => `$id`" : '' ) . ' [' . $operationResult['code'] . '] ' . $operationResult['status_description'];
            Flight::jsonHalt( $operationResult, $status, JSON_PRETTY_PRINT );
        }
    } );

} );
