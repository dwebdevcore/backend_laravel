<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Image\FaceDetection\GoogleFaceDetectionService;
use Illuminate\Http\Request;

class FaceDetectionController extends Controller
{
    /**
     * @OA\POST(
     *     path="/api/v1/image/face-detection",
     *     summary="Detect faces from an image.",
     *     @OA\Parameter(
     *         name="image",
     *         description="Image encoded in base64.",
     *         required=true,
     *         in="query"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Image faces annotations.",
     *     )
     * )
     * @param Request $request
     * @param GoogleFaceDetectionService $faceDetectionService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Google\ApiCore\ApiException
     */
    public function index(Request $request, GoogleFaceDetectionService $faceDetectionService)
    {
        $image = base64_decode($request->input('image'));

        $faces = $faceDetectionService->detect($image);

        return response()->json($faces);
    }
}
