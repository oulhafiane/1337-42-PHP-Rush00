var img_preview =  document.getElementById("img-preview");
var imginput = document.getElementById("imginput");
function readURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();
  
      reader.onload = function(e) {
        img_preview .setAttribute("src",e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  
imginput.onchange = function ()
{
    readURL(this);
}