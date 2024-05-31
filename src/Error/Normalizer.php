<?php

namespace App\Error;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class Normalizer implements NormalizerInterface
{
    public function normalize($exception, string|null $format = null, array $context = []): array
    {
        return [
            'message' => $context['debug'] ? $exception->getMessage() : 'An error occured',
            'status' => $exception->getStatusCode(),
            'trace' => $context['debug'] ? $exception->getTrace() : [],
        ];
    }

    public function supportsNormalization($data, string|null $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['exception'];
    }
}
