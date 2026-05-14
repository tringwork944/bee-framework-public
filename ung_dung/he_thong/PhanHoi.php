<?php
declare(strict_types=1);

namespace HeThong;

class PhanHoi
{
    public function html(string $noiDung, int $ma = 200): void
    {
        http_response_code($ma);
        echo $noiDung;
    }
}
