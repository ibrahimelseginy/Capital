<?php
declare(strict_types=1);

/** One source for both first-install content and the public fallback. */
function investors_page_defaults(): array
{
    static $items = null;
    if ($items === null) {
        $items = json_decode(file_get_contents(__DIR__ . '/../assets/data/investors-page.json'), true, 512, JSON_THROW_ON_ERROR);
    }
    return $items;
}

/** Apply the new brief once; preserve edits, deletions and custom cards. */
function investors_page_install(PDO $database): void
{
    $version = 'investors_page_content_20260831';
    $check = $database->prepare('SELECT setting_value FROM site_settings WHERE setting_key=?');
    $check->execute([$version]);
    if ($check->fetchColumn() !== false) return;

    $database->beginTransaction();
    try {
        // Claim the migration inside the same transaction as its content changes.
        $claim = $database->prepare('INSERT IGNORE INTO site_settings (setting_key,setting_value) VALUES (?,?)');
        $claim->execute([$version, '1']);
        if ($claim->rowCount() === 0) { $database->commit(); return; }

        $defaults = investors_page_defaults();
        $columns = array_keys($defaults[0]);
        $existing = $database->query('SELECT * FROM investor_page_items FOR UPDATE')->fetchAll(PDO::FETCH_ASSOC);
        if (!$existing) {
            $insert = $database->prepare('INSERT INTO investor_page_items (' . implode(',', $columns) . ',is_active) VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ',1)');
            foreach ($defaults as $item) $insert->execute(array_values($item));
        } else {
            $legacy = [];
            foreach (require __DIR__ . '/../database/investors-page-legacy.php' as $values) {
                $item = array_combine($columns, $values);
                $legacy[$item['id']] = $item;
            }
            $desired = array_column($defaults, null, 'id');
            $fields = array_slice($columns, 1);
            $update = $database->prepare('UPDATE investor_page_items SET ' . implode(',', array_map(static fn($field) => "$field=?", $fields)) . ',updated_at=NOW() WHERE id=?');
            $hide = $database->prepare('UPDATE investor_page_items SET is_active=0,updated_at=NOW() WHERE id=?');
            foreach ($existing as $item) {
                $id = $item['id'];
                if (!isset($legacy[$id]) || $item['updated_at'] !== null || !(bool)$item['is_active']) continue;
                foreach ($columns as $field) {
                    if ((string)$item[$field] !== (string)$legacy[$id][$field]) continue 2;
                }
                if (!isset($desired[$id])) {
                    // Retain older optional cards in the editor, but hide them publicly.
                    $hide->execute([$id]);
                } elseif ($desired[$id] != $legacy[$id]) {
                    $values = array_map(static fn($field) => $desired[$id][$field], $fields);
                    $update->execute([...$values, $id]);
                }
            }
        }
        $database->commit();
    } catch (Throwable $error) {
        if ($database->inTransaction()) $database->rollBack();
        throw $error;
    }
}

function investors_page_public_items(): array
{
    try {
        require_once __DIR__ . '/admin.php';
        $rows = admin_rows('SELECT id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order FROM investor_page_items WHERE is_active=1 ORDER BY section_key,sort_order,created_at,id');
        foreach ($rows as &$row) $row['sort_order'] = (int)$row['sort_order'];
        unset($row);
        // Empty is intentional when the administrator hides or deletes all content.
        return $rows;
    } catch (Throwable $error) {
        error_log('Investors page using default content: ' . $error->getMessage());
        return investors_page_defaults();
    }
}
