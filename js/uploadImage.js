let imageFileName = $('#imageInfo').val();
let imageDivChildrens = $('#imageRow').children();
let imageLabel = imageDivChildrens[1];

if(imageFileName)
    imageLabel.innerText = 'Imagem selecionada: ' + imageFileName;
else 
    imageLabel.innerText = 'Nenhuma imagem selecionada';

$('#Room_image').change((e) => {
    let fileName = e.target.files[0].name;
    imageLabel.innerText = 'Imagem selecionada: ' + fileName;
});