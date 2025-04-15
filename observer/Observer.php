<?php
namespace Observer;

interface Observer {
    public function update(string $eventType, array $data): void;
}
