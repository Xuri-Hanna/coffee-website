function imageOnChange(imageInput){
    let image = document.getElementById('image');
    let file = imageInput.files[0];
    console.log(imageInput)
    let reader = new FileReader();
    if(file){
        reader.readAsDataURL(file);
    }
    reader.addEventListener("load",function (){
        image.src = reader.result;
    },false)

}