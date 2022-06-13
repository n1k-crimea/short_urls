<?php

class ShortUrl
{
    const TABLE_NAME = 'short_urls';

    static public function find(string $field, string $value)
    {
        $db = getPDOInstance();
        $tableName = self::TABLE_NAME;

        $sql = "SELECT * FROM $tableName  WHERE $field = '$value' LIMIT 1";
        $st = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_CLASS);

        return empty($result) ? false : $result[0];
    }

    static public function create(string $longUrl)
    {
        $db = getPDOInstance();
        $tableName = self::TABLE_NAME;
        $shortCode = self::createShortCode($longUrl);
        $dateNow = date('Y-m-d h:i:s');

        $sql = "INSERT INTO $tableName (long_url, short_code, date_created) VALUES ('$longUrl', '$shortCode', '$dateNow')";
        $st = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $result = $st->execute();

        if ($result) {
            return self::find('id', $db->lastInsertId());
        }
        return false;
    }

    static private function createShortCode(string $longUrl): string
    {
        $hash = new \Hashids\Hashids($longUrl, 4);
        return $hash->encode(rand(1, 100));
    }
}