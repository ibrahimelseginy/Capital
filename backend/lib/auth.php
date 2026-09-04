<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

const AUTH_STORE_MARKER = "<?php exit; ?>\n";

function auth_boot(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $remember = isset($_POST['remember']) && $_POST['remember'] === '1';
    session_name('stc_session');
    session_set_cookie_params([
        'lifetime' => $remember ? 60 * 60 * 24 * 30 : 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
    auth_ensure_store();
}

function auth_store_path(): string
{
    return dirname(__DIR__) . '/storage/users.php';
}

function auth_reset_store_path(): string
{
    return dirname(__DIR__) . '/storage/password-resets.php';
}

function auth_investor_store_path(): string
{
    return dirname(__DIR__) . '/storage/investor-data.php';
}

function auth_ensure_store(): void
{
    if (app_db() instanceof PDO) {
        return;
    }
    $path = auth_store_path();
    $directory = dirname($path);
    if (!is_dir($directory)) {
        mkdir($directory, 0700, true);
    }
    if (is_file($path)) {
        if (!is_file(auth_reset_store_path())) {
            auth_write_resets([]);
        }
        if (!is_file(auth_investor_store_path())) {
            auth_seed_investor_data();
        }
        return;
    }

    auth_write_users([]);
    auth_write_resets([]);
    auth_seed_investor_data();
}

function auth_read_users(): array
{
    $database = app_db();
    if ($database instanceof PDO) {
        $users = $database->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
        foreach ($users as &$user) {
            $user['is_demo'] = (bool) ($user['is_demo'] ?? false);
        }
        unset($user);
        return $users;
    }
    $content = file_get_contents(auth_store_path());
    if ($content === false || !str_starts_with($content, AUTH_STORE_MARKER)) {
        return [];
    }
    $users = json_decode(substr($content, strlen(AUTH_STORE_MARKER)), true);
    return is_array($users) ? $users : [];
}

function auth_write_users(array $users): void
{
    $database = app_db();
    if ($database instanceof PDO) {
        $database->beginTransaction();
        try {
            $statement = $database->prepare(
                'INSERT INTO users (id,name,email,password,role,country,whatsapp,project,investor_type,kyc_status,is_demo,created_at,updated_at,password_updated_at,kyc_updated_at)
                 VALUES (:id,:name,:email,:password,:role,:country,:whatsapp,:project,:investor_type,:kyc_status,:is_demo,:created_at,:updated_at,:password_updated_at,:kyc_updated_at)
                 ON DUPLICATE KEY UPDATE name=VALUES(name),email=VALUES(email),password=VALUES(password),role=VALUES(role),country=VALUES(country),whatsapp=VALUES(whatsapp),project=VALUES(project),investor_type=VALUES(investor_type),kyc_status=VALUES(kyc_status),is_demo=VALUES(is_demo),updated_at=VALUES(updated_at),password_updated_at=VALUES(password_updated_at),kyc_updated_at=VALUES(kyc_updated_at)'
            );
            $ids = [];
            foreach ($users as $user) {
                $ids[] = (string) $user['id'];
                $statement->execute([
                    'id'=>(string)$user['id'], 'name'=>(string)$user['name'], 'email'=>(string)$user['email'],
                    'password'=>(string)$user['password'], 'role'=>(string)$user['role'], 'country'=>(string)($user['country'] ?? ''),
                    'whatsapp'=>(string)($user['whatsapp'] ?? ''), 'project'=>(string)($user['project'] ?? ''),
                    'investor_type'=>(string)($user['investor_type'] ?? ''), 'kyc_status'=>(string)($user['kyc_status'] ?? 'pending'),
                    'is_demo'=>!empty($user['is_demo']) ? 1 : 0,
                    'created_at'=>app_db_datetime((string)($user['created_at'] ?? '')) ?? date('Y-m-d H:i:s'),
                    'updated_at'=>app_db_datetime($user['updated_at'] ?? null),
                    'password_updated_at'=>app_db_datetime($user['password_updated_at'] ?? null),
                    'kyc_updated_at'=>app_db_datetime($user['kyc_updated_at'] ?? null),
                ]);
            }
            if ($ids) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $delete = $database->prepare("DELETE FROM users WHERE id NOT IN ($placeholders)");
                $delete->execute($ids);
            } else {
                $database->exec('DELETE FROM users');
            }
            $database->commit();
            return;
        } catch (Throwable $error) {
            $database->rollBack();
            throw $error;
        }
    }
    $payload = AUTH_STORE_MARKER . json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents(auth_store_path(), $payload, LOCK_EX);
    chmod(auth_store_path(), 0600);
}

function auth_read_resets(): array
{
    $database = app_db();
    if ($database instanceof PDO) {
        return $database->query('SELECT user_id,token_hash,UNIX_TIMESTAMP(expires_at) AS expires_at,created_at FROM password_resets ORDER BY created_at DESC')->fetchAll();
    }
    $content = file_get_contents(auth_reset_store_path());
    if ($content === false || !str_starts_with($content, AUTH_STORE_MARKER)) {
        return [];
    }
    $resets = json_decode(substr($content, strlen(AUTH_STORE_MARKER)), true);
    return is_array($resets) ? $resets : [];
}

function auth_write_resets(array $resets): void
{
    $database = app_db();
    if ($database instanceof PDO) {
        $database->beginTransaction();
        try {
            $database->exec('DELETE FROM password_resets');
            $statement = $database->prepare('INSERT INTO password_resets (user_id,token_hash,expires_at,created_at) VALUES (?,?,FROM_UNIXTIME(?),?)');
            foreach ($resets as $reset) {
                $statement->execute([
                    (string)($reset['user_id'] ?? ''), (string)($reset['token_hash'] ?? ''),
                    (int)($reset['expires_at'] ?? 0), app_db_datetime((string)($reset['created_at'] ?? '')) ?? date('Y-m-d H:i:s'),
                ]);
            }
            $database->commit();
            return;
        } catch (Throwable $error) {
            $database->rollBack();
            throw $error;
        }
    }
    $payload = AUTH_STORE_MARKER . json_encode($resets, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents(auth_reset_store_path(), $payload, LOCK_EX);
    chmod(auth_reset_store_path(), 0600);
}

function auth_empty_investor_data(): array
{
    return [
        'holdings' => [],
        'portfolio_history' => [],
        'opportunities' => [],
        'meetings' => [],
        'activities' => [],
        'pledges' => [],
    ];
}

function auth_read_investor_store(): array
{
    $database = app_db();
    if ($database instanceof PDO) {
        $store = [];
        $ensureUser = static function (array &$target, string $userId): void {
            if (!isset($target[$userId])) {
                $target[$userId] = auth_empty_investor_data();
            }
        };
        foreach ($database->query('SELECT id FROM users WHERE role = "investor"')->fetchAll() as $row) {
            $ensureUser($store, (string) $row['id']);
        }
        foreach ($database->query('SELECT id,user_id,name,sector,invested_amount,current_value,currency,status FROM investor_holdings ORDER BY created_at,id')->fetchAll() as $row) {
            $userId=(string)$row['user_id']; $ensureUser($store,$userId); unset($row['user_id']);
            $row['invested_amount']=(int)$row['invested_amount']; $row['current_value']=(int)$row['current_value'];
            $store[$userId]['holdings'][]=$row;
        }
        foreach ($database->query('SELECT user_id,label,value,sort_order FROM portfolio_history ORDER BY user_id,sort_order,id')->fetchAll() as $row) {
            $userId=(string)$row['user_id']; $ensureUser($store,$userId);
            $store[$userId]['portfolio_history'][]=['label'=>$row['label'],'value'=>(int)$row['value']];
        }
        foreach ($database->query('SELECT io.user_id,o.id,o.title,o.sector,o.stage,o.target_amount,o.currency,o.status FROM investor_opportunities io JOIN opportunities o ON o.id=io.opportunity_id ORDER BY o.created_at,o.id')->fetchAll() as $row) {
            $userId=(string)$row['user_id']; $ensureUser($store,$userId); unset($row['user_id']);
            $row['target_amount']=(int)$row['target_amount']; $store[$userId]['opportunities'][]=$row;
        }
        foreach ($database->query('SELECT id,user_id,subject,opportunity,scheduled_at,platform,status FROM meetings ORDER BY scheduled_at,id')->fetchAll() as $row) {
            $userId=(string)$row['user_id']; $ensureUser($store,$userId); unset($row['user_id']);
            $store[$userId]['meetings'][]=$row;
        }
        foreach ($database->query('SELECT id,user_id,text,type,created_at FROM activities ORDER BY created_at DESC,id')->fetchAll() as $row) {
            $userId=(string)$row['user_id']; $ensureUser($store,$userId); unset($row['user_id']);
            $store[$userId]['activities'][]=$row;
        }
        foreach ($database->query('SELECT id,user_id,opportunity_id,amount,currency,status,created_at FROM pledges ORDER BY created_at,id')->fetchAll() as $row) {
            $userId=(string)$row['user_id']; $ensureUser($store,$userId); unset($row['user_id']);
            $row['amount']=(int)$row['amount']; $store[$userId]['pledges'][]=$row;
        }
        return $store;
    }
    $content = file_get_contents(auth_investor_store_path());
    if ($content === false || !str_starts_with($content, AUTH_STORE_MARKER)) {
        return [];
    }
    $data = json_decode(substr($content, strlen(AUTH_STORE_MARKER)), true);
    return is_array($data) ? $data : [];
}

function auth_write_investor_store(array $data): void
{
    $database = app_db();
    if ($database instanceof PDO) {
        $database->beginTransaction();
        try {
            foreach (['pledges','activities','meetings','investor_opportunities','portfolio_history','investor_holdings','opportunities'] as $table) {
                $database->exec("DELETE FROM $table");
            }
            $holdingStatement=$database->prepare('INSERT INTO investor_holdings (id,user_id,name,sector,invested_amount,current_value,currency,status) VALUES (?,?,?,?,?,?,?,?)');
            $historyStatement=$database->prepare('INSERT INTO portfolio_history (user_id,label,value,sort_order) VALUES (?,?,?,?)');
            $opportunityStatement=$database->prepare('INSERT INTO opportunities (id,title,sector,stage,target_amount,currency,status) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE title=VALUES(title),sector=VALUES(sector),stage=VALUES(stage),target_amount=VALUES(target_amount),currency=VALUES(currency),status=VALUES(status)');
            $assignmentStatement=$database->prepare('INSERT IGNORE INTO investor_opportunities (user_id,opportunity_id) VALUES (?,?)');
            $meetingStatement=$database->prepare('INSERT INTO meetings (id,user_id,subject,opportunity,scheduled_at,platform,status) VALUES (?,?,?,?,?,?,?)');
            $activityStatement=$database->prepare('INSERT INTO activities (id,user_id,text,type,created_at) VALUES (?,?,?,?,?)');
            $pledgeStatement=$database->prepare('INSERT INTO pledges (id,user_id,opportunity_id,amount,currency,status,created_at) VALUES (?,?,?,?,?,?,?)');
            foreach ($data as $userId=>$investorData) {
                foreach ($investorData['holdings'] ?? [] as $holding) {
                    $holdingStatement->execute([$holding['id'],$userId,$holding['name'],$holding['sector'],(int)$holding['invested_amount'],(int)$holding['current_value'],$holding['currency'] ?? 'USD',$holding['status'] ?? 'active']);
                }
                foreach ($investorData['portfolio_history'] ?? [] as $index=>$point) {
                    $historyStatement->execute([$userId,$point['label'],(int)$point['value'],$index+1]);
                }
                foreach ($investorData['opportunities'] ?? [] as $opportunity) {
                    $opportunityStatement->execute([$opportunity['id'],$opportunity['title'],$opportunity['sector'],$opportunity['stage'],(int)$opportunity['target_amount'],$opportunity['currency'] ?? 'USD',$opportunity['status'] ?? 'review']);
                    $assignmentStatement->execute([$userId,$opportunity['id']]);
                }
                foreach ($investorData['meetings'] ?? [] as $meeting) {
                    $meetingStatement->execute([$meeting['id'],$userId,$meeting['subject'],$meeting['opportunity'] ?? '',app_db_datetime((string)$meeting['scheduled_at']),$meeting['platform'],$meeting['status'] ?? 'pending']);
                }
                foreach ($investorData['activities'] ?? [] as $activity) {
                    $activityStatement->execute([$activity['id'],$userId,$activity['text'],$activity['type'] ?? 'info',app_db_datetime((string)$activity['created_at'])]);
                }
                foreach ($investorData['pledges'] ?? [] as $pledge) {
                    $pledgeStatement->execute([$pledge['id'],$userId,$pledge['opportunity_id'],(int)$pledge['amount'],$pledge['currency'] ?? 'USD',$pledge['status'] ?? 'non_binding',app_db_datetime((string)$pledge['created_at'])]);
                }
            }
            $database->commit();
            return;
        } catch (Throwable $error) {
            $database->rollBack();
            throw $error;
        }
    }
    $payload = AUTH_STORE_MARKER . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents(auth_investor_store_path(), $payload, LOCK_EX);
    chmod(auth_investor_store_path(), 0600);
}

function auth_seed_investor_data(): void
{
    auth_write_investor_store([]);
}

function auth_get_investor_data(string $userId): array
{
    $store = auth_read_investor_store();
    $data = $store[$userId] ?? [];
    return array_replace(auth_empty_investor_data(), is_array($data) ? $data : []);
}

function auth_investor_metrics(array $data): array
{
    $portfolioTotal = array_sum(array_map(static fn(array $holding): int => max(0, (int) ($holding['current_value'] ?? 0)), $data['holdings'] ?? []));
    $pledgeTotal = array_sum(array_map(static fn(array $pledge): int => max(0, (int) ($pledge['amount'] ?? 0)), $data['pledges'] ?? []));
    $availableOpportunities = count(array_filter($data['opportunities'] ?? [], static fn(array $opportunity): bool => ($opportunity['status'] ?? '') === 'available'));
    $now = new DateTimeImmutable('now');
    $upcomingMeetings = count(array_filter($data['meetings'] ?? [], static function (array $meeting) use ($now): bool {
        try {
            return new DateTimeImmutable((string) ($meeting['scheduled_at'] ?? '')) >= $now;
        } catch (Throwable) {
            return false;
        }
    }));
    return [
        'portfolio_total' => $portfolioTotal,
        'pledge_total' => $pledgeTotal,
        'opportunity_count' => $availableOpportunities,
        'meeting_count' => $upcomingMeetings,
    ];
}

function auth_csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function auth_verify_csrf(?string $token): bool
{
    return is_string($token) && hash_equals(auth_csrf_token(), $token);
}

function auth_find_user(string $email): ?array
{
    $email = strtolower(trim($email));
    foreach (auth_read_users() as $user) {
        if (($user['email'] ?? '') === $email) {
            $user['original_role'] = $user['role'] ?? 'user';
            $user['role'] = 'admin';
            return $user;
        }
    }
    return null;
}

function auth_find_user_by_id(string $id): ?array
{
    foreach (auth_read_users() as $user) {
        if (hash_equals((string) ($user['id'] ?? ''), $id)) {
            $user['original_role'] = $user['role'] ?? 'user';
            $user['role'] = 'admin';
            return $user;
        }
    }
    return null;
}

function auth_initials(string $name): string
{
    $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $letters = [];
    foreach (array_slice($parts, 0, 2) as $part) {
        $letters[] = function_exists('mb_substr') ? mb_substr($part, 0, 1, 'UTF-8') : substr($part, 0, 1);
    }
    return $letters ? implode('ـ', $letters) : 'م';
}

function auth_update_investor_profile(string $userId, array $input): array
{
    $name = trim((string) ($input['name'] ?? ''));
    $email = strtolower(trim((string) ($input['email'] ?? '')));
    $whatsapp = trim((string) ($input['whatsapp'] ?? ''));
    $country = trim((string) ($input['country'] ?? ''));
    $investorType = trim((string) ($input['investor_type'] ?? ''));
    $allowedTypes = ['فرد مؤهل', 'شركة', 'صندوق استثماري', 'مكتب عائلي'];

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $whatsapp === '' || $country === '') {
        return [false, 'يرجى إدخال الاسم والبريد ورقم واتساب والدولة بشكل صحيح.'];
    }
    if (!preg_match('/^\+?[0-9\s()-]{8,20}$/', $whatsapp)) {
        return [false, 'رقم واتساب غير صحيح. استخدم أرقامًا مع رمز الدولة.'];
    }
    if (!in_array($investorType, $allowedTypes, true)) {
        return [false, 'نوع المستثمر غير صحيح.'];
    }

    $users = auth_read_users();
    foreach ($users as $user) {
        if (($user['email'] ?? '') === $email && ($user['id'] ?? '') !== $userId) {
            return [false, 'البريد الإلكتروني مستخدم في حساب آخر.'];
        }
    }

    $updated = null;
    foreach ($users as &$user) {
        if (($user['id'] ?? '') === $userId && ($user['role'] ?? '') === 'investor') {
            $user['name'] = $name;
            $user['email'] = $email;
            $user['whatsapp'] = $whatsapp;
            $user['country'] = $country;
            $user['investor_type'] = $investorType;
            $user['updated_at'] = gmdate('c');
            $updated = $user;
            break;
        }
    }
    unset($user);
    if ($updated === null) {
        return [false, 'تعذر العثور على حساب المستثمر.'];
    }

    auth_write_users($users);
    $_SESSION['name'] = $updated['name'];
    $_SESSION['email'] = $updated['email'];
    $_SESSION['investor_type'] = $updated['investor_type'];
    return [true, 'تم حفظ بيانات الحساب بنجاح.'];
}

function auth_update_admin_profile(string $userId, array $input): array
{
    $name = trim((string) ($input['name'] ?? ''));
    $email = strtolower(trim((string) ($input['email'] ?? '')));
    $country = trim((string) ($input['country'] ?? ''));
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $country === '') {
        return [false, 'يرجى إدخال الاسم والبريد والدولة بشكل صحيح.'];
    }

    $users = auth_read_users();
    foreach ($users as $user) {
        if (($user['email'] ?? '') === $email && ($user['id'] ?? '') !== $userId) {
            return [false, 'البريد الإلكتروني مستخدم في حساب آخر.'];
        }
    }

    $updated = null;
    foreach ($users as &$user) {
        if (($user['id'] ?? '') === $userId && ($user['role'] ?? '') === 'admin') {
            $user['name'] = $name;
            $user['email'] = $email;
            $user['country'] = $country;
            $user['updated_at'] = gmdate('c');
            $updated = $user;
            break;
        }
    }
    unset($user);
    if ($updated === null) {
        return [false, 'تعذر العثور على حساب الإدارة.'];
    }
    auth_write_users($users);
    $_SESSION['name'] = $updated['name'];
    $_SESSION['email'] = $updated['email'];
    return [true, 'تم حفظ بيانات حساب الإدارة بنجاح.'];
}

