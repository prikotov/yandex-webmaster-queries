<?php

require_once __DIR__ . '/../yandex-webmaster-core/WebmasterClient.php';

WebmasterClient::checkGitignore();
$config = WebmasterClient::loadConfig();

function parseArgs(array $argv): array
{
    $result = [
        'dateFrom' => date('Y-m-d', strtotime('-30 days')),
        'dateTo' => date('Y-m-d'),
        'sort' => 'impressions',
        'order' => 'desc',
        'limit' => null,
        'host' => null
    ];
    
    $i = 1;
    while ($i < count($argv)) {
        $arg = $argv[$i];
        
        if (in_array($arg, ['--sort', '-s']) && isset($argv[$i + 1])) {
            $result['sort'] = $argv[++$i];
        } elseif (in_array($arg, ['--order', '-o']) && isset($argv[$i + 1])) {
            $result['order'] = $argv[++$i];
        } elseif (in_array($arg, ['--limit', '-l']) && isset($argv[$i + 1])) {
            $result['limit'] = (int)$argv[++$i];
        } elseif (in_array($arg, ['--host', '-h']) && isset($argv[$i + 1])) {
            $result['host'] = $argv[++$i];
        } elseif (!str_starts_with($arg, '-') && strlen($arg) === 10 && strpos($arg, '-') !== false) {
            if (!$result['dateFrom'] || $result['dateFrom'] === date('Y-m-d', strtotime('-30 days'))) {
                $result['dateFrom'] = $arg;
            } else {
                $result['dateTo'] = $arg;
            }
        }
        $i++;
    }
    
    return $result;
}

function getOrderBy(string $sort): string
{
    $map = [
        'impressions' => 'TOTAL_SHOWS',
        'clicks' => 'TOTAL_CLICKS',
        'ctr' => 'TOTAL_SHOWS',
        'position' => 'TOTAL_SHOWS'
    ];
    
    return $map[$sort] ?? 'TOTAL_SHOWS';
}

$args = parseArgs($argv);

$hostId = $args['host'] ?? $config['host_id'] ?? null;

$client = new WebmasterClient(
    $config['client_id'],
    $config['client_secret'],
    $hostId
);

$orderBy = getOrderBy($args['sort']);
$data = $client->getPopularQueries($args['dateFrom'], $args['dateTo'], 500, $orderBy);

$queries = [];
foreach ($data['queries'] ?? [] as $item) {
    $indicators = $item['indicators'] ?? [];
    $shows = (int)($indicators['TOTAL_SHOWS'] ?? 0);
    $clicks = (int)($indicators['TOTAL_CLICKS'] ?? 0);
    $ctr = $shows > 0 ? round(($clicks / $shows) * 100, 2) : 0;
    
    $queries[] = [
        'query' => $item['query_text'] ?? '',
        'impressions' => $shows,
        'clicks' => $clicks,
        'ctr' => $ctr,
        'position' => round($indicators['AVG_SHOW_POSITION'] ?? 0, 1)
    ];
}

$sortField = $args['sort'];
$order = $args['order'];

usort($queries, function($a, $b) use ($sortField, $order) {
    $cmp = $a[$sortField] <=> $b[$sortField];
    return $order === 'desc' ? -$cmp : $cmp;
});

if ($args['limit'] !== null && $args['limit'] > 0) {
    $queries = array_slice($queries, 0, $args['limit']);
}

$reportPath = WebmasterClient::createReportDir();
$timestamp = WebmasterClient::getFileTimestamp();
$hostId = $client->getHostId();

echo "\n  Папка отчета: webmaster_reports/" . basename($reportPath) . "\n";
echo "  Сайт: $hostId\n";
echo "  Период: {$args['dateFrom']} — {$args['dateTo']}\n";
echo "  Сортировка: {$args['sort']} ({$args['order']})\n";
if ($args['limit'] !== null) {
    echo "  Лимит: топ {$args['limit']}\n";
}
echo "\n";

WebmasterClient::saveCsv($queries, "$reportPath/queries_$timestamp.csv");
WebmasterClient::saveMarkdown($queries, "$reportPath/queries_$timestamp.md", "Поисковые запросы", $args['dateFrom'], $args['dateTo']);

echo "  Создано файлов:\n";
echo "    - queries_$timestamp.csv\n";
echo "    - queries_$timestamp.md\n";
echo "\n  Найдено запросов: " . count($queries) . "\n";
