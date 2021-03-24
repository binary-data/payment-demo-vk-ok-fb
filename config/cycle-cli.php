<?php

declare(strict_types=1);

require_once 'cycle_orm_bootstrap.php';
return $orm ?? throw new RuntimeException('orm is not available');
