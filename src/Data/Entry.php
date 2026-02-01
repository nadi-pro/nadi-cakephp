<?php

namespace Nadi\CakePHP\Data;

use Nadi\CakePHP\Concerns\InteractsWithMetric;
use Nadi\Data\Entry as DataEntry;
use Throwable;

class Entry extends DataEntry
{
    use InteractsWithMetric;

    public $user;

    public function __construct($type, array $content, $uuid = null)
    {
        parent::__construct($type, $content, $uuid);

        $this->registerMetrics();

        try {
            $identity = $this->resolveIdentity();
            if ($identity) {
                $this->user($identity);
            }
        } catch (Throwable $e) {
            // Do nothing.
        }
    }

    public function user($user): static
    {
        $this->user = $user;

        $id = method_exists($user, 'getIdentifier') ? $user->getIdentifier() : ($user->id ?? null);
        $name = $user->name ?? $user->username ?? null;
        $email = $user->email ?? null;

        $this->content = array_merge($this->content, [
            'user' => [
                'id' => $id,
                'name' => $name,
                'email' => $email,
            ],
        ]);

        $this->tags(['Auth:'.$id]);

        return $this;
    }

    private function resolveIdentity()
    {
        if (PHP_SAPI === 'cli') {
            return null;
        }

        // CakePHP Authentication plugin stores identity as request attribute
        // This is set by the AuthenticationMiddleware
        return null;
    }
}
