<?php

namespace App\Entity;

class ApplicationStatus
{

    public const STATUS_PENDING = 'STATUS_PENDING';
    public const STATUS_ACCEPTED = 'STATUS_ACCEPTED';
    public const STATUS_REFUSED = 'STATUS_REFUSED';

    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
