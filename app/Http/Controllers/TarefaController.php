<?php

namespace App\Http\Controllers;

use App\Models\tarefa;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use App\Mail\NovaTarefaMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TarefasExport;


class TarefaController extends Controller
{
    

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user_id = auth()->user()->id;
       $tarefas = Tarefa::where('user_id', $user_id)->paginate(10);
       return view('tarefa.index', ['tarefas' => $tarefas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = request()->all();
        $dados['user_id'] = auth()->user()->id;
       
       $tarefa = Tarefa::create($dados);
       $destinatario = auth()->user()->email;
       
       Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
       
       return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(tarefa $tarefa)
    {

         return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(tarefa $tarefa)
    {
        $user_id_logging = auth()->user()->id;
       
        if($tarefa->user_id == $user_id_logging){
            return view('tarefa.edit', ['tarefa' => $tarefa]);
          
        }
        else{
            return view('acesso-negado');
        }

    

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tarefa $tarefa)
    {
      
    // Obtém o ID do usuário logado
    $user_id_logging = auth()->user()->id;

    // Verifica se o usuário logado é o proprietário da tarefa
    if ($tarefa->user_id == $user_id_logging) {
  
        tarefa::create(request()->all());
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    } 
    
    else {
       
        return view('acesso-negado');
    }
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(tarefa $tarefa)
    {
        $user_id_logging = auth()->user()->id;
       
        if($tarefa->user_id == $user_id_logging){
           
           $tarefa->delete();
            return redirect()->route('tarefa.index');

        }
        else{
            return view('acesso-negado');
        }
    }

    public function exportacao($extensao)
    {
            
        $nome_do_arquivo = 'lista_de_tarefas';

      
        if($extensao == 'xlsx'){
            $nome_do_arquivo .= '.'.$extensao;
        }
         else if ($extensao == 'csv'){
            $nome_do_arquivo .= '.'.$extensao;
         }
        
        else if($extensao == 'pdf'){
            $nome_do_arquivo .= '.'.$extensao;
        }
        else {
            return redirect()->route('tarefa.index');
        }

        return Excel::download( new TarefasExport, $nome_do_arquivo);
    }   
}

