<?php

namespace App\Image\FaceDetection;

use Google\Cloud\Vision\V1\AnnotateImageResponse;
use Google\Cloud\Vision\V1\FaceAnnotation;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use PHPUnit\Framework\TestCase;

class GoogleFaceDetectionServiceTest extends TestCase
{
    public function testCanInstantiate()
    {
        $client = $this->mockClient();

        $service = new GoogleFaceDetectionService($client);

        $this->assertInstanceOf(GoogleFaceDetectionService::class, $service);
    }

    /**
     * @throws \Google\ApiCore\ApiException
     */
    public function testCanDetectFaces()
    {
        $client = $this->mockClient();

        $client->expects($this->once())
            ->method('faceDetection')
            ->willReturn(new AnnotateImageResponse(
                [
                    'face_annotations' => [
                        new FaceAnnotation([
                            'detection_confidence' => 0.98069099999999998,
                            'landmarking_confidence' => 0.57905465,
                            'joy_likelihood' => 0,
                            'sorrow_likelihood' => 1,
                            'anger_likelihood' => 2,
                            'surprise_likelihood' => 3,
                            'under_exposed_likelihood' => 4,
                            'blurred_likelihood' => 5,
                            'headwear_likelihood' => 0,
                        ]),
                    ],
                ]
            ));

        $service = new GoogleFaceDetectionService($client);

        $faces = $service->detect('some/image');

        $this->assertCount(1, $faces);

        $this->assertEquals([
            [
                'detectionConfidence' => 0.98069099999999998,
                'landmarkingConfidence' => 0.57905465,
                'joyLikelihood' => 'UNKNOWN',
                'sorrowLikelihood' => 'VERY_UNLIKELY',
                'angerLikelihood' => 'UNLIKELY',
                'surpriseLikelihood' => 'POSSIBLE',
                'underExposedLikelihood' => 'LIKELY',
                'blurredLikelihood' => 'VERY_LIKELY',
                'headwearLikelihood' => 'UNKNOWN',
            ]
        ], $faces);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|ImageAnnotatorClient
     */
    private function mockClient()
    {
        return $this->getMockBuilder(ImageAnnotatorClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
