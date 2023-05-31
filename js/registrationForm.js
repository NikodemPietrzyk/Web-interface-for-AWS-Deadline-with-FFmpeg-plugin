const form = document.querySelector('form');

let firstname = form.elements.namedItem("firstname");
let surname = form.elements.namedItem('surname');
let email = form.elements.namedItem('email');
let password = form.elements.namedItem("password");
let password2 = form.elements.namedItem('password2');


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
  
  //check input lenght
function checkLength(input, min, max) {
    if (input.value.length < min) {
      showError(input, `${getFieldName(input)} must be at least ${min} characters`);
      return false;
    } else if (input.value.length > max) {
      showError(input, `${getFieldName(input)} must be less than ${max} characters`);
      return false;
    } else {
      showSuccess(input);
      return true;
    }
}
  
  // check passwords match
  
function checkPasswordsMatch(input1, input2) {
    if (input1.value !== input2.value) {
      showError(input2, 'Passwords do not match');
      return false;
    } else {
      showSuccess(input2);
      return true;
    }
}
  
  // Get fieldname
function getFieldName(input) {
    return input.name.charAt(0).toUpperCase() + input.name.slice(1);
}
  


function validate (e) {
 switch (e.target.name) {
    case "firstname":
        checkLength(firstname, 2, 254);
        break;
    case "surname":
        checkLength(surname, 2, 254);
        break;
    case "email":
        checkEmail(email);
        break;
    case "password":
        checkPassword(password);
        break;
    case "password2":
        checkPasswordsMatch(password, password2);
        break;
 }
}



firstname.addEventListener('input', validate);
surname.addEventListener('input', validate);
email.addEventListener('input', validate);
password.addEventListener('input', validate);
password2.addEventListener('input', validate);


function validateMyForm()   {
    
    if(
        checkRequired([firstname,surname, email, password, password2])
        && checkLength(firstname, 2, 254) 
        && checkLength(surname, 2, 254)
        && checkEmail(email)
        && checkPassword(password)
        && checkPasswordsMatch(password, password2)
    ){
        return true;
    }else{
        if(checkRequired([firstname])){
            checkLength(firstname, 2, 254);
        }
        if(checkRequired([surname])){
            checkLength(surname, 2, 254);
        }
        if(checkRequired([email])){
            checkEmail(email);
        }
        if(checkRequired([password])){
            checkPassword(password);
        }
        if(checkRequired([password2])){
            checkPasswordsMatch(password, password2);
        }
        return false;
    }
}