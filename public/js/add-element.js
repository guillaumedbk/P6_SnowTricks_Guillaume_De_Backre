let collection, boutonAjout, span;

window.onload = () => {
    collection = document.querySelector("#create_trick_videoUrl");
    span = collection.querySelector("span");
    boutonAjout = document.createElement("button");
    boutonAjout.className = "ajout-video btn btn-secondary btn-form mt-1";
    boutonAjout.innerText = "Ajouter une vidéo";
    boutonAjout.type = "button";
    let nouveauBouton = span.append(boutonAjout);
    collection.dataset.index = collection.querySelectorAll("input").length;
    boutonAjout.addEventListener("click", function(){
        addButton(collection, nouveauBouton);
    });
}
function addButton(collection, nouveauBouton){
    let prototype = collection.dataset.prototype;
    let index = collection.dataset.index;
    prototype = prototype.replace(/__name__/g, index);
    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");
    let boutonSuppr = document.createElement("button");
    boutonSuppr.type = "button";
    boutonSuppr.className = "btn btn-secondary btn-form";
    boutonSuppr.id = "delete-video-" + index;
    boutonSuppr.innerText = "Supprimer cette vidéo";
    newForm.append(boutonSuppr);
    collection.dataset.index++;
    let boutonAjout = collection.querySelector(".ajout-video");
    span.insertBefore(newForm, boutonAjout);
    boutonSuppr.addEventListener("click", function(){
        this.previousElementSibling.parentElement.remove();
    })
}

function deleteElement(id){
    let image = document.querySelector('#image_'+id);
    let deletedImages = document.getElementById('modify_trick_images_deletedImages');
    if(deletedImages.value !== ''){
        deletedImages.value += ';';
    }
    deletedImages.value += id;
    image.remove();
    console.log(deletedImages.value);
}
