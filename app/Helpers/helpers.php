<?php

/**
 * Global helpers file with misc functions.
 */
use App\Model\Album;
use App\Model\Medium;
use App\Model\Post;
use App\Model\User;

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('transUpl')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function transUpl($text)
    {
        if ($text == 'uploads')
            return 'Telechargements';
        return $text;
    }
}

if (! function_exists('formatDate')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function formatDate($date)
    {
        //return ($date)->format('d M Y H:i');
    }
}

if (! function_exists('getAlbums')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function getAlbums(){
        if (Auth::check()){
            return Album::where('owner_id',Auth::id())
                ->where('owner_table','users')
                ->where('name_canonical','<>','profile')->get();
        }
        return null;
    }
}

if (! function_exists('getAlbumFromId')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function getAlbumFromId($id){
        $qry = Album::where('id',$id)
            ->withTrashed()->where('owner_table','users');
        if ($qry->count() > 0){
            return $qry->first();
        }
        return null;
    }
}

if (! function_exists('getAlbumImagesFromAlbumId')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function getAlbumImagesFromAlbumId($id){
        $qry = Medium::where('discr','image')
            ->where('album_id',$id)
            ->limit(4);
        if ($qry->count() > 0){
            return $qry->get();
        }
        return null;
    }
}

if (! function_exists('getImageSrcFromMediaId')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function getImageSrcFromMediaId($id){
       return \App\Model\Photo::where('media_id',$id)->first();
    }
}

if (! function_exists('profilePicFromUserId')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function profilePicFromUserId($id){

       $qry = Medium::where('user_id',$id)
           ->where('discr','profil')
           ->where('type','image')
           ->where('actif',true);
       if($qry->count() > 0){
           return $qry->first()['url'];
       }
           return '/img/default_prof.jpg';
    }
}

if (! function_exists('banierePicFromUserId')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function banierePicFromUserId($id){
       $qry = Medium::where('user_id',$id)
           ->where('discr','baniere')
           ->where('type','image')
           ->where('actif',true);
       if($qry->count() > 0){
           return $qry->first()['url'];
       }
           return '/img/default_ban'.random_int(1,5).'.png';
    }
}

if (! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function.
     */
    function access()
    {
        return app('access');
    }
}

if (! function_exists('history')) {
    /**
     * Access the history facade anywhere.
     */
    function history()
    {
        return app('history');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('includeRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        $directory = $folder;
        $handle = opendir($directory);
        $directory_list = [$directory];

        while (false !== ($filename = readdir($handle))) {
            if ($filename != '.' && $filename != '..' && is_dir($directory.$filename)) {
                array_push($directory_list, $directory.$filename.'/');
            }
        }

        foreach ($directory_list as $directory) {
            foreach (glob($directory.'*.php') as $filename) {
                require $filename;
            }
        }
    }
}

if (! function_exists('getRtlCss')) {

    /**
     * The path being passed is generated by Laravel Mix manifest file
     * The webpack plugin takes the css filenames and appends rtl before the .css extension
     * So we take the original and place that in and send back the path.
     *
     * @param $path
     *
     * @return string
     */
    function getRtlCss($path)
    {
        $path = explode('/', $path);
        $filename = end($path);
        array_pop($path);
        $filename = rtrim($filename, '.css');

        return implode('/', $path).'/'.$filename.'.rtl.css';
    }
}

if (! function_exists('findOrCreateAlbum')) {


    function findOrCreateAlbumAndPost(User $user,$album_name,$post_title,$post_privacy,$post_text,$post_tags)
    {
        //Si c'est l'album par defaut
        if (strtolower($album_name) == 'uploads'){
            //On create un nouveau post pour les nouvelles tofs..
            $post = Post::create(['titre'=>$post_title,
                'titre_canonical'=>str_replace(" ","_",$post_title),
                'tags'=>$post_tags,'privacy'=>$post_privacy, 'text'=>$post_text,
                'type'=>'post', 'parent_id'=>0,
                'user_id'=>$user->id]);
            //Recupere l'album par defaut (uploads)
            $qry0 = Album::where([['name','=','uploads'],['user_id','=',$user->id]]);
            if ($qry0->count() > 0){
                $defaultAlbum = $qry0->first();
            }else{
                $defaultAlbum = Album::create(['name'=>'uploads',
                    'name_canonical'=> 'uploads',
                    'user_id'=>$user->id,
                    'post_id'=>0]);
            }
            $album = Album::create(['name'=>'auto_gen_'.time(),
                'name_canonical'=> str_replace(' ','_',$album_name),
                'auto_generated'=>true,
                'user_id'=>$user->id,
                'parent_id'=>$defaultAlbum->id,
                'post_id'=>$post->id]);
            return ['album'=>$album, 'post'=>$post,'post_created'=>true,'album_created'=>true];
        }

        //Recherche si l'album existe deja
        $qry = Album::where([['user_id','=',$user->id],['name','=',$album_name]]);
        if($qry->count() > 0){
            $album = $qry->first();
            $qry1 = Post::where('id',$album->post_id);
            $post_created = false;
            if ($qry1->count() > 0){
                $post = $qry1->first();
            }else{
                $post = Post::create(['titre'=>$post_title,
                    'titre_canonical'=>str_replace(" ","_",$post_title),
                    'tags'=>$post_tags,'privacy'=>$post_privacy, 'text'=>$post_text,
                    'type'=>'post', 'parent_id'=>0,
                    'user_id'=>$user->id]);
                $album->post_id = $post->id;
                $album->save();
                $post_created = true;
            }
            return ['album'=>$album, 'post'=>$post,'post_created'=>$post_created,'album_created'=>false];
        }
        /*Sinon on create un nouvel album et un nouveau post */

        $post = Post::create(['titre'=>$post_title,
            'titre_canonical'=>str_replace(" ","_",$post_title),
            'tags'=>$post_tags,'privacy'=>$post_privacy, 'text'=>$post_text,
            'type'=>'post', 'parent_id'=>0,
            'user_id'=>$user->id]);
        $album = Album::create(['name'=>$album_name,
        'name_canonical'=> str_replace(' ','_',$album_name),
            'user_id'=>$user->id,
            'post_id'=>$post->id]);
        return ['album'=>$album, 'post'=>$post,'post_created'=>true,'album_created'=>true];
    }
}


