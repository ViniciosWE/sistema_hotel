function sumirMensagem(classe, tempo) {
    setTimeout(function() {
        var mensagens = document.getElementsByClassName(classe);
        for (var i = 0; i < mensagens.length; i++) {
            mensagens[i].style.display = "none";
        }
    }, tempo);
}