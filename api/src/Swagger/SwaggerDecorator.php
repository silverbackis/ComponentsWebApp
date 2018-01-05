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
/*
        $patchOp = $docs['paths']['/form_input_values/{id}']['patch'];
        // $copyFrom = $docs['paths']['/form_input_values/{id}']['put'];
        $patchOp['parameters'] = [
            [
                'name' => 'id',
                'in' => 'path',
                'type' => 'string',
                'required' => true
            ],
            [
                'name' => 'formInputValue',
                'in' => 'body',
                'description' => 'The updated FormInputValue resource',
                'scehma' => [
                    '$ref' => '#/definitions/FormInputValue-form_write'
                ]
            ]
        ];
        $docs['paths']['/form_input_values/{id}']['patch'] = $patchOp;*/
/*
        $copyFrom = $docs['paths']['/form_input_values/{id}']['put'];

        $newOp = [];
        // Group with other 'Form'
        // $newOp['tags'] = $copyFrom['tags'];
        $newOp['parameters'][] = $copyFrom['parameters'][0];
        //var_dump($copyFrom['parameters'][1]);
        $newOp['parameters'][] = [
          'schema' => [
              '$ref' => '#/definitions/FormInputValues'
          ]
        ];
        $newOp['consumes'] = $copyFrom['consumes'];
        $newOp['produces'] = $copyFrom['produces'];

        $newOp['responses'] = $copyFrom['responses'];

        $newOp['summary'] = 'Tests a form field against validation';

        $newOp['responses']['400'] = [
            'description' => 'Invalid user input',
            'schema' => $newOp['responses']['200']['schema']
        ];

*/
        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
