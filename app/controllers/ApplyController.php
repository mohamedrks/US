<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 9:42 AM
 */
class ApplyController extends \BaseController
{
    public function upload()
    {


        // getting all of the post data

        // setting up rules
        $rules = array(
            'file' => 'required', // 'image|max:3000',
            'product_id' => 'required'
        );
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return Response::json(array("error" => "Failed: upload" . $validator->messages()));

        } else {
            // checking file is valid.

            if (Input::file('file')->isValid()) {

                $product_picture = new ProductPictures;

                $product_picture->user_id = 50; // Authorizer::getResourceOwnerId();
                $product_picture->product_id = Input::get('product_id');


                $file = Input::file('file');
                $destinationPath = 'uploads/products/';
                $ext = $file->guessClientExtension(); // Get real extension according to mime type
                $fullname = $file->getClientOriginalName(); // Client file name, including the extension of the client
                $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext; // Hash processed file name, including the real extension
                $picture = Image::make($file->getRealPath());
                // crop the best fitting ratio and resize image
                $picture->fit(1024, 683)->save(public_path($destinationPath . $hashname));
                $picture->fit(170, 140)->save(public_path('uploads/product_thumbnails/' . $hashname));

                $product_picture->thumbnail_location = 'uploads/product_thumbnails/' . $hashname;
                $product_picture->filename = $destinationPath . $hashname;
                $product_picture->save();

                return Response::json(array("status" => "successful"), 200);

            } else {

                // sending back with error message.
                return Response::json(array("status" => "fail"), 400);

            }
        }
    }


    public function uploadKeyFacts()
    {

        // getting all of the post data

        // setting up rules
        $rules = array(
            'file' => 'required', 'image|max:3000',
            'description' => 'required'
        );

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return Response::json(array("error" => "Failed: upload" . $validator->messages()));

        } else {
            // checking file is valid.

            if (Input::file('file')->isValid()) {


                $file = Input::file('file');
                $destinationPath = public_path('uploads/KeyFacts');
                $ext = $file->getClientOriginalExtension(); // Get real extension according to mime type

                if (strcmp($ext, 'pdf')) {

                    return Response::json(array("status" => "fail"), 502);

                } else {

                    $fullname = $file->getClientOriginalName(); // Client file name, including the extension of the client
                    $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext; // Hash processed file name, including the real extension

                    $nutritionalResource = new NutritionResource;
                    $nutritionalResource->resource_type = 2;
                    $nutritionalResource->description = Input::get('description');
                    $nutritionalResource->link = $hashname;
                    $nutritionalResource->save();

                    $allProfessionals = DB::table('users_groups')
                        ->where('users_groups.group_id','=',1)
                        ->select('users_groups.user_id')
                        ->get();

                    foreach($allProfessionals as $item ){

                        $usersSelectedResource = new UsersSelectedResource;

                        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($item->user_id);

                        $usersSelectedResource->users()->associate($user);
                        $usersSelectedResource->NutritionResource()->associate($nutritionalResource);
                        $usersSelectedResource->status = 0;
                        $usersSelectedResource->save();
                    }

                    $upload_success = $file->move($destinationPath, $hashname);

                    return Response::json(array("status" => "successful", 'response' => 'success'), 200);
                }


            } else {

                // sending back with error message.
                return Response::json(array("status" => "fail"), 400);

            }
        }
    }

    public function uploadVideos()
    {

        // getting all of the post data

        // setting up rules
        $rules = array(
            'file' => 'required', // 'image|max:3000',
            'description' => 'required'
        );

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return Response::json(array("error" => "Failed: upload" . $validator->messages()));

        } else {
            // checking file is valid.

            if (Input::file('file')->isValid()) {


                $file = Input::file('file');
                $destinationPath = public_path('uploads/Videos');
                $ext = $file->getClientOriginalExtension(); // Get real extension according to mime type
                $fullname = $file->getClientOriginalName(); // Client file name, including the extension of the client

                if (strcmp($ext,'mp4')) {

                    return Response::json(array("status" => "fail"), 502);

                } else {
                    $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext; // Hash processed file name, including the real extension

                    $nutritionalResource = new NutritionResource;
                    $nutritionalResource->resource_type = 1;
                    $nutritionalResource->description = Input::get('description');
                    $nutritionalResource->link = $hashname;
                    $nutritionalResource->save();


                    $allProfessionals = DB::table('users_groups')
                        ->where('users_groups.group_id','=',1)
                        ->select('users_groups.user_id')
                        ->get();

                    foreach($allProfessionals as $item ){

                        $usersSelectedResource = new UsersSelectedResource;

                        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($item->user_id);

                        $usersSelectedResource->users()->associate($user);
                        $usersSelectedResource->NutritionResource()->associate($nutritionalResource);
                        $usersSelectedResource->status = 0;
                        $usersSelectedResource->save();
                    }

                    $upload_success = $file->move($destinationPath, $hashname);

                    return Response::json(array("status" => "successful", 'response' => $ext), 200);
                }
            } else {

                // sending back with error message.
                return Response::json(array("status" => "fail"), 400);

            }
        }
    }

    public function show($id)
    {

        return $product = ProductPictures::find($id);
    }

    public function showImageByProduct($product_id)
    {

        $userId = 50; // Authorizer::getResourceOwnerId();

        $productPictures = ProductPictures::where('user_id', '=', $userId)->where('product_id', '=', $product_id)->get();

        return $productPictures;
    }

    public function getPicturesByProduct($product_id)
    {

        $productPictures = ProductPictures::where('product_id', '=', $product_id)->get();

        return $productPictures;
    }

    public function destroy($id)
    {
        $userId = 50; //Authorizer::getResourceOwnerId();
        $productPicture = ProductPictures::find($id);

        if ($userId == $productPicture->user_id) {
            $productPicture->delete();

            return Response::json(array("status" => "successful"), 200);
        } else return Response::json(array("status" => "fail"), 400);
    }


    public function mainPic()
    {
        $productId = Input::get('product_id');
        $id = Input::get('id');


        $pictures = DB::table('product_pictures')
            ->where('product_id', $productId)
            ->where('main_pic', 1)
            ->update('main_pic', 0);

        $picture = ProductPictures::find($id);
        $picture->main_pic = 1;
        $picture->save();

        return Response::json(array("status" => "successful"), 200);


    }


}