function auth_set_kyc_status(string $userId, string $status): array
{
    if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
        return [false, 'حالة المراجعة غير صحيحة.'];
    }
    $users = auth_read_users();
    $updated = false;
    foreach ($users as &$user) {
        if (($user['id'] ?? '') === $userId && ($user['role'] ?? '') === 'investor') {
            $user['kyc_status'] = $status;
            $user['kyc_updated_at'] = gmdate('c');
            $updated = true;
            break;
        }
    }
    unset($user);
    if (!$updated) {
        return [false, 'تعذر العثور على حساب المستثمر.'];
    }
    auth_write_users($users);
    return [true, $status === 'approved' ? 'تم اعتماد حساب المستثمر.' : ($status === 'rejected' ? 'تم رفض مراجعة الحساب.' : 'تمت إعادة الحساب إلى قائمة المراجعة.')];
}

function auth_role_label(string $role): string
{
    return match ($role) {
        'investor' => 'مستثمر',
        'entrepreneur' => 'رائد أعمال',
        'admin' => 'إدارة',
        default => 'غير محدد',
    };
}

function auth_kyc_label(string $status): string
{
    return match ($status) {
        'approved' => 'معتمد',
        'rejected' => 'مرفوض',
        default => 'قيد المراجعة',
    };
}

