<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Cards;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class ControllerCards extends Controller
{
    // public function LoginBasic( Request $request ) {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);
 
    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('dashboard');
    //     }
        
    //     return response()->json([
    //         'code'     =>  200,
    //         'message'  => 'Error.'
    //     ], 200);
    // }

    //Listar todos los registros
    public function Index( Request $request ){
        $info = DB::table('cards')->whereNull('cards.deleted_at')
            ->leftJoin('users', 'users.id', '=', 'cards.id_user')
            ->leftJoin('list_expansion', 'list_expansion.id', '=', 'cards.id_expansion')
            ->leftJoin('list_type', 'list_type.id', '=', 'cards.id_type')
            ->leftJoin('list_rarity', 'list_rarity.id', '=', 'cards.id_rarity')
            ->select('cards.id','cards.name','cards.first_edition','list_expansion.name as expansion','list_type.name as type','list_rarity.name as rarity','cards.price','cards.img','users.name as user')->get();
        return response()->json([
            'data'       => $info, 
            'code'     =>  200,
            'message'  => 'Exitoso.'
        ], 200);
    }

    //crear registro
    public function Insert( Request $request ){
        DB::beginTransaction();
        try {
            $attributes = array(
                'name'          =>   'Nombre',
                'first_edition' =>   'Es primera edición',
                'expansion'     =>   'Expansión',
                'type'          =>   'Tipo',
                'rarity'        =>   'Rareza',
                'price'         =>   'Precio',
            );
            $validator = Validator::make($request->all(), [
                'name'   => ['required'],
                'first_edition'   => ['required'],
                'expansion'   => ['required'],
                'type'   => ['required'],
                'rarity'   => ['required'],
                'price'   => ['required'],
            ]);
            $validator->setAttributeNames($attributes);
            if($validator->fails()) {
                return response()->json([
                    'error'    => $validator->errors(), 
                    'id'       => $request->id, 
                    'code'     =>  200,
                    'message'  => 'Error al validar'
                ], 200);
            }
            $filename = null;

            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $docum = $file->getClientOriginalName('img');
                $tmp = explode('.',$docum);
                $extension = strtolower(end($tmp));

                if($extension !== 'jpg' && $extension !== 'jpge' && $extension !== 'webp' && $extension !== 'gif' && $extension !== 'bmp' && $extension !== 'png' && $extension !== 'svg' && $extension !== 'jpeg'){
                    return response()->json([
                        'id'       => 0, 
                        'code'     =>  200,
                        'message'  => 'Error en el formato de la imagen.'
                    ], 200);
                }
                
                $filename = date('d_m_Y_h_i_s').$extension;
                $path_full = Storage::putFileAs(
                    'public/tarjeta/', $file, $filename
                );
            }
            $new = new Cards();
                $new->name = $request->name;
                $new->first_edition = $request->first_edition;
                $new->id_expansion = $request->expansion;
                $new->id_type = $request->type;
                $new->id_rarity = $request->rarity;
                $new->price = $request->price;
                $new->img = $filename;
                // $new->id_user = Auth::user()->id;
                $new->id_user = 1;
            $new->save();

            DB::commit();   
            return response()->json([
                'id'       => $new->id, 
                'code'     =>  200,
                'message'  => 'Registro exitoso.'
            ], 200);

        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'id'       => 0, 
                'code'     =>  200,
                'message'  => 'Error.'
            ], 200);
        }
    }

    //modificar registro
    public function Update( Request $request ){
        DB::beginTransaction();
        try {
            $attributes = array(
                'id'            =>   'Identificador',
                'name'          =>   'Nombre',
                'first_edition' =>   'Es primera edición',
                'expansion'     =>   'Expansión',
                'type'          =>   'Tipo',
                'rarity'        =>   'Rareza',
                'price'         =>   'Precio',
            );
            $validator = Validator::make($request->all(), [
                'id'   => ['required'],
                'name'   => ['required'],
                'first_edition'   => ['required'],
                'expansion'   => ['required'],
                'type'   => ['required'],
                'rarity'   => ['required'],
                'price'   => ['required'],
            ]);
            $validator->setAttributeNames($attributes);
            if($validator->fails()) {
                return response()->json([
                    'error'    => $validator->errors(), 
                    'id'       => $request->id, 
                    'code'     =>  200,
                    'message'  => 'Error al validar'
                ], 200);
            }

            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $docum = $file->getClientOriginalName('img');
                $tmp = explode('.',$docum);
                $extension = strtolower(end($tmp));

                if($extension !== 'jpg' && $extension !== 'jpge' && $extension !== 'webp' && $extension !== 'gif' && $extension !== 'bmp' && $extension !== 'png' && $extension !== 'svg' && $extension !== 'jpeg'){
                    return response()->json([
                        'id'       => 0, 
                        'code'     =>  200,
                        'message'  => 'Error en el formato de la imagen.'
                    ], 200);
                }
                
                $filename = date('d_m_Y_h_i_s').$extension;
                $path_full = Storage::putFileAs(
                    'public/tarjeta/', $file, $filename
                );
            }

            $update = Cards::find($request->id);
                $update->name = $request->name;
                $update->first_edition = $request->first_edition;
                $update->id_expansion = $request->expansion;
                $update->id_type = $request->type;
                $update->id_rarity = $request->rarity;
                $update->price = $request->price;
                if ($request->hasFile('file')) {
                    $update->img = $filename;
                }
                $update->id_user = 1;
                // $update->id_user = Auth::user()->id;
            $update->save();

            DB::commit();   
            return response()->json([
                'id'       => $update->id, 
                'code'     =>  200,
                'message'  => 'Modificaciòn exitosa.'
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'id'       => $request->id, 
                'code'     =>  200,
                'message'  => 'Error.'
            ], 200);
        }
    }

    //borrado logico del registro
    public function Delete( Request $request ){
        DB::beginTransaction();
        try {
            $attributes = array(
                'id'            =>   'Identificador'
            );
            $validator = Validator::make($request->all(), [
                'id'   => ['required']
            ]);
            $validator->setAttributeNames($attributes);
            if($validator->fails()) {
                return response()->json([
                    'error'    => $validator->errors(), 
                    'id'       => $request->id, 
                    'code'     =>  200,
                    'message'  => 'Error al validar'
                ], 200);
            }
            $delete = Cards::find($request->id);
                $delete->deleted_at = date('Y-m-d H:i:s');
                $delete->id_user = 1;
                // $delete->id_user = Auth::user()->id;
            $delete->save();

            DB::commit();  
            return response()->json([
                'id'       => $delete->id, 
                'code'     =>  200,
                'message'  => 'Eliminaciòn exitosa.'
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'id'       => $request->id, 
                'code'     =>  200,
                'message'  => 'Error.'
            ], 200);
        }
    }

    //informacion de un registro
    public function Detal( Request $request ){
        $attributes = array(
            'id'            =>   'Identificador'
        );
        $validator = Validator::make($request->all(), [
            'id'   => ['required']
        ]);
        $validator->setAttributeNames($attributes);
        if($validator->fails()) {
            return response()->json([
                'error'    => $validator->errors(), 
                'id'       => $request->id, 
                'code'     =>  200,
                'message'  => 'Error al validar'
            ], 200);
        }

        $info = Cards::find($request->id);

        return response()->json([
            'data'     => $info, 
            'id'       => $request->id, 
            'code'     =>  200,
            'message'  => 'Detalle exitoso.'
        ], 200);
    }

    public function List_Expansion() {
        $info = DB::table('list_expansion')->whereNull('deleted_at')->select('id', 'name')->get();
        return response()->json([
            'data'     => $info, 
            'code'     =>  200,
            'message'  => 'Detalle exitoso.'
        ], 200);
    }

    public function List_Type() {
        $info = DB::table('list_type')->whereNull('deleted_at')->select('id', 'name')->get();
        return response()->json([
            'data'     => $info, 
            'code'     =>  200,
            'message'  => 'Detalle exitoso.'
        ], 200);
    }

    public function List_Rarity() {
        $info = DB::table('list_rarity')->whereNull('deleted_at')->select('id', 'name')->get();
        return response()->json([
            'data'     => $info, 
            'code'     =>  200,
            'message'  => 'Detalle exitoso.'
        ], 200);
    }
}
