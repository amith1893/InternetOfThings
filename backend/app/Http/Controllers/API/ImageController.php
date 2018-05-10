<?php

namespace App\Http\Controllers\API;

use App\Models\Event;
use App\Models\Snapshot;
use App\Models\SnapshotFace;
use Carbon\Carbon;
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Core\ServiceBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HTTP_Request2 as HTTPRequest;

class ImageController extends Controller
{

    public function postProcessImage(Request $request)
    {
        $requestFace = new HTTPRequest('https://westcentralus.api.cognitive.microsoft.com/face/v1.0/detect');
        $requestFaceVerify = new HTTPRequest('https://westcentralus.api.cognitive.microsoft.com/face/v1.0/verify');
        $url = $requestFace->getUrl();
        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',

            // NOTE: Replace the "Ocp-Apim-Subscription-Key" value with a valid subscription key.
            'Ocp-Apim-Subscription-Key' => env('FACE_API', '')
        );
        $requestFace->setHeader($headers);
        $requestFaceVerify->setHeader($headers);

        $parameters = array(
            // Request parameters
            'returnFaceId' => 'true',
            'returnFaceLandmarks' => 'false',
        );

        $url->setQueryVariables($parameters);

        $requestFace->setMethod(HTTPRequest::METHOD_POST);
        $requestFaceVerify->setMethod(HTTPRequest::METHOD_POST);




        $vision = new VisionClient([
            'projectId' => 'first-rrf-123913'
        ]);

        // Annotate an image, detecting faces.
        $image = $vision->image(
            fopen($request->file('process_image')->path(), 'r'),
            ['faces']
        );

        $annotation = $vision->annotate($image);

        $authorizedFlag = false;
        $person = false;
        $authorizedProfile = '';
        $now = Carbon::now();

        // Create Snapshot for database
        $snap = Snapshot::create(['google_analysis' => json_encode($annotation->info())]);
        $snap->addMedia($request->file('process_image'))->toMediaCollection('images');
        $snapshotCollections = collect();
        $faces = $annotation->faces();
        if (isset($faces)) {
            foreach ($annotation->faces() as $key => $face) {
                $person = true;
                // Find Crop Sizes
                $x_size = $face->fdBoundingPoly()['vertices'][1]['x'] - $face->fdBoundingPoly()['vertices'][0]['x'];
                $y_size = $face->fdBoundingPoly()['vertices'][2]['y'] - $face->fdBoundingPoly()['vertices'][1]['y'];

                // Cut Faces from image and create SnapshotFace
                $snapshot = $snap->snapshotFace()->create(['snapshot_id' => $snap->id, 'name' => str_random(16)]);
                $path = storage_path('app/faces/' . $snapshot->name . '.jpg');

                $img = \Image::make($snap->getFirstMedia('images')->getPath())->crop($x_size, $y_size, $face->fdBoundingPoly()['vertices'][0]['x'], $face->fdBoundingPoly()['vertices'][0]['y'])->save($path);

                $snapshot->addMedia($path)->toMediaCollection('images');
                $face_url = url($snapshot->getFirstMediaUrl('images'));
                $snapshotCollections->push($face_url);

                // Request body
                $requestFace->setBody('{"url": "'.$face_url.'"}');


                try
                {
                    $response = $requestFace->send();
                    $body = json_decode($response->getBody());
                    $snapshot->face_id = $body[0]->faceId;
                    $snapshot->save();
                }
                catch (\HttpException $ex)
                {
                    echo $ex;
                    return response()->json($ex->getMessage());
                }

                // Guillermo
                $user1 = '7ae09d6b-6d2e-4472-bf73-e65894e2eaa3';
                // Manish
                $user2 = '308042b5-8487-463f-86a0-65536ff91360';
                // Amith
                $user3 = 'b25ee7e5-f95d-495d-bc8d-e0ab70d33fee';

                // Authenticate
                //
                /*
                $requestFaceVerify->setBody('{"faceId1": "'.$user1.'","faceId2": "'.$snapshot->face_id.'"}');
                $response = $requestFaceVerify->send();
                $body = json_decode($response->getBody());

                if($body->isIdentical)
                {
                    $authorizedProfile = 'Guillermo';
                    $authorizedFlag = true;
                    $event = Event::create([
                        'snapshot_id' => $snapshot->id,
                        'type' => 'authentication-attempt',
                        'icon' => 'fa fa-lock',
                        'message' => 'Eagle Eye - User verified as '.$authorizedProfile.' on '.$now->toDateTimeString()
                    ]);
                }
                */


                $requestFaceVerify->setBody('{"faceId1": "'.$user2.'","faceId2": "'.$snapshot->face_id.'"}');
                $response = $requestFaceVerify->send();
                $body = json_decode($response->getBody());

                if($body->isIdentical)
                {
                    $authorizedProfile = 'Manish';
                    $authorizedFlag = true;
                    $event = Event::create([
                        'snapshot_id' => $snapshot->id,
                        'type' => 'authentication-attempt',
                        'icon' => 'fa fa-lock',
                        'message' => 'Eagle Eye - User verified as '.$authorizedProfile.' on '.$now->toDateTimeString()
                    ]);
                }

                $requestFaceVerify->setBody('{"faceId1": "'.$user3.'","faceId2": "'.$snapshot->face_id.'"}');
                $response = $requestFaceVerify->send();
                $body = json_decode($response->getBody());

                if($body->isIdentical)
                {
                    $authorizedProfile = 'Amith';
                    $authorizedFlag = true;
                    $event = Event::create([
                        'snapshot_id' => $snapshot->id,
                        'type' => 'authentication-attempt',
                        'icon' => 'fa fa-lock',
                        'message' => 'Eagle Eye - User verified as '.$authorizedProfile.' on '.$now->toDateTimeString()
                    ]);
                }

            }

            // Create a face detected event for logging purposes
            $event = Event::create([
                'snapshot_id' => $snapshot->id,
                'type' => 'face-detected',
                'icon' => 'fa fa-camera',
                'message' => 'Eagle Eye has detected an individual on '.$now->toDateTimeString()
            ]);

            if(!$authorizedFlag)
                $unauthorized = true;
        } else {
            $unauthorized = false;
        }


