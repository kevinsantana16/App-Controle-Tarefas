@component('mail::message')
# Introduction

Data limite de conclusão: {{$data_limite_conclusao}}.

@component('mail::button', ['url' => $url])
Clique Aqui para ver a tarefa
@endcomponent

Att,<br>
{{ config('app.name') }}
@endcomponent
