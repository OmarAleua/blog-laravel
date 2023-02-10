<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mostrar los articulos en el Admin
        $user = Auth::user(); //Auth traer la info del usu autenticado - all
        $articles = Article::where('user_id', $user->id) //user_id es la clave foranea de article con usuarios
            ->orderBy('id', 'desc')
            ->simplePaginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Obtener categorias publicas
        $categories = Category::select('id', 'name') //otra forma
            ->where('status', '1')
            ->get();

        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request) //ArticleRequest contiene las validaciones
    {
        // 
        $request->merge([
            'user_id' => Auth::user()->id,
        ]);

        //guardo la solicitud en una variable
        $article = $request->all();

        //validar si hay un archivo en el request
        if ($request->hasFile('image')) {
            $article['image'] = $request->file('image')->store('articles');
        }

        //es para guardar esta informacion
        Article::create($article);

        //redireccionamos a index
        return redirect()->action([ArticleController::class, 'index'])
            ->with('success-create', 'Articulo creado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //mostrar los comentarios para todos los usuarios y roles
        $comments = $article->comments()->simplePaginate(5);

        return view('subscriber.articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //Editar categorias publicas - lo traje del obtener (Metodo create)
        $categories = Category::select('id', 'name') //otra forma
            ->where('status', '1')
            ->get();

        return view('admin.articles.edit', compact('categories', 'article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        //si el usuario sube una nueva imagen - que elimine la anterior y que asigne la nueva imagen
        if($request->hasFile('image')){
            //eliminar la imagen anterior
            File::delete(public_path('storage/' . $article->image));
            //ahora que la nueva imagen se asigne
            $article['image'] = $request->file('image')->store('articles');
        }

        //actualizar los datos
        $article->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'introduction' => $request->introduction,
            'body' => $request->body,
            'user_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'status' => $request->status,

        ]);

        //redireccionamos a index
        return redirect()->action([ArticleController::class, 'index'])
            ->with('success-update', 'Articulo modificado con exito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //Eliminar la imagen del articulo
        if($article->image){
            File::delete(public_path('storage/' . $article->image));
        }

        //Eliminar articulo
        $article->delete();

        //redireccionamos a index
        return redirect()->action([ArticleController::class, 'index'], compact($article))
            ->with('success-delete', 'Articulo eliminado con exito');
    }
}
