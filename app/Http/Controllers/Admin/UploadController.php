<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Brand;
use App\Models\Upload;
use Image;
class UploadController extends Controller 
{
    // drop zone functions
    public function uploadFile(Request $request){
        $file=$request->file;
        $fileArr = array();
        if ($file){
            $type = array( 
                "jpg" => "image",
                "jpeg" => "image",
                "png" => "image",
                "svg" => "image",
                "webp" => "image",
                "gif" => "image",
                "mp4" => "video",
                "mpg" => "video",
                "mpeg" => "video",
                "webm" => "video",
                "ogg" => "video",
                "avi" => "video",
                "mov" => "video",
                "flv" => "video",
                "swf" => "video",
                "mkv" => "video",
                "wmv" => "video",
                "wma" => "audio",
                "aac" => "audio",
                "wav" => "audio",
                "mp3" => "audio",
                "zip" => "archive",
                "rar" => "archive",
                "7z" => "archive",
                "doc" => "document",
                "txt" => "document",
                "docx" => "document",
                "pdf" => "document",
                "csv" => "document",
                "xml" => "document",
                "ods" => "document",
                "xlr" => "document",
                "xls" => "document",
                "xlsx" => "document"
            );


            $upload= new Upload();
            $path =  'uploads/files/';
            $ext=$file->getClientOriginalExtension();
            $imgHash = time().md5(rand(0,10));
            $filename =$imgHash.".".$ext;
            $fileNam = $request->file('file')->getClientOriginalName();
            $arr = explode('.', $request->file('file')->getClientOriginalName());
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }
            if ($type[$ext] == 'image') {

                try {

                    $img = \Image::make($request->file('file')->getRealPath())->encode();
                    $height = $img->height();
                    $width = $img->width();
                    if ($width > $height && $width > 1500) {
                        $img->resize(1500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > 1500) {
                        $img->resize(null, 800, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }

                    $ext= "webp";
                    $filename =$imgHash.".".$ext;
                    $img->save($path.$filename);
                    $size = $img->filesize();

                } catch (\Exception $e) {
                    //dd($e);
                }
            }else{

                $file->save($path.$filename);

            }

            $fileArr["file"]["filename"] = $filename;
            $fileArr["file"]["name"] = $fileNam;
            $fileArr["file"]["ext"] = $ext;
            $fileArr["file"]["size"] = $size;
            $fileArr["file"]["date"]=date('d-m-y');
            
            $upload->extension = $ext;
            $upload->file_name = $filename;
            $upload->user_id = "2";
            $upload->type = $type[$ext];
            $upload->file_size = $size;
            
            //$files=json_encode($fileArr);
            if($upload->save()){
                return response()->json(['status'=>'success']);

            }
        }
        else{
            return response()->json(['status'=>'fail','msg'=>'Please select a file']);
        }
    }
    public function deleteFile(Request $request){

        $file=$request->file;
        if ($file){
            $path =  'uploads/files/';
            //code for remove old file
            if ($file != '') {
                $file_old = $path . $file;
                if (file_exists($file_old)){
                    @unlink($file_old);
                }
            }
            return response()->json(['status'=>'success','msg'=>'File deleted successfully']);
        }else{
            return response()->json(['status'=>'fail','msg'=>'Failed to delete File']);
        }

    }

    public function Count(Request $request){
        // return "hello";
        $filterTitle=$request->filterTitle;
        $filterLength=$request->filterLength;
        $result=Upload::query();

        if ($filterTitle !=''){
            $result = $result->where('file_original_name', 'like', '%' . $filterTitle . '%');
        }
        $count = $result->count();
        if ($count>0){

            return response()->json(['status'=>'success','data'=>$count]);

        }else{
            return response()->json(['status'=>'fail','msg'=>'No Data Found']);
        }

    }

    public function list(Request $request){ 

        $filterTitle=$request->filterTitle;
        $filterLength=$request->filterLength;
        $result=Upload::query();


        if ($filterTitle !=''){
            $result = $result->where('file_original_name', 'like', '%' . $filterTitle . '%');
        }
        $uploads = $result->take($filterLength)->skip($request->offset)->orderBy('id','DESC')->get();

        if(isset($uploads) && sizeof($uploads)>0){
            $html = "";
            foreach($uploads as $upload){

                if($upload->file_name==""){

                    $img ='<img class="img-fluid" height="76" src="'.asset('assets/store.png').'" alt="upload pic" />';
                }else{
                    $img ='<img class="img-fluid" height="76" src="'.asset('uploads/files/'.$upload->file_name).'" alt="upload pic" />';
                }
                $html.='
                <div class="col">
                <figure class="card border-1">
                    
                    <div class="card-header bg-white text-center mt-0 pt-0 px-2">
                        <div class="text-end">
                            <div class="dropdown px-0 m-0">
                                <a href="#" data-bs-toggle="dropdown" class="font-sm"> <i
                                        class="material-icons md-more_horiz"></i> </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/uploads/files/'.$upload->file_name.'" download="" >Download</a>
                                    <a class="dropdown-item" href="javascritpt:;" onclick="copyUrl(this)" data-url="'.asset('uploads/files/'.$upload->file_name).'" >Copy url</a>
                                    <a class="dropdown-item text-danger btnDelete" id="'.$upload->id.'" href="javascript:void(0)">Delete</a>
                                </div>
                            </div>
                        </div>
                        '.$img.'
                    </div>
                    <figcaption class="card-body text-center">
                        <h6 class="card-title m-0">'.ucwords($upload->file_original_name).'</h6>
                        <h6 class="card-title m-0">'.ucwords($upload->id).'</h6>
                    </figcaption>
                    
                </figure>
            </div>      
                ';

              

            }

            return response(['status'=>'success','rows'=>$html]);
        }else{

            return response(['status'=>'fail']);
        }

    }

    public function deleteGalleryItem($id){
        $upload=Upload::find($id);
        $path =  'uploads/files/';
        $currentImage=$upload->file_name;
        if($currentImage !=NULL){
            if (file_exists($path.$currentImage)){
                @unlink($path.$currentImage);
            }
        }        
        $upload->delete();
        if ($upload){
            return response()->json(['status'=>'success','msg'=>'upload is Deleted']);
        }
        return response()->json(['status'=>'fail','msg'=>'failed to delete upload']);
    }
}
