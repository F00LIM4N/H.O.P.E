<?php

namespace App\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class UserManager
{
    public function __construct(
        private Connection $connection
    ){  }

    public function connexion(string $name): ?array
    {
        $result = $this->connection->fetchAssociative("
            SELECT * FROM \"user\" WHERE name_user = ?
        ", [
            $name
        ]);

        return $result ?: null;
    }

    public function inscription($nom_user, $pswd_user, $birth)
    {
        try {
            $this->connection->executeStatement('
                INSERT INTO "user" 
                (name_user, pswd_user, birth_user, role_user) 
                VALUES (:name_user, :pswd_user, :birth_user, :role_user)
            ', [
                'name_user' => $nom_user,
                'pswd_user' => $pswd_user,
                'birth_user' => $birth,
                'role_user' => 0
            ]);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getNewUser($nom_user, $birth)
    {
        try {
            $this->connection->fetchAssociative('
                SELECT id_user 
                FROM "user"
                WHERE name_user = :name_user AND birth_user = :birth_user AND role_user = 0
            ', [
                'name_user' => $nom_user,
                'birth_user' => $birth
            ]);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}