function auth_kyc_badge(string $status): string
{
    return match ($status) {
        'approved' => 'badge-success',
        'rejected' => 'badge-danger',
        default => 'badge-warning',
    };
}

function auth_password_error(string $password): string
{
    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[^A-Za-z\d]/', $password)) {
        return 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل وتحتوي على حرف ورقم ورمز.';
    }
    return '';
}

function auth_create_password_reset(string $email): ?string
{
    $user = auth_find_user($email);
    $now = time();
    $resets = array_values(array_filter(
        auth_read_resets(),
        static fn(array $reset): bool => (int) ($reset['expires_at'] ?? 0) > $now
    ));

    if (!$user) {
        auth_write_resets($resets);
        return null;
    }

    $token = bin2hex(random_bytes(32));
    $resets = array_values(array_filter(
        $resets,
        static fn(array $reset): bool => ($reset['user_id'] ?? '') !== $user['id']
    ));
    $resets[] = [
        'user_id' => $user['id'],
        'token_hash' => hash('sha256', $token),
        'expires_at' => $now + 3600,
        'created_at' => gmdate('c'),
    ];
    auth_write_resets($resets);
    return $token;
}

function auth_reset_password(string $token, string $password): array
{
    $passwordError = auth_password_error($password);
    if ($passwordError !== '') {
        return [false, $passwordError];
    }

    $tokenHash = hash('sha256', $token);
    $now = time();
    $matchedUserId = '';
    $remaining = [];
    foreach (auth_read_resets() as $reset) {
        $valid = (int) ($reset['expires_at'] ?? 0) > $now;
        if ($valid && hash_equals((string) ($reset['token_hash'] ?? ''), $tokenHash)) {
            $matchedUserId = (string) ($reset['user_id'] ?? '');
            continue;
        }
        if ($valid) {
            $remaining[] = $reset;
        }
    }
    if ($matchedUserId === '') {
        auth_write_resets($remaining);
        return [false, 'رابط استعادة كلمة المرور غير صالح أو انتهت صلاحيته.'];
    }

    $users = auth_read_users();
    $updated = false;
    foreach ($users as &$user) {
        if (($user['id'] ?? '') === $matchedUserId) {
            $user['password'] = password_hash($password, PASSWORD_DEFAULT);
            $user['password_updated_at'] = gmdate('c');
            $updated = true;
            break;
        }
    }
    unset($user);
    if (!$updated) {
        return [false, 'تعذر العثور على الحساب المرتبط بهذا الرابط.'];
    }
    auth_write_users($users);
    auth_write_resets(array_values(array_filter(
        $remaining,
        static fn(array $reset): bool => ($reset['user_id'] ?? '') !== $matchedUserId
    )));
    return [true, ''];
}

