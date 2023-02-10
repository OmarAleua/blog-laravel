<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //guardamos toda la consulta de 3 tablas en una variable $comments
        $comments = DB::table('comments')
            ->join('articles', 'comments.article_id', '=', 'articles.id')
            ->join('users', 'comments.user_id', '=', 'user.id')
            ->select('comments.value', 'comments.description', 'articles.title', 'users.full_name')
            ->where('articles.users_id', '=', Auth::user()->id)
            ->orderBy('articles.id', 'desc')
            ->get();

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        //verificar si en el articulo ya existe un comentario del usuario
        $result = Comment::where('user_id', Auth::user()->id)
            ->where('article_id', $request->article_id)->exists();

        //consulta para obtener el slug y estado del articulo comentado
        $article = Article::select('status', 'slug')->find($request->article_id);  
        
        //si no existe ningun comentario y si el estado del articulo es publico, comentar
        if(!$result and $article->status == 1){
            Comment::create([
                'value' => $request->value,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'article_id' => $request->article_id,
            ]);

            return redirect()->action([ArticleController::class, 'show'], [$article->slug]);
        }else{
            return redirect()->action([ArticleController::class, 'show'], [$article->slug])
                             ->with('succes-error', 'Solo puedes comentar una vez');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
        $comment->delete();

        return redirect()->action([CommentController::class, 'index'], compact('comment'))
                         ->with('succes-delete', 'Comentario eliminado con exito');
    }
}
