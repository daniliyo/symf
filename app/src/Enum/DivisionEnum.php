<?php

namespace App\Enum;

enum DivisionEnum: string
{
    case DIVISION_A = 'A';
    case DIVISION_B = 'B';
    case PLAY_OFF = 'P';

    public function teams(): array
    {
        return match($this) {
            DivisionEnum::DIVISION_A => ['team A', 'team B', 'team C', 'team D', 'team E', 'team F', 'team G', 'team H', 'team I', 'team J'],
            DivisionEnum::DIVISION_B => ['team K', 'team L', 'team M', 'team N', 'team O', 'team P', 'team Q', 'team R', 'team S', 'team T'],
        };
    }
}