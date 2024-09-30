<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ResetTableService 
{

    public function __construct(
        private EntityManagerInterface $entityManager,    
    ) {}

    public function reset(): void
    {
        try {
            
            $conn = $this->entityManager->getConnection();
            
            $sql = 'TRUNCATE statistics';
            $conn->executeQuery($sql);

            $sql = 'TRUNCATE result';
            $conn->executeQuery($sql);
            
            $sql = '
                SET FOREIGN_KEY_CHECKS = 0; 
                TRUNCATE team; 
                SET FOREIGN_KEY_CHECKS = 1;
            ';
            $conn->executeQuery($sql);

        }catch (\Throwable $exception) {
            throw $exception;
        }
    }
}