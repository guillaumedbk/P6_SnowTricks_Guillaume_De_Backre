   function addMore(){
       let currentItem = 10;
       let loadMoreBtn = document.getElementById('load-more');

       let boxes = [...document.querySelectorAll('.grid-container .card')];
       for (var i = currentItem; i < currentItem + 5; i++){
           boxes[i].style.display = 'grid';
       }
       currentItem += 5;

       if(currentItem >= boxes.length){
           loadMoreBtn.style.display = 'none';
       }
}
