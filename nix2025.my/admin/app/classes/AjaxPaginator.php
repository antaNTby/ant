<?php
require_once __DIR__ . '/app/classes/SimplePaginator.php';

use classes\Paginator;

$html = <<<HTML
<ul class="pagination justify-content-center" data-pagination-container>
  ...
  <a class="page-link pagination-ajax" href="/admin/sub/news/page/2" data-page="2">2</a>
  ...
</ul>
HTML;
$js = <<<JS
document.addEventListener('click', function (e) {
    const link = e.target.closest('.pagination-ajax');
    if (link) {
        e.preventDefault();
        const targetUrl = link.getAttribute('href');
        fetch(targetUrl)
            .then(res => res.text())
            .then(html => {
                document.querySelector('#content').innerHTML = html;
                history.pushState(null, '', targetUrl);
            });
    }
});
JS;

Flight::route( '/admin/sub/@sub/page/@page/limit/@limit', function (
    $sub,
    $page,
    $limit
) {
    $totalRecords = get_total_records_for( $sub ); // <-- получи список записей
    $smarty       = Flight::get( 'smarty' );       // если уже зарегистрирован

    $paginator = new Paginator( $smarty, [
        'current_sub'    => $sub,
        'totalRecords'   => count( $totalRecords ),
        'items_per_page' => (int) $limit,
        'currentPage'    => (int) $page,
    ] );

    $records = array_slice( $totalRecords, $paginator->getOffset(), $limit );

    $smarty->assign( 'records', $records );
    $smarty->display( 'pages/' . $sub . '.tpl' );
} );

###############################################################
