### Requisitos mínimos:
 - Docker

### Pacotes usados:
 - (Scoob)[https://github.com/Shieldforce/scoob]

### App irá utiliza as postas: 8094, 6394, 3394

--- 
### Para baixar o app e rodar ele localmente:

```
bash <(curl -s "http://verdometro.com.br/create-app")
```

### Depois atualize o ~/.bashrc ou ~/.zshrc, no meu caso: source ~/.zshrc

```
source ~/.zshrc
```

### Pronto seu to-do-list-terminal está ok


---

## Como usar (As funções são auto-explicativas), Funções:

### Lista de Tarefas (Se acrescentar .all no final retorna todas as tarefas!) O padrão são tarefa não finalizadas!
```
task.list
task.list.all
```

### Adicionar Tarefa
```
task.add
```

### Remover Tarefa
```
task.remove
```

### Finalizar Tarefa
```
task.check
```
