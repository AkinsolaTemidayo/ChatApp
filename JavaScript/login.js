const form = document.querySelector(".login form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-txt");

// form.onsubmit = (e)=> {
//     e.preventDefault(); ///preventing form from submitting
// }

continueBtn.onclick = ()=> {
    //Ajax script
    let xhr = new XMLHttpRequest(); //Creating XML Object
    xhr.open("POST", "php/login.php", true);
    xhr.onload = ()=> {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                console.log(data);
                if(data == "success"){
                    location.href = "users.php";
                }else{
                    errorText.textContent = data;
                    errorText.style.display = "block";
                    
                }
            }
        }
    }
    //sending form data through ajax to php
    let formData = new FormData(form); //creating new formData Object
    xhr.send(formData);//sending the formdata to php
}