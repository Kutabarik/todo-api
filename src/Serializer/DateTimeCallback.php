<?php

namespace App\Serializer;

use DateTimeInterface;

class DateTimeCallback {

    public function __invoke(null|string|DateTimeInterface $object): DateTimeInterface|string|null
    {
        if ($object === null ) {
            return null;
        }

        if (!($object instanceof DateTimeInterface)) {
            return $object;
        }

        return $object->format('H:i:s d.m.Y');
    }
}
