<script>
salle = [];
entry = document.getElementsByClassName("poplight lienCellule")

//cache toute les entrés ayant une salle non contenue dans la variable salle
function hide(){
    for(i = 0; i<entry.length; i++){
        if(salle.includes(entry[i].childNodes[2].data)){
            entry[i].style.display="block";
        }else{
            entry[i].style.display="none";
        }
        
    }
}

//affiche toute les entée
function show(){
    for(i = 0; i<entry.length; i++){
        entry[i].style.display="block";
    }
}

function addRemoveSalle(s, b){
    //si il est déjà dans le tableau
    if(b === bouton[0]){
        salle = [];
        for (let i = 1; i < bouton.length; i++) {
            bouton[i].className="btn btn-default btn-lg btn-block item";
        }
    }else if((index = salle.indexOf(s))> -1){
        b.className="btn btn-default btn-lg btn-block item";
        salle.splice(index, 1);
    }else{
        b.className="btn btn-primary btn-lg btn-block item_select";
        salle.push(s);
    }
    if(salle.length == 0){
        bouton[0].className="btn btn-primary btn-lg btn-block item_select";
        show();
    }else{
        bouton[0].className="btn btn-default btn-lg btn-block item";
        hide();
    }
    
}

// marche pas car dblclick = 2 événement click donc click sera toujour fait en premier
bouton = document.getElementById("room_001").children;

for (let i = 0; i < bouton.length; i++) {
    bouton[i].addEventListener('contextmenu', function (e) {
    e.preventDefault();
    addRemoveSalle(bouton[i].value,bouton[i]);
});
    
}

</script>