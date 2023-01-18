<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $articles = Article::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->simplePaginate(10);

        return view('admin.articles.idex', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Obtener cateforias publicas
        $categories = Category::select('id', 'name')
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
    public function store(ArticleRequest $request)
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
