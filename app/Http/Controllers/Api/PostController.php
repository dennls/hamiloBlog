<?php

namespace App\Http\Controllers\Api;

use App\Models\Posts;
use App\Models\Comentarios;
use App\Models\Categorias;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Posts::select('id','titulo','imagen','resumen', 'categoria_id', 'fecha_publicacion')
                        ->with('categoria')
                        ->orderBy('id', 'DESC')->paginate(5);
        foreach ($blogs as $item) {
            $item->imagen = $item->getImagenUrl();
            $item->cant_comentarios = Comentarios::where('post_id', $item->id)->count();
        }
        $paraSlider = Posts::select('posts.id','posts.titulo','posts.imagen','categorias.nombre',
                                    'categoria_id', 'posts.fecha_publicacion',
                                    DB::raw('COUNT(comentarios.id) as cant_comentarios'))
                        ->join('comentarios', 'posts.id','comentarios.post_id')
                        ->join('categorias', 'posts.categoria_id','categorias.id')
                        ->groupBy('posts.id')
                        ->orderBy('cant_comentarios', 'DESC')
                        ->take(3)
                        ->get();
        foreach ($paraSlider as $slide) {
            $slide->imagen = $slide->getImagenUrl();
        }
        return response()->json(['mensaje' => 'Datos cargados correctamente', 'datos' => $blogs, 'paraSlider'=> $paraSlider]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Posts::with('usuario', 'categoria', 'comentarios', 'comentarios.usuario')->find($id);
        //return view('posts.show', compact('post'));
        if($post){
            $post->imagen = $post->getImagenUrl();
            $post->tags = json_decode($post->tags);
            $post->fecha_publicacion = Carbon::parse($post->fecha_publicacion)->diffForHumans(now());
            $post->cant_comentarios = $post->comentarios->count();

        }
        return response()->json(['mensaje' => 'Post cargado correctamente', 'datos' =>$post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function siguiente($id, string $antsig)
    {
        $actual = Posts::find($id);
        if($antsig == 'anterior'){
            $post = Posts::where('categoria_id', $actual->categoria_id)
                                ->where('id', '<', $actual->id)
                                ->orderBy('id', 'DESC')
                                ->first();
        }else{
            $post = Posts::where('categoria_id', $actual->categoria_id)
                                ->where('id', '>', $actual->id)
                                ->orderBy('id', 'ASC')
                                ->first();
        }

        return response()->json(['mensaje' => 'post encontrado',
        'postId' => ($post) ? $post->id: $actual->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function categorias()
    {
        $categorias = Categorias::select('id', 'nombre', 'imagen')->where('estado', true)->get();
        foreach ($categorias as $cat) {
            $cat->imagen = $cat->getImagenUrl();
            $cat->cant_posts = Posts::where('categoria_id', $cat->id)->count();
        }
        return response()->json(['mensaje' => 'Datos cargados', 'datos' => $categorias]);
    }
    public function filtrado(Request $request)
    {
        $busqueda = $request->busqueda;
        // quitar %20 y reemplazarlo por espacio
        $busqueda = str_replace('%20', ' ', $busqueda);

        $blogs = Posts::select('id', 'titulo', 'imagen', 'resumen', 'categoria_id', 'fecha_publicacion')
                        ->with('categoria')
                        ->where('tags', 'LIKE', '%'.$busqueda.'%')
                        ->orWhereHas('categoria', function($query) use ($busqueda){
                            $query->where('id', 'LIKE', '%'.$busqueda.'%')
                                    ->orWhere('nombre', 'LIKE', '%'.$busqueda.'%');
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate(10);

        foreach($blogs as $item){
            $item->imagen = $item->getImagenUrl();
            $item->cant_comentarios = Comentarios::where('post_id', $item->id)->count();
        }
        return response()->json([
            'mensaje' => 'Datos cargados correctamente.',
            'datos' => $blogs
        ]);
    }

    public function comentario(Request $request){
        $this->validate($request, [
            'post_id' => 'required|exists:posts,id',
            'comentario' => 'required|string|min:10|max:200'
        ]);
        $comment = new Comentarios();
        $comment->post_id = $request->post_id;
        $comment->comentario = $request->comentario;
        $comment->estado = true;
        $comment->fecha = now();
        $comment->usuario_id = auth()->user()->id;
        if($comment ->save()){
            return response()->json(['mensaje' => 'Comentario registrado', 'datos' => $comment]);
        }else{
            return response()->json(['mensaje' => 'El comentario no fue registrado']);
        }
    }
}