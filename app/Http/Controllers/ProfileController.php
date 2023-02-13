<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
        return view('subscriber.profiles.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, Profile $profile)
    {
        //en la variable user vamos a guardar la informacion del usuario
        $user = Auth::user();
        //vamos a comprobar si el usuario sube una foto
        //es decir, si encuentra alguna foto que elimine la anterior y asigne la nueva foto
        //sino que deje la misma foto que encuentra
        if ($request->hasFile('photo')){
            //eliminar foto anterior
            File::delete(public_path('storage/'.$profile->photo));
            //asignar nueva foto
            $photo = $request['photo']->store('profiles');
        }else{ 
            $photo = $user->profile->photo;
        }

        //asignar nombre y correo
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        //asigar foto
        $user->profile->photo = $photo;

        //guardar campos de usuario
        $user->full_name->save();
        $user->email->save();
        //guardar campo de profile
        $user->profile->save();
        //retornamos al edit, estamos accediendo al id del usuario
        return redirect()->route('profiles.edit', $user->profile->edit);
    }
    
}
