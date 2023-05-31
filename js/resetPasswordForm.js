const form = document.querySelector('form');


let email = form.elements.namedItem('email');
let password = form.elements.namedItem("password");
let password2 = form.elements.namedItem('password2');
let password3 = form.elements.namedItem('password3');



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

function checkPassword(input) {
  const pe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,254}$/;
  if (pe.test(input.value.trim())) {
    showSuccess(input);
    return true;
  } else {
    showError(input, `${getFieldName(input)} must be at least 8 characters and contain at least one letter, one number and one special character`);
    return false;
  }
}

function checkPasswordsMatch(input1, input2) {
  if (input1.value !== input2.value) {
    showError(input2, 'Passwords do not match');
    return false;
  } else {
    showSuccess(input2);
    return true;
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
  

function validateMyFormEmail()   {
    
    if(
        checkRequired([email])
        && checkEmail(email)
    ){
        return true;
    }else{
        if(checkRequired([email])){
            checkEmail(email);
        }
        // if(checkRequired([password])){
        // }
        return false;
    }
}


function validateMyFormPassword() {
  if(
    checkRequired([password,password2,password3])
    && checkPassword(password2)
    && checkPassword(password3)
    && checkPasswordsMatch(password2,password3)
){
    return true;
}else{
  if(checkRequired([password])){
    checkPassword(password);
  }
  if(checkRequired([password2])){
    checkPassword(password2);
  }
  if(checkRequired([password3])){
    checkPasswordsMatch(password2, password3);
  }
    return false;
}
}

function validateMyFormNewPassword() {
  if(
    checkRequired([password2,password3])
    && checkPassword(password2)
    && checkPassword(password3)
    && checkPasswordsMatch(password2,password3)
){
    return true;
}else{
  if(checkRequired([password2])){
    checkPassword(password2);
  }
  if(checkRequired([password3])){
    checkPasswordsMatch(password2, password3);
  }
    return false;
}
}