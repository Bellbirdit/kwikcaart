<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Models\UserType;
use Auth;

class RoleController extends Controller
{
    public function addRole(Request $request){
      

        $role = Role::create([
            'name' => $request->name,
            'type'=>$request->type,
            'store_id' => Auth::user()->code

        ]);

        if ($role){

            return response()->json(['status'=>'success','msg'=>'Role added successfully']);

        }else{

            return response()->json(['status'=>'fail','msg'=>'FAILED! try again']);

        }
       
    }

    public function getRole($id){

        $role = Role::where('id',$id)->first();
        $type = UserType::where('id',$role->type)->first();

        if ($role){
            if($type==""){
                $t = "empty";
            }else{
                $t=$type;
            }
            return response()->json(['status'=>'success','data'=>$role,'type'=>$t]);

        }


        return response()->json(['status'=>'fail']);
    }

    
    public function deleteRole($id){

        $role=Role::where('id',$id)->delete();

        if ($role){
            return response()->json(['status'=>'success']);
        }
        return response()->json(['status'=>'fail']);
    }
    public function updateRole(Request $request){
       
        $role=Role::where('id',$request->id)->first();
        $role->name=$request->name;
    
        $role->save();

        if ($role){
            return response()->json(['status'=>'success']);
        }
        return response()->json(['status'=>'fail']);
    }

    public function Permission($id){
        $permission1=Permission::where('section','user_management')->where('type',1)->get();
        $permission2=Permission::where('section','product_management')->where('type',1)->get();
        $permission3=Permission::where('section','order_management')->where('type',1)->get();
        
        $permission5=Permission::where('section','general_setting')->where('type',1)->get();
        $permission6=Permission::where('section','storestaff_setting')->where('type',2)->get();

        $role=Role::where('id',$id)->first();

        return view('admin.roles.permission',['role'=>$role,'user_permission'=>$permission1,'product_permission'=>$permission2,'order_permission'=>$permission3,'general_setting'=>$permission5,'storestaff_setting'=>$permission6]);
    }

    public function addPermission(Request $request){
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->section = $request->section;
        $permission->type =Auth::user()->type;
        $permission->save();
        return response()->json(['status'=>'success','msg'=>'Permission created successfully']);
    }
    public function assignPermission(Request $request)
    {
        $role=Role::where('id',$request->role)->first();
        $permission=$request->permission;
        if(! empty($permission)){
            $allow=Permission::whereIn('id',$permission)->get();

            $final=$role->syncPermissions($allow);
            if ($final){
                return response()->json(['status'=>'success','msg'=>'Permission updated for Role']);
            }else{
                return response()->json(['status'=>'fail','msg'=>'Fail to add permission']);
            }

        }else{

            if(isset($role->permissions) && sizeof($role->permissions)>0){
                $role->revokePermissionTo($role->permissions);
            }
            return response()->json(['status'=>'success','msg'=>'Permission updated for Role']);

        }

    }


   
}