        return response()->json(['snap'=>$snap->toArray(),'authorized' => $authorizedFlag, 'authorized_match' => $authorizedProfile, 'person' => $person, 'faces' => $snapshotCollections->toArray()]);

    }

    public function postProcessImageWeb(Request $request)
    {
        $requestFace = new HTTPRequest('https://westcentralus.api.cognitive.microsoft.com/face/v1.0/detect');
        $requestFaceVerify = new HTTPRequest('https://westcentralus.api.cognitive.microsoft.com/face/v1.0/verify');
        $url = $requestFace->getUrl();
        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',

            // NOTE: Replace the "Ocp-Apim-Subscription-Key" value with a valid subscription key.
            'Ocp-Apim-Subscription-Key' => env('FACE_API', '')
        );
        $requestFace->setHeader($headers);
        $requestFaceVerify->setHeader($headers);

        $parameters = array(
            // Request parameters
            'returnFaceId' => 'true',
            'returnFaceLandmarks' => 'false',
        );

        $url->setQueryVariables($parameters);

        $requestFace->setMethod(HTTPRequest::METHOD_POST);
        $requestFaceVerify->setMethod(HTTPRequest::METHOD_POST);




        $vision = new VisionClient([
            'projectId' => 'first-rrf-123913'
        ]);

        // Annotate an image, detecting faces.
        $image = $vision->image(
            fopen($request->file('process_image')->path(), 'r'),
            ['faces']
        );

        $annotation = $vision->annotate($image);

        $authorizedFlag = false;
        $authorizedProfile = '';
        $now = Carbon::now();

        // Create Snapshot for database
        $snap = Snapshot::create(['google_analysis' => json_encode($annotation->info())]);
        $snap->addMedia($request->file('process_image'))->toMediaCollection('images');
        $snapshotCollections = collect();
        $faces = $annotation->faces();
        if (isset($faces)) {
            foreach ($annotation->faces() as $key => $face) {
                // Find Crop Sizes
                $x_size = $face->fdBoundingPoly()['vertices'][1]['x'] - $face->fdBoundingPoly()['vertices'][0]['x'];
                $y_size = $face->fdBoundingPoly()['vertices'][2]['y'] - $face->fdBoundingPoly()['vertices'][1]['y'];

                // Cut Faces from image and create SnapshotFace
                $snapshot = $snap->snapshotFace()->create(['snapshot_id' => $snap->id, 'name' => str_random(16)]);
                $path = storage_path('app/faces/' . $snapshot->name . '.jpg');

                $img = \Image::make($snap->getFirstMedia('images')->getPath())->crop($x_size, $y_size, $face->fdBoundingPoly()['vertices'][0]['x'], $face->fdBoundingPoly()['vertices'][0]['y'])->save($path);

                $snapshot->addMedia($path)->toMediaCollection('images');
                $face_url = url($snapshot->getFirstMediaUrl('images'));
                $snapshotCollections->push($face_url);

                // Request body
                $requestFace->setBody('{"url": "'.$face_url.'"}');


                try
                {
                    $response = $requestFace->send();
                    $body = json_decode($response->getBody());
                    $snapshot->face_id = $body[0]->faceId;
                    $snapshot->save();
                }
                catch (\HttpException $ex)
                {
                    echo $ex;
                    return response()->json($ex->getMessage());
                }

                // Guillermo
                $user1 = '7ae09d6b-6d2e-4472-bf73-e65894e2eaa3';
                // Manish
                $user2 = '308042b5-8487-463f-86a0-65536ff91360';
                // Amith
                $user3 = 'b25ee7e5-f95d-495d-bc8d-e0ab70d33fee';

                // Authenticate
                //
                $requestFaceVerify->setBody('{"faceId1": "'.$user1.'","faceId2": "'.$snapshot->face_id.'"}');
                $response = $requestFaceVerify->send();
                $body = json_decode($response->getBody());

                if($body->isIdentical)
                {
                    $authorizedProfile = 'Guillermo';
                    $authorizedFlag = true;
                    $event = Event::create([
                        'snapshot_id' => $snapshot->id,
                        'type' => 'authentication-attempt',
                        'icon' => 'fa fa-lock',
                        'message' => 'Eagle Eye - User verified as '.$authorizedProfile.' on '.$now->toDateTimeString()
                    ]);
                }


                $requestFaceVerify->setBody('{"faceId1": "'.$user2.'","faceId2": "'.$snapshot->face_id.'"}');
                $response = $requestFaceVerify->send();
                $body = json_decode($response->getBody());

                if($body->isIdentical)
                {
                    $authorizedProfile = 'Manish';
                    $authorizedFlag = true;
                    $event = Event::create([
                        'snapshot_id' => $snapshot->id,
                        'type' => 'authentication-attempt',
                        'icon' => 'fa fa-lock',
                        'message' => 'Eagle Eye - User verified as '.$authorizedProfile.' on '.$now->toDateTimeString()
                    ]);
                }

                $requestFaceVerify->setBody('{"faceId1": "'.$user3.'","faceId2": "'.$snapshot->face_id.'"}');
                $response = $requestFaceVerify->send();
                $body = json_decode($response->getBody());

                if($body->isIdentical)
                {
                    $authorizedProfile = 'Amith';
                    $authorizedFlag = true;
                    $event = Event::create([
                        'snapshot_id' => $snapshot->id,
                        'type' => 'authentication-attempt',
                        'icon' => 'fa fa-lock',
                        'message' => 'Eagle Eye - User verified as '.$authorizedProfile.' on '.$now->toDateTimeString()
                    ]);
                }

            }

            // Create a face detected event for logging purposes
            $event = Event::create([
                'snapshot_id' => $snapshot->id,
                'type' => 'face-detected',
                'icon' => 'fa fa-camera',
                'message' => 'Eagle Eye has detected an individual on '.$now->toDateTimeString()
            ]);
        }


        return redirect('/');
    }


}

