<?php
declare(strict_types=1);

namespace HeThong;

use PDO;
use PDOException;

class CoSoDuLieu
{
    private static ?PDO $ketNoi = null;

    public static function layKetNoi(): PDO
    {
        if (self::$ketNoi !== null) return self::$ketNoi;
        $tenCoSoDuLieu = trim((string)env('DB_TEN', ''));
        if ($tenCoSoDuLieu === '') {
            http_response_code(500);
            exit('Thieu cau hinh DB_TEN. Hay tao file .env tu .env.example va dien ten co so du lieu.');
        }
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s',
            env('DB_MAY_CHU', '127.0.0.1'),
            env('DB_CONG', '3306'),
            $tenCoSoDuLieu,
            env('DB_BANG_MA', 'utf8mb4')
        );
        try {
            self::$ketNoi = new PDO($dsn, env('DB_NGUOI_DUNG', 'root'), env('DB_MAT_KHAU', ''), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('Loi ket noi CSDL: ' . $e->getMessage());
        }
        return self::$ketNoi;
    }
}
