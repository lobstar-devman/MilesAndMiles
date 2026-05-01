<?php

namespace App\Enums;

enum OrganisationPermission: string
{
    case Hr = 'hr';
    case Billing = 'billing';
    case Oversight = 'oversight';
    case Approval = 'approval';
    case SubmitJourneyRecord = 'submit_journey_record';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Hr => 'HR',
            self::Billing => 'Billing',
            self::Oversight => 'Oversight',
            self::Approval => 'Approval',
            self::SubmitJourneyRecord => 'Submit Journey Record',
            self::Admin => 'Admin',
        };
    }
}
