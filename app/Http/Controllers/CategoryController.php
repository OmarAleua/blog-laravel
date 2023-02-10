<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Mostrar categorias en el admin
        $categories = Category::orderBy('id', 'desc')
            ->simplePaginate(8);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.categories.create'); //admin y categories (admin.categories) son carpetas y create es la vista
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //almacenara todo lo que traiga el request
        $category = $request->all();

        //validar si hay un archivo en el request
        if($request->hasFile('image')){
            $category['image'] = $request->file('image')->store('categories'); //la guarda en la carpeta storage(categories)
        }

        //guardar informacion
        Category::create($category);

        //redireccionamos a index
        return redirect()->action([CategoryController::class, 'index'])
            ->with('success-create', 'Categoria creada con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //retorna la vista
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        //si el usuario sube una imagen
        if($request->hasFile('image')){
            //eliminamos la imagen anterior
            File::delete(public_path('storage/'.$category->image));
            //asignamos la nueva imagen
            $category['image'] = $request->file('image')->store('categories');
        }

        //actualizar datos
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
            'is_featured' => $request->is_featured,
        ]);

        //redireccionamos a index
        return redirect()->action([CategoryController::class, 'index'], compact('category'))
            ->with('success-update', 'Categoria modificada con exito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //eliminar imagen de la categoria
        if($category->image){
            File::delete(public_path('storage/'.$category->image));
        }

        $category->delete();

        //redireccionamos a index
        return redirect()->action([CategoryController::class, 'index'], compact('category'))
            ->with('success-delete', 'Categoria eliminada con exito');
    }

    //filtrar articulos por categoria
    public function detail(Category $category){

        $articles = Article::where([
            ['category_id', $category->id],
            ['status', '1']
        ])
            ->orderBy('id', 'desc')
            ->simplePaginate(5);

        # Obtener las categorias con estado publico (1) y destacadas (1)
        $navbar = Category::where([ //Otra forma de pasar varias condiiones a una consulta
            ['status', '1'],
            ['is_featured', '1'],
        ])->paginate(3);
        
        return view('subscriber.categories.detail', compact('articles', 'navbar', 'category'));
    }
}
