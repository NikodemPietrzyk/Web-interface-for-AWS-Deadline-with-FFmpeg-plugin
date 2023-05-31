const form = document.querySelector('form');


let email = form.elements.namedItem('email');
let password = form.elements.namedItem("password");



// show input error message
function showError(input, message) {
    const formControl = input.parentElement;
    formControl.className = 'form-control error';
    const small = formControl.querySelector('small');
    small.innerText = message;
}
  
  // show success message
function showSuccess(input) {
    formControl = input.parentElement;
    formControl.className = 'form-control success';
    const small = formControl.querySelector('small');
    small.innerText = 'No Error';
}

function checkEmail(input) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (re.test(input.value.trim())) {
      showSuccess(input);
      return true;
    } else {
      showError(input, `${getFieldName(input)} is not valid`);
      return false;
    }
}

//check required fields
function checkRequired(inputArr) {
    var test=true;
    inputArr.forEach(function (input) {
      if (input.value.trim() === '') {
        showError(input, `${getFieldName(input)} is required`);
        test=false;
      } else {
        showSuccess(input);
      }
    });
    return test;
}
  
  
  // Get fieldname
function getFieldName(input) {
    return input.name.charAt(0).toUpperCase() + input.name.slice(1);
}
  

function validateMyForm()   {
    
    if(
        checkRequired([email, password])
        && checkEmail(email)
    ){
        return true;
    }else{
        if(checkRequired([email])){
            checkEmail(email);
        }
        if(checkRequired([password])){
        }
        return false;
    }
}