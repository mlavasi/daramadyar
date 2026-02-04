<?php
// fetch_data.php
include_once __DIR__ . '/include/config.php';
include_once __DIR__ . '/include/db.php';
include_once __DIR__ . '/include/auth.php'; 

header('Content-Type: application/json');

// دریافت ورودی‌ها
$page_input = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_input = isset($_GET['search']) ? $_GET['search'] : '';
$filter_input = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// --- منطق محدودسازی دسترسی ---
if ($is_premium) {
    // اگر دسترسی دارد، همه چیز عادی است
    $page = $page_input;
    $search = $search_input;
    $filter = $filter_input;
} else {
    // اگر دسترسی ندارد:
    // 1. فقط صفحه 1 مجاز است (اگر صفحه 2 به بعد خواست، آرایه خالی برمی‌گردد)
    if ($page_input > 1) {
        echo json_encode(['status' => 'success', 'data' => []]); 
        exit;
    }
    $page = 1;
    
    // 2. جستجو و فیلتر غیرفعال (اجبار به حالت پیش‌فرض)
    $search = ''; 
    $filter = 'all';
}
// ----------------------------

$limit = 50;
$offset = ($page - 1) * $limit;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// ساخت شرط‌های SQL
$whereClause = "WHERE 1=1";
$params = [];









// 1. لاجیک جستجو
if (!empty($search)) {
    // نرمال سازی اعداد فارسی به انگلیسی (مشابه تابع JS شما)
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $search = str_replace($persian, $english, $search);

    $whereClause .= " AND (code LIKE :search OR title LIKE :search)";
    $params[':search'] = "%$search%";
}

// 2. لاجیک فیلترها (Chips)
if ($filter !== 'all') {
    switch ($filter) {
        case 'global':
            $whereClause .= " AND global = '1'";
            break;
        case 'star':
            $whereClause .= " AND feature LIKE '%*%'";
            break;
        case 'mark':
            $whereClause .= " AND feature LIKE '%#%'";
            break;
        case 'c_code':
            $whereClause .= " AND cucif LIKE '%c%'";
            break;
        case 'anesthesia':
            $whereClause .= " AND (anesthesia != '0' AND anesthesia != '')";
            break;
    }
}

// اجرای کوئری با Limit و Offset
try {
    $sql = "SELECT * FROM tbl_rvu $whereClause LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // تبدیل داده‌ها به فرمت مورد نیاز JS (Mapping)
    $result = [];
    foreach ($rows as $item) {
        $result[] = [
            'code' => $item['code'],
            'sysid' => $item['systemId'],
            'attr' => $item['feature'],
            'desc' => str_replace(["\r", "\n"], ' ', $item['title']),
            'details' => str_replace(["\r", "\n"], ' ', $item['description']),
            'total_part' => $item['total'],
            'pro_part' => $item['prof'],
            'tech_part' => $item['technical'],
            'anesthesia' => $item['anesthesia'],
            'gov_out' => $item['opd'],
            'gov_in' => $item['inpatient'],
            'gov_part_time' => $item['opd_inpatient'],
            'private' => $item['private'],
            'public_non_gov' => $item['general'],
            'charity' => $item['charity'],
            'global' => $item['global'],
            'file' => $item['parvande'],
            'no_cover' => $item['star_list'],
            'lab_price' => $item['lab_dade_kham'],
            'type' => $item['cucif']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $result]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>