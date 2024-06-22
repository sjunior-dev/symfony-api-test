<?php

namespace App\Error;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class Normalizer implements NormalizerInterface
{
    /**
    * @param array<string, mixed> $context
    * @return array<string, mixed>
    */
    public function normalize($exception, string|null $format = null, array $context = []): array
    {
        return [
            'message' => $context['debug'] ? $exception->getMessage() : 'An error occured',
            'status' => $exception->getStatusCode(),
            'trace' => $context['debug'] ? $exception->getTrace() : [],
        ];
    }

    /**
    * @param array<string, mixed> $context
    */
    public function supportsNormalization($data, string|null $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            \Exception::class => true,
        ];
    }
}
