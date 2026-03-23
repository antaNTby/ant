<?php
namespace classes;

use Smarty\Smarty;

class SimplePaginator
{
    private int $base = 1;
    private int $itemsPerPage;
    private int $totalItemCount;
    private int $currentPage;
    private int $offset;
    private string|null $sortField     = 'sort_order';
    private string|null $sortDirection = 'ASC';
    private int $totalPageCount;
    private string $subName = '';

    private array $firstPages           = [];
    private array $middlePages          = [];
    private array $lastPages            = [];
    private array $neighbourPages       = [];
    private array $sameSegmentPages     = [];
    private array $oppositeSegmentPages = [];

    public array $navigatorData = [];
    private Smarty $smarty;

    public function __construct(
        Smarty $smarty,
        array  $config = []
    ) {
        $this->smarty         = $smarty;
        $this->subName        = $config['subName'] ?? 'thisSub';
        $this->totalItemCount = $config['total_item_count'] ?? 0;
        $this->itemsPerPage   = $config['items_per_page'] ?? DEFAULT_ITEMS_PER_PAGE;
        $this->currentPage    = $config['current_page'] ?? $this->base;
        $this->sortField      = $config['sort_field'] ?? 'sort_order';
        $this->sortDirection  = $config['sort_direction'] ?? 'ASC';

        $this->totalPageCount = (int) ceil( $this->totalItemCount / $this->itemsPerPage );
        // $this->currentPage    = max( 1, min( $this->currentPage, $this->totalPageCount ) );
        $this->offset = max( 0, ( $this->currentPage - 1 ) * $this->itemsPerPage );

        $this->firstPages     = $this->getFirstPages( 3 );
        $this->middlePages    = $this->getMiddlePages( 3 );
        $this->lastPages      = $this->getLastPages( 3 );
        $this->neighbourPages = $this->getNeighbourPages( 3 );

        $pages = $this->justifyPages();

        $this->navigatorData = [
            'current_page'   => $this->currentPage,
            'items_per_page' => $this->itemsPerPage,
            'total_pages'    => $this->totalPageCount,
            'total_items'    => $this->totalItemCount, // <-- добавили
            'has_previous'   => $this->currentPage > 1,
            'has_next'       => $this->currentPage < $this->totalPageCount,
            'previous_page'  => max( 1, $this->currentPage - 1 ),
            'next_page'      => min( $this->totalPageCount, $this->currentPage + 1 ),
            'paginator'      => $pages,
        ];

        // 📦 сразу назначаем данные в шаблон
        $this->smarty->assign( 'nav_links', $this->buildSmartyPagination( $this->navigatorData ) );
    }

    private function getFirstPages( int $size ): array
    {
        return range( 1, min( $size, $this->totalPageCount ) );
    }

    private function getLastPages( int $size ): array
    {
        return range( max( 1, $this->totalPageCount - $size + 1 ), $this->totalPageCount );
    }

    private function getMiddlePages( int $size ): array
    {
        if ( $this->totalPageCount <= $size ) {
            return range( 1, $this->totalPageCount );
        }

        $middle = (int) floor( $this->totalPageCount / 2 );
        $start  = max( 1, $middle - (int) floor( $size / 2 ) );
        $end    = min( $this->totalPageCount, $start + $size - 1 );

        return range( $start, $end );
    }

    private function getNeighbourPages( int $size ): array
    {
        $start = max( 1, $this->currentPage - $size );
        $end   = min( $this->totalPageCount, $this->currentPage + $size );

        return range( $start, $end );
    }

    private function calculateAdditionalPages(
        int $start,
        int $end,
        int $size = 1
    ): array {
        if ( $end <= $start ) {
            return [];
        }

        $midStart = (int) floor( $start + ( $end - $start ) / 2 - $size );
        $midEnd   = (int) floor( $start + ( $end - $start ) / 2 + $size );

        return range( $midStart, $midEnd );
    }

