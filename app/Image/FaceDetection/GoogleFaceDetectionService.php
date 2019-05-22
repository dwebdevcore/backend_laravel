<?php

namespace App\Image\FaceDetection;

use Google\Cloud\Vision\V1\FaceAnnotation;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleFaceDetectionService
{
    const UNKNOWN = 0;
    const VERY_UNLIKELY = 1;
    const UNLIKELY = 2;
    const POSSIBLE = 3;
    const LIKELY = 4;
    const VERY_LIKELY = 5;

    const LIKELIHOOD = [
        self::UNKNOWN       => 'UNKNOWN',
        self::VERY_UNLIKELY => 'VERY_UNLIKELY',
        self::UNLIKELY      => 'UNLIKELY',
        self::POSSIBLE      => 'POSSIBLE',
        self::LIKELY        => 'LIKELY',
        self::VERY_LIKELY   => 'VERY_LIKELY'
    ];

    /**
     * @var ImageAnnotatorClient
     */
    private $client;

    public function __construct(ImageAnnotatorClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $image
     * @return array
     * @throws \Google\ApiCore\ApiException
     */
    public function detect($image)
    {
        $response = $this->client->faceDetection($image);

        $faces = $response->getFaceAnnotations();

        return array_map(function(FaceAnnotation $face) {
            return [
                'detectionConfidence' => $face->getDetectionConfidence(),
                'landmarkingConfidence' => $face->getLandmarkingConfidence(),
                'joyLikelihood' => $this->getLikelihoodConstant($face->getJoyLikelihood()),
                'sorrowLikelihood' => $this->getLikelihoodConstant($face->getSorrowLikelihood()),
                'angerLikelihood' => $this->getLikelihoodConstant($face->getAngerLikelihood()),
                'surpriseLikelihood' => $this->getLikelihoodConstant($face->getSurpriseLikelihood()),
                'underExposedLikelihood' => $this->getLikelihoodConstant($face->getUnderExposedLikelihood()),
                'blurredLikelihood' => $this->getLikelihoodConstant($face->getBlurredLikelihood()),
                'headwearLikelihood' => $this->getLikelihoodConstant($face->getHeadwearLikelihood()),
            ];
        }, iterator_to_array($faces));
    }

    private function getLikelihoodConstant($key)
    {
        return self::LIKELIHOOD[$key] ?? null;
    }
}
