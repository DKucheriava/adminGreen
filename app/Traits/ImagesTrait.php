<?php
namespace App\Traits;
use Storage;
use File;
use Image; //Intervention Image
// use Illuminate\Support\Facades\Storage;
 //Laravel Filesystem

trait ImagesTrait
{

    public function deleteImage($path ){
       
        if($path != ''){
          
            Storage::disk('s3')->exists($path)?Storage::disk('s3')->delete($path):'';
        }
        return true;
    }

    public function getImageSize($url){
         $size = 0;
        if(Storage::disk('s3')->exists($url)){
            $img = get_headers(env('BUCKET_URL').$url, 1);
            $size += (int) @$img['Content-Length'];
        }
        

        return $size;
    }

    public function  movefiles($filePath, $image){
        if($filePath != '' && $image != ''){
            Storage::disk('s3')->put($filePath, $image);

        }
    }
    public function  uploadimage($directory, $type, $uploadedfile, $prevfile = ''){
      
        if($prevfile != ''){
           if(File::exists($directory.$prevfile)) {
                File::delete($directory.$prevfile);
            }
        }
        if (! File::exists(public_path().'/'.$directory.'/')) {
            File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
        }
        
        if($uploadedfile!=''){
            $profile = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $profile);
           
            $data = array('success' => 200, 'message' => 'Image Updated successfully', 'image' => $profile);
        }else{
            $data = array('success' => 400, 'message' => 'Something went wrong, Please try again.');

        }
        return $data;
    }

    public function  uploadimageNew($directory, $type, $uploadedfile, $prevfile = ''){
      
        if($prevfile != ''){
           if(File::exists($directory.$prevfile)) {
                File::delete($directory.$prevfile);
            }
        }
        if (! File::exists(public_path().'/'.$directory.'/')) {
            File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
        }
        
        if($uploadedfile!=''){
            $profile = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            
            $destinationPath = public_path($directory);
            // dd($destinationPath);
            $img = Image::make($destinationPath)->resize(400, 150, function($constraint) {
               $constraint->aspectRatio();
            });

            $uploadedfile->move($destinationPath, $profile);
            
           
            $data = array('success' => 200, 'message' => 'Image Updated successfully', 'image' => $profile);
        }else{
            $data = array('success' => 400, 'message' => 'Something went wrong, Please try again.');

        }
        return $data;
    }

}