    private function justifyPages(): array
    {
        if ( $this->currentPage < min( $this->middlePages ) ) {
            $this->oppositeSegmentPages = $this->calculateAdditionalPages( max( $this->middlePages ), min( $this->lastPages ) );
            $this->sameSegmentPages     = $this->calculateAdditionalPages( max( $this->firstPages ), min( $this->middlePages ) );
        } elseif ( $this->currentPage > max( $this->middlePages ) ) {
            $this->oppositeSegmentPages = $this->calculateAdditionalPages( max( $this->firstPages ), min( $this->middlePages ) );
            $this->sameSegmentPages     = $this->calculateAdditionalPages( max( $this->middlePages ), min( $this->lastPages ) );
        }

        $merged = array_merge(
            [$this->currentPage],
            $this->firstPages,
            $this->sameSegmentPages,
            $this->neighbourPages,
            $this->oppositeSegmentPages,
            $this->middlePages,
            $this->lastPages
        );

        $merged   = array_unique( $merged );
        $filtered = array_filter( $merged, fn( $p ) => $p > 0 && $p <= $this->totalPageCount );
        sort( $filtered );

        return $filtered;
    }

    private function buildPageLink(
        int  $page,
        bool $isCurrent = false
    ): array {

        if ( $page == -1 ) {
            return [
                'href' => "/admin/sub/{$this->subName}/show_all/sort/{$this->sortField}/{$this->sortDirection}",
            ];
        }
        if ( $page == -2 ) {
            return [
                'href' => "/admin/sub/{$this->subName}/page/{$this->base}/limit/" . DEFAULT_ITEMS_PER_PAGE . "/sort/{$this->sortField}/{$this->sortDirection}",
            ];
        }

        $res = [
            'href'    => "/admin/sub/{$this->subName}/page/{$page}/limit/{$this->itemsPerPage}/sort/{$this->sortField}/{$this->sortDirection}",
            'content' => str_pad( $page, 2, '0', STR_PAD_LEFT ),
            'active'  => $isCurrent,
        ];

        return $res;
    }

    public function buildSmartyPagination( array $data ): array
    {
        $result  = [];
        $pages   = $data['paginator'];
        $current = $data['current_page'];

        // Previous
        $result['previous'] = $data['has_previous']
        ? ['href' => $this->buildPageLink( $data['previous_page'] )['href']]
        : ['href' => '#', 'disabled' => true];

        // Page entries
        for ( $i = 0; $i < count( $pages ); $i++ ) {
            $isCurrent = (int) $pages[$i] === (int) $current;
            $entry     = $this->buildPageLink( $pages[$i], $isCurrent );

            $result['data'][] = $entry;

            // Add " ..." if there's a gap
            if ( isset( $pages[$i + 1] ) && ( $pages[$i + 1] - $pages[$i] ) > 1 ) {
                $result['data'][] = [
                    'href'     => '#',
                    'content'  => '<i class="bi bi - three - dots"></i>',
                    'disabled' => true,
                ];
            }
        }

        // Next
        $result['next'] = $data['has_next']
        ? ['href' => $this->buildPageLink( $data['next_page'] )['href']]
        : ['href' => '#', 'disabled' => true];

        $result['current_page']   = $data['current_page'];
        $result['items_per_page'] = $data['items_per_page'];
        $result['total_items']    = $data['total_items'];
        $result['total_pages']    = $data['total_pages'];

        if ( $data['total_items'] <= 256 ) {

            $isShowAll          = ( $data['current_page'] == -1 );
            $result['show_all'] = [
                'href'    => $isShowAll
                ? $this->buildPageLink( -2, false )['href']
                : $this->buildPageLink( -1, false )['href'],
                'content' => $isShowAll ? 'Разбить на страницы' : 'Показать всё',
            ];

        }

        return $result;
    }

    public function getNavigatorData(): array
    {
        return $this->navigatorData;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
