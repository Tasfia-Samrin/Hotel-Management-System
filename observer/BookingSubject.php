<?php
namespace Observer;

require_once 'Subject.php';
require_once 'Observer.php';

class BookingSubject implements Subject {
    private array $observers = [];

    public function attach(Observer $observer): void {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer): void {
        $this->observers = array_filter(
            $this->observers,
            fn($obs) => $obs !== $observer
        );
    }

    public function notify(string $eventType, array $data): void {
        foreach ($this->observers as $observer) {
            $observer->update($eventType, $data);
        }
    }
}
