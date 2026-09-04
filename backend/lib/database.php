<?php
declare(strict_types=1);

function app_db(): ?PDO
{
    static $attempted = false;
    static $connection = null;
    if ($attempted) {
        return $connection;
    }
    $attempted = true;
    $config = require dirname(__DIR__) . '/config/database.php';
    $multiStatementsAttribute = class_exists('Pdo\\Mysql')
        ? \Pdo\Mysql::ATTR_MULTI_STATEMENTS
        : PDO::MYSQL_ATTR_MULTI_STATEMENTS;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 2,
        $multiStatementsAttribute => true,
    ];

    try {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
        $connection = new PDO($dsn, $config['username'], $config['password'], $options);
        try {
            $connection->query('SELECT 1 FROM users LIMIT 1');
        } catch (Throwable) {
            app_db_install_schema($connection);
        }
        return $connection;
    } catch (Throwable $databaseError) {
        try {
            $serverDsn = sprintf(
                'mysql:host=%s;port=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['charset']
            );
            $server = new PDO($serverDsn, $config['username'], $config['password'], $options);
            app_db_install_schema($server);
            $connection = new PDO($dsn, $config['username'], $config['password'], $options);
            return $connection;
        } catch (Throwable $installError) {
            error_log('MySQL unavailable; using protected file storage: ' . $installError->getMessage());
            $connection = null;
            return null;
        }
    }
}

function app_db_install_schema(PDO $connection): void
{
    $schema = file_get_contents(dirname(__DIR__) . '/database/capital_ui.sql');
    if ($schema === false) {
        throw new RuntimeException('Database schema file is missing.');
    }
    $connection->exec($schema);
}

function app_db_datetime(?string $value): ?string
{
    if (!$value) {
        return null;
    }
    try {
        return (new DateTimeImmutable($value))->format('Y-m-d H:i:s');
    } catch (Throwable) {
        return null;
    }
}
