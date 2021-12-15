$(document).ready(() => {

let imageInfo = $('#imageInfo')[0].value;
let imageRow = Array.from($('#imageRow')[0].children);
let label = imageRow[1];


if(imageInfo){
    label.innerText = 'Imagem selecionada: ' + imageInfo;
} else {
    label.innerText = 'Nenhuma imagem selecionada';
}

$('#Room_image').change((e) => {
    label.innerText = 'Imagem selecionada: ' + event.srcElement.files[0].name;
});

});