function auth_login(string $email, string $password): bool
{
    $user = auth_find_user($email);
    if (!$user || !password_verify($password, (string) $user['password'])) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = 'admin';
    $_SESSION['account_role'] = $user['original_role'] ?? 'admin';
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['project'] = $user['project'] ?? '';
    $_SESSION['investor_type'] = $user['investor_type'] ?? '';
    return true;
}

function auth_register(array $input): array
{
    $name = trim((string) ($input['name'] ?? ''));
    $email = strtolower(trim((string) ($input['email'] ?? '')));
    $password = (string) ($input['password'] ?? '');
    $whatsapp = trim((string) ($input['whatsapp'] ?? ''));
    $country = trim((string) ($input['country'] ?? ''));
    $role = (string) ($input['role'] ?? 'investor');
    $project = trim((string) ($input['project'] ?? ''));

    if (($input['terms'] ?? '') !== '1') {
        return [false, 'يجب الموافقة على الشروط وسياسة الخصوصية.'];
    }
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $whatsapp === '' || $country === '') {
        return [false, 'يرجى إدخال كل البيانات المطلوبة بشكل صحيح.'];
    }
    if (!in_array($role, ['investor', 'entrepreneur'], true)) {
        return [false, 'نوع الحساب غير صحيح.'];
    }
    if ($role === 'entrepreneur' && $project === '') {
        return [false, 'يرجى إدخال اسم المشروع أو الشركة.'];
    }
    $passwordError = auth_password_error($password);
    if ($passwordError !== '') {
        return [false, $passwordError];
    }
    if (auth_find_user($email)) {
        return [false, 'يوجد حساب مسجل بهذا البريد بالفعل.'];
    }

    $users = auth_read_users();
    $user = [
        'id' => bin2hex(random_bytes(8)), 'name' => $name, 'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => $role,
        'country' => $country, 'whatsapp' => $whatsapp, 'project' => $project,
        'investor_type' => $role === 'investor' ? trim((string) ($input['investor_type'] ?? 'فرد مؤهل')) : '',
        'is_demo' => false,
        'kyc_status' => 'pending',
        'created_at' => gmdate('c'),
    ];
    $users[] = $user;
    auth_write_users($users);
    auth_login($email, $password);
    return [true, ''];
}

function auth_dashboard_url(?string $role = null): string
{
    return 'dashboard/admin-home.php';
}

function auth_protect_dashboard(): void
{
    auth_boot();
    $user = !empty($_SESSION['user_id']) ? auth_find_user_by_id((string) $_SESSION['user_id']) : null;
    if (!$user) {
        $_SESSION = [];
        session_regenerate_id(true);
        header('Location: ../login.php?auth=required');
        exit;
    }

    $file = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if (str_starts_with($file, 'investor') || str_starts_with($file, 'entrepreneur')) {
        header('Location: ../' . auth_dashboard_url());
        exit;
    }
}

function auth_logout(): void
{
    auth_boot();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
    }
    session_destroy();
}
