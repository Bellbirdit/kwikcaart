<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentNotification;




class CommentController extends Controller
{
    public function fileUpload(Request $request): \Illuminate\Http\JsonResponse
    {
        $file=$request->file;
        $fileCount=$request->fileCount;
        $response = array();
        if ($file){
            $path =  'uploads/files/';
            $fileNam=$file->getClientOriginalName();
            $ext=$file->getClientOriginalExtension();
            $size=$file->getSize();
            if ($size >8000000){
                $response["status"] = "fail";
                $response["msg"] = "Please upload file under <b>8MB</b> size.";
            }else{
                $imgHash = time().md5(rand(0,10));
                $filename = "message".$imgHash.".".$ext;
                $move=$file->move($path, $filename);
                $response["status"] = "success";
                $response["msg"] = "Files has been uploaded";
                //$file = array();
                $response["file"]["filename"] = $filename;
                $response["file"]["name"] = $fileNam;
                $response["file"]["ext"] = $ext;
                $response["file"]["fileCount"]=$fileCount;
                $response["file"]["size"] = $size;
            }
        }else{
            $response["status"] = "fail";
            $response["msg"] = "Please select file.";
        }
        return response()->json($response);
    }


     public function fileDelete(Request $request){
        $currentImage = $request->file;
        if (!is_null($currentImage)){
            $path =  'uploads/files/';
            if (file_exists($path.$currentImage)){
                @unlink($path.$currentImage);
            }
            
            $response["status"] = "success";
            $response["msg"] = "file is deleted";
        }else{
            $response["status"] = "fail";
            $response["msg"] = "file is not selected";
        }

        return response()->json($response);
    }




    public function AddComment(Request $req)
    {
         
             $validator=Validator::make($req->all(),[
            'comment'=>'required',
            
             ]);

        if($validator->fails()){
            return response()->json(['status'=>'fail','msg'=>$validator->errors()->all()]);
        }
        if($req->myFile==""){
            $files = NULL;
        }else{
            $files = json_encode($req->myFile);
        }
       
        
        
        $comment= new Comment();
            
        $user_id=Auth::user()->id;
        $comment->support_ticket_id=$req->support_ticket_id;
        $comment->user_id=$user_id;
        $comment->comment=$req->comment;
        $comment->attachments=$files;
        $comment->save();
       
        if($comment){
            return response()->json(['status'=>'success','msg'=>'Comment added successfully']);
        }else{
            return response()->json(['status'=>'fail','msg'=>'Comment not added']);
        }
    }
    public function CommentView(Request $req){
        $comments=Comment::where('support_ticket_id',$req->id)->get();
        

        $imgExt= array(
            "png",
            "jpg",
            "jpeg",
            "PNG"
        );

        
        if($comments){
            $html="";
            foreach($comments as $comment){


                $Annoucefiles="";
                $extension="";
                if(! empty($comment)){
                    if($comment->attachments != NULL){
                        
                        $Annoucefiles=json_decode($comment->attachments);

                        
                    }else{
                        $Annoucefiles="Empty";
                    }
                }else{
                    $Annoucefiles="Empty"; 
                }
                if($Annoucefiles != "Empty"){
                        
                        foreach($Annoucefiles as $af){
                            if(in_array($af->ext,$imgExt)){
                                $extension.='
                                        
                                         <div class="col-lg-2">
                                            <div class="px-2 pb-1">
                                                <div class="">
                                                    <img src="'.asset('/uploads/files/'.$af->filename).'" id="myImg"
                                                        class=" rounded img-fluid product-img myImg" alt=""
                                                        style="height:80px; width:80px;" />
                                                </div>
                                                <span>
                                                    <a href="/uploads/files/'.$af->filename.'" download="'.$af->name.'">
                                                        <small><i class="fa fa-download"></i> Download</small>
                                                    </a>
                                                </span>
                                            </div>
                                          </div>
                                       
                                ';
                            }else{
                                $extension.='
                                        <div class="col-lg-2">
                                            <div class="px-2 pb-1">
                                                <div class="  align-items-center">
                                                    <img src="'.asset('asset/images/logo/jpg.png').'" id="myImg"
                                                        class=" rounded img-fluid product-img myImg" alt=""
                                                        style="height:80px; width:80px; " />
                                                </div>
                                                <span>
                                                    <a href="/uploads/files/'.$af->filename.'" download="'.$af->name.'">
                                                        <small><i class="fa fa-download"></i> Download</small>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                ';
                            }
                        }

                }

                $user=User::where('id',$comment->user_id)->first();
                $html.='
               
                    <div class="col-lg-12 col-md-12 col-sm-12">
                    
                        <div class="card" style="background-color: #ffffff;color:black">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar me-75">
                                        '.($user->profile_pic != NULL ? '
                                            <img src="'.asset('uploads/files/'.$user->profile_pic).'" width="38" height="38" alt="Avatar" />
                                    
                                        ':'
                                                <img src="'.asset('asset/images/logo/user.jpg').'" width="38" height="38" alt="Avatar" />
                                        
                                        ').'
                                    </div>
                                    
                                    <div class="author-info">
                                    
                                        <h6 class="fw-bolder mb-25">'.ucwords($user->first_name.' '.$user->last_name).'</h6>
                                        <p class="card-text small text-muted">'.date('M d,Y h:m a ',strtotime($user->created_at)).'</p>
                                       
                                        
                                    </div>
                                        
                                </div>
                            </div>
                                <div>
                                    <p class="card-text pt-1 pb-1 px-2">
                                        '.$comment->comment.'
                                    </p>
                                </div>
                            <div class="row">
                                '.$extension.'
                            </div>
                        </div>
                    </div>
                     ';
            }
            return response()->json(['status'=>'success','rows'=>$html]);
        }else{
            return response()->json(['status'=>'fail']);
        }
    }
    public function CommentDelete($id){
        $delete=Comment::find($id);
        $currentImage=$delete->attachments;
        $path =  'uploads/files/';

        if (file_exists($path.$currentImage)){
            @unlink($path.$currentImage);
        }
        $delete->delete();
        if($delete){
            return response()->json(['status'=>'success','msg'=>'comment deleted']);
        }else{
            return response()->json(['status'=>'fail','msg'=>'comment not deleted']);
        }
    }
}