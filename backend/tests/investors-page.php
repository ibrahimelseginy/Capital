<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

// Run with: php backend/tests/investors-page.php
// Only connection-local temporary tables are modified by the test cases.
require_once __DIR__ . '/../lib/admin.php';
require_once __DIR__ . '/../lib/investors-page.php';

function check(bool $condition, string $message): void
{
    if (!$condition) throw new RuntimeException($message);
    echo "PASS: $message\n";
}

if (in_array('--fallback', $argv, true)) {
    putenv('DB_PORT=1');
    check(investors_page_public_items() === investors_page_defaults(), 'Serve the complete requested content when MySQL is unavailable');
    exit;
}

$db = admin_db();
foreach (['site_settings', 'investor_page_items', 'admin_audit_log'] as $table) {
    $definition = $db->query("SHOW CREATE TABLE $table")->fetch(PDO::FETCH_NUM)[1];
    // MySQL temporary tables do not support foreign keys; audit rows are isolated too.
    $definition = preg_replace('/,\n\s*CONSTRAINT[^\n]+/', '', $definition);
    $db->exec(preg_replace('/^CREATE TABLE/', 'CREATE TEMPORARY TABLE', $definition));
}

$defaults = investors_page_defaults();
investors_page_install($db);
check(count(investors_page_public_items()) === 16, 'Fresh install publishes the 16 requested content items');
$counts = array_count_values(array_column(investors_page_public_items(), 'section_key'));
check($counts['investor_type'] === 3 && $counts['benefit'] === 3 && $counts['journey_step'] === 3 && $counts['faq'] === 2, 'Requested card and FAQ counts');

$input = ['section_key'=>'investor_type', 'title'=>'بطاقة اختبار', 'subtitle'=>'مسار أهلية', 'icon_key'=>'family', 'sort_order'=>'4', 'is_active'=>'1'];
[$ok] = admin_handle_action('create_investor_page_item', $input);
check($ok && count(investors_page_public_items()) === 17, 'Create and publish an additional investor type');
$id = (string)$db->query("SELECT id FROM investor_page_items WHERE title='بطاقة اختبار'")->fetchColumn();
$input['id'] = $id;
$input['title'] = 'بطاقة معدلة';
$input['sort_order'] = '0';
[$ok] = admin_handle_action('update_investor_page_item', $input);
$types = array_values(array_filter(investors_page_public_items(), static fn($row) => $row['section_key'] === 'investor_type'));
check($ok && $types[0]['title'] === 'بطاقة معدلة', 'Edit and reorder the investor type');
unset($input['is_active']);
[$ok] = admin_handle_action('update_investor_page_item', $input);
check($ok && count(investors_page_public_items()) === 16, 'Hide the investor type from the public API');
[$ok] = admin_handle_action('delete_investor_page_item', ['id'=>$id]);
check($ok, 'Delete the investor type');
[$ok] = admin_handle_action('create_investor_page_item', [...$input, 'primary_url'=>'javascript:alert(1)']);
check(!$ok, 'Reject executable action URLs');

$db->exec('UPDATE investor_page_items SET is_active=0');
investors_page_install($db);
check(investors_page_public_items() === [], 'Do not restore defaults when all content is hidden');
$db->exec('DELETE FROM investor_page_items');
investors_page_install($db);
check(investors_page_public_items() === [], 'Do not reseed intentionally deleted content');

$db->exec('DELETE FROM site_settings');
$columns = array_keys($defaults[0]);
$insert = $db->prepare('INSERT INTO investor_page_items (' . implode(',', $columns) . ') VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')');
foreach (require __DIR__ . '/../database/investors-page-legacy.php' as $row) $insert->execute($row);
investors_page_install($db);
check(count(investors_page_public_items()) === 16, 'Migrate untouched legacy content to the requested brief');
$published = array_column(investors_page_public_items(), null, 'id');
check($published['INVP-JOURNEY-HEAD']['primary_url'] === 'login.php', 'Journey start points to login');
check($published['INVP-FAQ-01']['body'] === $defaults[13]['body'], 'Use the requested FAQ answer');
$db->exec("UPDATE investor_page_items SET is_active=1 WHERE id='INVP-TYPE-04'");
investors_page_install($db);
check(count(investors_page_public_items()) === 17, 'An administrator can re-enable a legacy card after migration');

$db->exec('DELETE FROM site_settings');
$db->exec('DELETE FROM investor_page_items');
foreach (require __DIR__ . '/../database/investors-page-legacy.php' as $row) $insert->execute($row);
$db->exec("UPDATE investor_page_items SET title='ميزة مخصصة',updated_at=NOW() WHERE id='INVP-BEN-04'");
$db->exec("DELETE FROM investor_page_items WHERE id='INVP-TYPE-03'");
$db->exec("UPDATE investor_page_items SET is_active=0 WHERE id='INVP-FAQ-02'");
investors_page_install($db);
$published = array_column(investors_page_public_items(), null, 'id');
check(($published['INVP-BEN-04']['title'] ?? '') === 'ميزة مخصصة', 'Preserve administrator edits during migration');
check(!isset($published['INVP-TYPE-03']) && !isset($published['INVP-FAQ-02']), 'Preserve deleted and hidden content during migration');
echo "All investor page checks passed. Temporary tables are discarded on disconnect.\n";
