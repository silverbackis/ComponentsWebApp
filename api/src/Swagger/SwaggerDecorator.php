<?php

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $customDefinition = [
            'name' => 'id',
            'definition' => 'Form ID',
            'default' => '9',
            'in' => 'path',
            'required' => true,
            'type' => 'string'
        ];

        // e.g. add a custom parameter
        $docs['paths']['/forms/{id}']['patch']['parameters'][] = $customDefinition;
        $docs['paths']['/forms/{id}']['patch']['summary'] = 'Tests a form field against validation';
        $docs['paths']['/forms/{id}']['patch']['consumes'] = ["application/json", "text/html"];
        $docs['paths']['/forms/{id}']['patch']['produces'] = ["application/json"];
        $docs['paths']['/forms/{id}']['patch']['responses'] = $docs['paths']['/forms/{id}']['get']['responses'];

        $docs['paths']['/forms/{id}']['patch']['responses']['201'] = $docs['paths']['/forms/{id}']['patch']['responses']['200'];
        unset($docs['paths']['/forms/{id}']['patch']['responses']['200']);

        $docs['paths']['/forms/{id}']['patch']['responses']['400'] = [
          'description' => 'Invalid user input'
        ];
        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
